<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Header extends MY_Controller {
	
	function __construct(){
		parent::__construct();
	}
	public function index(){
		
		$data=array();
		
		$data['site_name'] = $this->settings->config_site_title;
		
		if (is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['logo'] = base_url('assets') . '/' . $this->settings->config_site_logo;
		} else {
			$data['logo'] = '';
		}
	
		$data['menu']=$this->plugin->nav_menu(
			array(
				'theme_location'=>'',
				'menu_group_id'  => '7',
				'menu_class'     => 'nav-menu'
			)
		);
		
		if ($this->uri->segment(1)) {
			$data['class'] = $this->uri->segment(1);
		} else {
			$data['class'] = 'common-home';
		}
		
		$this->load->view('header',$data);
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */