<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Header extends MX_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data=array();
		
		$data['site_name'] = $this->settings->config_site_title;
		if (is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['logo'] = base_url('storage/uploads') . '/' . $this->settings->config_site_logo;
		} else {
			$data['logo'] = '';
		}
		
		
		$data['profile_img'] = $this->user->getImage();
		$data['profile'] = base_url('users/profile');
		$data['settings'] = base_url('setting');
		$data['lock'] = base_url('common/lock');
		$data['logout'] = admin_url('common/logout');
		if($this->user->isLogged()){
			$data['menu']=Modules::run('common/column_left/index'); 
		}
		
		$this->load->view('header',$data);
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */