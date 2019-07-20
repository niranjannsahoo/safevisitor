<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Column_top extends MX_Controller {

	public function index()
	{
		$data=array();
		$this->lang->load('column_left');
		
		$data['menu']=$this->plugin->nav_menu(
			array(
				'theme_location' => 'admin',
				'menu_group_id'  => '1',
				'menu_class'     => 'horizontal white'
			)
		);
		
		$this->load->view('column_top',$data);
	}
}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
