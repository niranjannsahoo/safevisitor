<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Error extends CI_Controller {
	private $error = array();
	
	function __construct(){
      
		parent::__construct();
	}
	public function index(){
		
		$data=array();
	
		$this->template->view('common/error',$data);
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */