<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */
class MY_Controller extends MX_Controller
{
	private $CI;
	function __construct()
	{
		
		parent::__construct();
		$this->CI =& get_instance();
		$this->form_validation->CI =& $this;
      // Create Dynamic Page Title
      if ( ! $title = str_replace('-', ' ', $this->uri->segment(1)))
      {
         $title = 'Home';
      }

      if ($segment2 = str_replace('-', ' ', $this->uri->segment(2)))
      {
         $title = $segment2 . " - " . $title;
      }
		
      $this->template->set_meta_title(ucwords($title) . " | " . $this->settings->config_site_title);
		$this->template->set_theme($this->settings->config_front_theme);
		//$this->template->set('sitename', $this->settings->site_name);
		
	}
	public function _remap($method, $params = array()){
		
		$slug = trim($this->uri->uri_string(), '/');
		
		if ($this->settings->maintenance_mode){
         return $this->load->view('maintenance', array());
      }elseif (!$slug){
			echo modules::run("pages/home",$params);
		}else if (method_exists($this, $method)){
			return call_user_func_array(array($this, $method), $params);
		}else{
			return modules::run("pages/error",$params);
		}
		//show_404();
   }
	
	
}