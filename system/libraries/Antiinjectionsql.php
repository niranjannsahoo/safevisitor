<?php
/*
*  Class: Anti Injection SQL
*  Developed by: Jesús Matiz
*  Year: 2017
*  Version: 1.3
*  License: GNU General Public License (GPL) 
*  URL Lincese: https://www.gnu.org/licenses/licenses.en.html
*/
try { // Try, Error handling

  $method = $_SERVER['REQUEST_METHOD'];
  $variables = null;
  $target = "";
  $error = 0;

  if( $method == "GET" ){
    $variables = $_GET;
  } else if( $method == "POST" ){
    $variables = $_POST;
  } else {
    echo "No Data";
    return ;
  }

  $antiInjection = new AntiInjectionSQL();
  $antiInjection->targetSQL($variables);

}
catch(Exception $ex) {}

class AntiInjectionSQL{

  public function targetSQL($variables) {

    if( !empty($variables) ){

      foreach ($variables as $key => $val) {    
        
        $value = $variables[$key];

        if( is_array( $value ) ){

          foreach($value as $k => $val){            
              $valor = $value[$key]; 
               // Doubled spaces are removed
              $valor = str_replace("  ", "", $valor);
              $this->compareString($valor);
          }
          
        } else {
          // Doubled spaces are removed
          $value = str_replace("  ", "", $value);
          $this->compareString($value);
        }         

      }

    }    

  }

  private function compareString($value){

    // Queries used for SQL injection
    $reserved = "|select\[\*\]from|select \* from|select\*from|'or'1'=1|";
    $reserved .= "or1=1|update set|insert into|delete from|";
    $reserved .= "order by|1'1|select count\(\[\*\]\)|select count\(\*\)|1 and 1=1|";
    $reserved .= "&#49|&#32|&#79|&#82|&#61|&#39|1 UNION ALL SELECT 1,2,3,4,5,6,name FROM sysObjects WHERE xtype = 'U' --|";
    $reserved .= "1 AND ASCII\(LOWER\(SUBSTRING\(\(SELECT TOP 1 name FROM sysobjects WHERE xtype='U'\), 1, 1\)\)\) > 116|";
    $reserved .= "1' AND 1=(SELECT COUNT\(\[\*\]\) FROM tablenames); --|";
    $reserved .= "1 UNI\/\[\*\*\]\/ON SELECT ALL FROM WHERE|%31|%27|%20|%4F|%52|%3D|";
    $reserved .= "&#x31|&#x27|&#x20|&#x4F|&#x52|&#x3D|";
    $reserved .= "' OR username IS NOT NULL OR username = '|'; DESC users; --|1' AND non_existant_table = '1|";
    $reserved .= "1 AND USER_NAME\(\) = 'dbo'|1 EXEC XP_|";
    $reserved .= "select|";

    // If you want to add more queries to the SQl injection detection
    // Just add the $reserved variable and end it with |
    // Example: $reserved. = "My query to detect |";

    // Regular expression for the search of queries of type SQL injection
    $RegExp = "/\s ".$reserved." \s/i";

    $search = preg_match($RegExp, $value); // Look for matches of SQL injection type queries

    if($search >= 1) // If there are matches, add the data to the destination
    {    
      $target = $value;
      $error = 1;
    } 

    // In case of detecting an injection sql is written in the file Injection_log.txt
    if( $error == 1 ){
      
      $ctn = "";

      if( !file_exists("Injection_log.txt") ){
        fopen("Injection_log.txt", "w+");
      }

      $fr = fopen("Injection_log.txt", "r");
      while(!feof($fr)) {
        $ctn .= fgets($fr);
      }
      fclose($fr);

      $fw = fopen("Injection_log.txt", "w");
      fwrite($fw, $ctn . PHP_EOL);
      fwrite($fw, htmlspecialchars($target)." - ".$_SERVER['REMOTE_ADDR']." - ".date('Y-m-d H:i:s'). PHP_EOL);
      fclose($fw);

      die();

    }

  }

} // END CLASS