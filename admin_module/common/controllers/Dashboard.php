<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends Admin_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		//redirect('pages');
		$data=array();
		$this->lang->load('dashboard');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$this->template->view('dashboard',$data);
	}
	public function test(){
		echo "test";
		echo $this->uri->segment(5);
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */