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
		   
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('checkinflow')
		);
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){
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
		if ($this->input->post('checkin_phone_label')){
			$data['checkin_phone_label'] = $this->input->post('checkin_phone_label');
		} else {
			$data['checkin_phone_label'] = $this->settings->checkin_phone_label;
		}
		
		if ($this->input->post('checkin_phone_otp')){
			$data['checkin_phone_otp'] = $this->input->post('checkin_phone_otp');
		} else {
			$data['checkin_phone_otp'] = $this->settings->checkin_phone_otp;
		}

		/*Address Tab*/

		if ($this->input->post('checkin_address_host')){
			$data['checkin_address_host'] = $this->input->post('checkin_address_host');
		} else {
			$data['checkin_address_host'] = $this->settings->checkin_address_host;
		}

		$data['fieldTypes']=array(
			'text'=>'Textbox',
			'radio'=>'Single Choice',
			'checkbox'=>'Multi Choice',
			'select'=>'Dropdown'
		);

		//$this->settings->checkin_address_field = 

		if ($this->input->post('checkin_address_field')){
			$data['checkin_address_field'] = $this->input->post('checkin_address_field');
		} else {
			$data['checkin_address_field'] = (array)$this->settings->checkin_address_field;
		}

		/*Card Tab*/
		if ($this->input->post('checkin_card_info')){
			$data['checkin_card_info'] = $this->input->post('checkin_card_info');
		} else {
			$data['checkin_card_info'] = $this->settings->checkin_card_info;
		}

		/*Photo Tab*/
		if ($this->input->post('checkin_photo_info')){
			$data['checkin_photo_info'] = $this->input->post('checkin_photo_info');
		} else {
			$data['checkin_photo_info'] = $this->settings->checkin_photo_info;
		}
		
		/*Notification Tab*/
		if ($this->input->post('checkin_notification_host')){
			$data['checkin_notification_host'] = $this->input->post('checkin_notification_host');
		} else {
			$data['checkin_notification_host'] = $this->settings->checkin_notification_host;
		}
		
		if ($this->input->post('checkin_notification_visitor')){
			$data['checkin_notification_visitor'] = $this->input->post('checkin_notification_visitor');
		} else {
			$data['checkin_notification_visitor'] = $this->settings->checkin_notification_visitor;
		}
		
		if ($this->input->post('checkin_notification_sms')){
			$data['checkin_notification_sms'] = $this->input->post('checkin_notification_sms');
		} else {
			$data['checkin_notification_sms'] = $this->settings->checkin_notification_sms;
		}
       
			
      $this->template->view('checkinflow', $data);
	}
	
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */