<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends MY_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('common_model');
	}
	
	public function index(){
		$data=array();
		$this->lang->load('register');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){
         $user_id=$this->common_model->addMember($this->input->post());
			if($this->input->post('user_group_id')==3){
				$this->session->set_flashdata('message', 'Successfully Signup and login after activate your account');
			}else{
				$this->session->set_flashdata('message', 'Successfully Signup your account');
			}
			redirect("common/login");
      }

		$data['heading_title']	= $this->lang->line('heading_title');
		
		if (isset($this->error['warning'])) {
			$data['error'] = $this->error['warning'];
		} 
		
		
		
		$data['user_group_id'] = $this->uri->segment(4);
		
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} else {
			$data['name'] = '';
		}
		
		if ($this->input->post('email')) {
			$data['email'] = $this->input->post('email');
		} else {
			$data['email'] = '';
		}

		if ($this->input->post('company')) {
			$data['company'] = $this->input->post('company');
		} else {
			$data['company'] = '';
		}
		
		if ($this->input->post('phone')) {
			$data['phone'] = $this->input->post('phone');
		} else {
			$data['phone'] = '';
		}
		
		if ($this->input->post('address')) {
			$data['address'] = $this->input->post('address');
		} else {
			$data['address'] = '';
		}
		
		$data['businesstypes']= $this->common_model->getBusinessTypes();
		
		if ($this->input->post('business_id')) {
			$data['business_id'] = $this->input->post('business_id');
		} else {
			$data['business_id'] = '';
		}
		
		
		if ($this->input->post('username')) {
			$data['username'] = $this->input->post('username');
		} else {
			$data['username'] = '';
		}
		
		if ($this->input->post('password')) {
			$data['password'] = $this->input->post('password');
		} else {
			$data['password'] = '';
		}
		
		$this->template->view('common/register',$data);
	}
	
	protected function validateForm(){
		$group_id=$this->input->post('user_group_id');
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Name', 
				'rules' => 'trim|required'
			),
			'email' => array(
				'field' => 'email', 
				'label' => 'Username', 
				'rules' => 'trim|required|valid_email|is_unique[user.email]'
			),
			'username' => array(
				'field' => 'username', 
				'label' => 'Username', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]|is_unique[user.username]"
			),
			'password' => array(
				'field' => 'password', 
				'label' => 'Password', 
				'rules' => 'trim|required'
			),
			'business_id' => array(
				'field' => 'business_id', 
				'label' => 'Business Type', 
				'rules' => "trim|callback_businesstype[$group_id]"
			),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=' Warning: Please check the form carefully for errors !!';
			return false;
    	}
   }
	
	public function businesstype($type, $group_id = ''){
		if($group_id==3 && $this->input->post('business_id')==""){
			
			$this->form_validation->set_message('businesstype', 'This {field} is required');
         return FALSE;
		}else{
			return TRUE;
		}
   }
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */