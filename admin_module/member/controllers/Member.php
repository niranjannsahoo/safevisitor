<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Member extends CI_Controller {
	private $error = array();
	
	public function __construct(){
      parent::__construct();
		$this->load->model('member_model');		
	}
	
	public function index(){
		
      $this->lang->load('member');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	public function search($user_group_id) {
		$requestData= $_REQUEST;
		
		
		$totalData = $this->member_model->getTotalMember();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'filter_group_id'=> $user_group_id,
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->member_model->getTotalMember($filter_data);
			
		$filteredData = $this->member_model->getMembers($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.site_url('member/edit/'.$result->user_id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.base_url('member/delete/'.$result->user_id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				$result->username,
				$result->firstname,
				$result->email,
				$result->enabled ? 'Enable':'Disable',
				$action
			);
	
		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($json_data));  // send data as json format
	}
	
	public function add(){
		$this->lang->load('member');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$userid=$this->member_model->addMember($this->input->post());
			
			$this->session->set_flashdata('message', 'Member Saved Successfully.');
			redirect("member");
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('member');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$user_id=$this->uri->segment(3);
			$this->member_model->editMember($user_id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Member Updated Successfully.');
			redirect("member");
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(3);
       }
		$this->member_model->deleteMember($selected);
		$this->session->set_flashdata('message', 'Member deleted Successfully.');
		redirect("member");
	}
	
	protected function getList() {
		$user_group_id=$this->uri->segment(3);
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = base_url('member/add');
		$data['delete'] = base_url('member/delete');
		$data['datatable_url'] = base_url("member/search/$user_group_id");

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('member', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor'),true);		
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(3) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		$data['cancel'] = base_url('member');

		if ($this->uri->segment(3) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$user_info = $this->member_model->getMember($this->uri->segment(3));
		}
		
		if ($this->input->post('username')) {
			$data['username'] = $this->input->post('username');
		} elseif (!empty($user_info)) {
			$data['username'] = $user_info->username;
		} else {
			$data['username'] = '';
		}
		
		if ($this->input->post('user_group_id')) {
			$data['user_group_id'] = $this->input->post('user_group_id');
		} elseif (!empty($user_info)) {
			$data['user_group_id'] = $user_info->user_group_id;
		} else {
			$data['user_group_id'] = '';
		}

		$data['user_groups'] = $this->member_model->getMemberGroups();

		if ($this->input->post('firstname')) {
			$data['firstname'] = $this->input->post('firstname');
		} elseif (!empty($user_info)) {
			$data['firstname'] = $user_info->firstname;
		} else {
			$data['firstname'] = '';
		}
		
		
		if ($this->input->post('email')) {
			$data['email'] = $this->input->post('email');
		} elseif (!empty($user_info)) {
			$data['email'] = $user_info->email;
		} else {
			$data['email'] = '';
		}
		
		if ($this->input->post('company')) {
			$data['company'] = $this->input->post('company');
		} elseif (!empty($user_info)) {
			$data['company'] = $user_info->company;
		} else {
			$data['company'] = '';
		}
		
		if ($this->input->post('phone')) {
			$data['phone'] = $this->input->post('phone');
		} elseif (!empty($user_info)) {
			$data['phone'] = $user_info->phone;
		} else {
			$data['phone'] = '';
		}
		
		if ($this->input->post('address')) {
			$data['address'] = $this->input->post('address');
		} elseif (!empty($user_info)) {
			$data['address'] = $user_info->address;
		} else {
			$data['address'] = '';
		}
		
		$data['businesstypes']= $this->member_model->getBusinessTypes();
		
		if ($this->input->post('business_id')) {
			$data['business_id'] = $this->input->post('business_id');
		} elseif (!empty($user_info)) {
			$data['business_id'] = $user_info->business_id;
		} else {
			$data['business_id'] = '';
		}
		
		if ($this->input->post('password')) {
			$data['password'] = $this->input->post('password');
		} elseif (!empty($user_info)) {
			$data['password'] = $user_info->show_password;
		} else {
			$data['password'] = '';
		}
		
		if ($this->input->post('enabled')) {
			$data['enabled'] = $this->input->post('enabled');
		} elseif (!empty($user_info)) {
			$data['enabled'] = $user_info->enabled;
		} else {
			$data['enabled'] = '';
		}
		
		$this->template->view('member_form',$data);
	}

	protected function validateForm() {
		$user_id=$this->uri->segment(3);
		$group_id=$this->input->post('user_group_id');
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'firstname' => array(
				'field' => 'firstname', 
				'label' => 'First Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'email' => array(
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => "trim|required|valid_email|callback_email_check[$user_id]"
			),
			'business_id' => array(
				'field' => 'business_id', 
				'label' => 'Business Type', 
				'rules' => "trim|callback_businesstype[$group_id]"
			),
			'username' => array(
				'field' => 'username', 
				'label' => 'Membername', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]|callback_username_check[$user_id]"
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
		return !$this->error;
	}
	
	public function email_check($email, $user_id=''){
      
		$Member = $this->member_model->getMemberByEmail($email);
		
      if (!empty($Member) && $Member->user_id != $user_id){
			$this->form_validation->set_message('email_check', "This email address is already in use.");
         return FALSE;
		}else{
         return TRUE;
      }
   }
	
	public function username_check($username, $user_id=''){
      $Member = $this->member_model->getMemberByMembername($username);
		
      if (!empty($Member) && $Member->user_id != $user_id){
            $this->form_validation->set_message('username_check', "This {field} provided is already in use.");
            return FALSE;
		}else{
         return TRUE;
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