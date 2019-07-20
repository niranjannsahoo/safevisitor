<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */
class Admin_Controller extends MX_Controller{
	
	private $CI;
	function __construct(){
		
		parent::__construct();
		 
		$this->CI =& get_instance();
		$this->form_validation->CI =& $this;
     
		// Create Dynamic Page Title
      if ( ! $title = str_replace('-', ' ', $this->uri->segment(1))){
         $title = 'Home';
      }

      if ($segment2 = str_replace('-', ' ', $this->uri->segment(2))){
         $title = $segment2 . " - " . $title;
      }
		
      $this->template->set_theme('admin');
		
		$this->template->title(ucwords($title) . " | " . $this->settings->config_site_name);

		$this->template->set('sitename', $this->settings->site_name);
		
		//$this->config->set_item('base_url', admin_url());
		
		if($this->user->isLogged())
		{
			//$this->template->add_stylesheet('assets/css/style.css');
		}
	}
	public function _remap($method, $params = array())
   {
		
		if($this->user->checkLogin()){
			return Modules::run("common/login/index",$params);		
		}else if($this->user->checkPermission()){
			return modules::run("common/error",$params);		
		}else if (method_exists($this, $method)){
			return call_user_func_array(array($this, $method), $params);
		}else{
			return modules::run("common/error",$params);
		}
		//show_404();
    }
	
}