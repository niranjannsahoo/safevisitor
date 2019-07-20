<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Footer extends MX_Controller {

	public function index()
	{
		$data=array();
		$this->load->view('footer',$data);
	}
}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
