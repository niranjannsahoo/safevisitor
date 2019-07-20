<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AIO Admin
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class MY_Form_validation extends CI_Form_validation 
{
	 public $CI;
	 public function __construct($config = array()){
        parent::__construct($config);
        $this->CI =& get_instance();
     }
	/*function run($module = '', $group = '')
   {
      (is_object($module)) AND $this->CI = &$module;
      return parent::run($group);
   }*/
	
    public function is_unique($str, $field){
      
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);

        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, 'id !=' => $id));
        if($query->num_rows() === 0) {
          return TRUE;
        }else {
          if(!array_key_exists('is_unique',$this->_error_messages)) {
              $this->CI->form_validation->set_message('is_unique', "The %s already in used");
          }
          return FALSE;
        }
        /*return ($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, 'id !=' => $id))->num_rows() === 0)
            : FALSE;*/
    }

    public function exists($str, $field){
  
      list($table, $field)=explode('.', $field);

      $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
      //echo $query;
      if($query->num_rows() !== 0) {
        return TRUE;
      }else {
        if(!array_key_exists('exists',$this->_error_messages)) {
            $this->CI->form_validation->set_message('exists', "The %s does not exist");
        }
        return FALSE;
      }
  }
}
