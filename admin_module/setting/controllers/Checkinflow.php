<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Checkinflow extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('setting_model');
		$this->load->model('users/users_model');		
	}
	
	public function index(){
		// Init
      $data = array();
		$data = $this->lang->load('checkinflow');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->template->add_package(array('ckeditor','colorbox'),true);
        
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('checkinflow')
		);
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){
			printr($this->input->post());
			exit;
			$this->setting_model->editSetting('checkin',$this->input->post());
			$this->session->set_flashdata('message', 'Checkinflow Saved');
			redirect(current_url());
		}
		
		
		$data['action'] = admin_url('checkinflow');
		$data['cancel'] = admin_url('checkinflow');

				
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		
		/*Phone Tab*/
		if ($this->input->post('config_phone_label')){
			$data['config_phone_label'] = $this->input->post('config_phone_label');
		} else {
			$data['config_phone_label'] = $this->settings->config_phone_label;
		}
		
		if ($this->input->post('config_phone_otp')){
			$data['config_phone_otp'] = $this->input->post('config_phone_otp');
		} else {
			$data['config_phone_otp'] = $this->settings->config_phone_otp;
		}

		/*Address Tab*/

		if ($this->input->post('config_address_host')){
			$data['config_address_host'] = $this->input->post('config_address_host');
		} else {
			$data['config_address_host'] = $this->settings->config_address_host;
		}

		$data['fieldTypes']=array(
			'text'=>'Textbox',
			'radio'=>'Single Choice',
			'checkbox'=>'Multi Choice',
			'select'=>'Dropdown'
		);

		//$this->settings->config_address_field = 

		if ($this->input->post('config_address_field')){
			$data['config_address_field'] = $this->input->post('config_address_field');
		} else {
			$data['config_address_field'] = (array)$this->settings->config_address_field;
		}

		/*Card Tab*/
		if ($this->input->post('config_card_info')){
			$data['config_card_info'] = $this->input->post('config_card_info');
		} else {
			$data['config_card_info'] = $this->settings->config_card_info;
		}

		/*Photo Tab*/
		if ($this->input->post('config_photo_info')){
			$data['config_photo_info'] = $this->input->post('config_photo_info');
		} else {
			$data['config_photo_info'] = $this->settings->config_photo_info;
		}
       
			
      $this->template->view('checkinflow', $data);
	}
	
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */