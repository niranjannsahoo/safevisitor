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
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validatecheckinflow()){
			//printr($this->input->post());
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
		$data['fieldTypes']=array(
			'text'=>'Textbox',
			'radio'=>'Single Choice',
			'checkbox'=>'Multi Choice',
			'select'=>'Dropdown'
		);

		if ($this->input->post('config_address_field')){
			$data['config_address_field'] = $this->input->post('config_address_field');
		} else {
			$data['config_address_field'] = (array)$this->settings->config_address_field;
		}


		
       
			
      $this->template->view('checkinflow', $data);
	}
	
	public function validatecheckinflow(){
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
     	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		
		$rules=array(
			'config_site_title' => array(
				'field' => 'config_site_title', 
				'label' => 'Site Title', 
				'rules' => "trim|required"
			),
			'config_site_tagline' => array(
				'field' => 'config_site_tagline', 
				'label' => 'Site Tagline', 
				'rules' => "trim|required"
			),
			'config_meta_title' => array(
				'field' => 'config_meta_title', 
				'label' => 'Meta Title', 
				'rules' => "trim|required"
			),
			'config_site_owner' => array(
				'field' => 'config_site_owner', 
				'label' => 'Site Owner', 
				'rules' => "trim|required"
			),
			'config_address' => array(
				'field' => 'config_address', 
				'label' => 'Site Address', 
				'rules' => "trim|required"
			),
			'config_country_id' => array(
				'field' => 'config_country_id', 
				'label' => 'Country', 
				'rules' => "trim|required"
			),
			'config_state_id' => array(
				'field' => 'config_state_id', 
				'label' => 'State', 
				'rules' => "trim|required"
			),
			'config_email' => array(
				'field' => 'config_email', 
				'label' => 'Email', 
				'rules' => "trim|required|valid_email"
			),
			'config_telephone' => array(
				'field' => 'config_telephone', 
				'label' => 'Telephone', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_front' => array(
				'field' => 'config_pagination_limit_front', 
				'label' => 'Pagination limit For front', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_admin' => array(
				'field' => 'config_pagination_limit_admin', 
				'label' => 'pagination limit for admin', 
				'rules' => "trim|required|numeric"
			),
			'username' => array(
				'field' => 'username', 
				'label' => 'Username', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]"
			),
			'password' => array(
				'field' => 'password', 
				'label' => 'Password', 
				'rules' => 'trim|required|max_length[100]'
			),
			
		);
		
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=$this->lang->line('error_warning');
			return false;
    	}
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */