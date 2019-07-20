<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends Admin_Controller {
	private $error = array();
	
	public function __construct(){
      parent::__construct();
		$this->load->model('users_model');		
	}
	
	public function index(){
      $this->lang->load('users');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	public function search() {
		$requestData= $_REQUEST;
		
		$columns = array( 
			0 => '',
			1 => 'u.image',
			2 => 'u.username',
			3 => 'u.firstname',
			4 => 'u.lastname',
			5 => 'u.email',
			6 => 'ug.name',
			7 => 'u.status'
		);
		
		$totalData = $this->users_model->getTotalUsers();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => intval($requestData['order'][0]['column']) ? $columns[$requestData['order'][0]['column']] : 'u.user_id',
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->users_model->getTotalUsers($filter_data);
			
		$filteredData = $this->users_model->getUsers($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			if (is_file(DIR_UPLOAD . $result->image)) {
				$image = resize($result->image, 40, 40);
			} else {
				$image = resize('no_image.png', 40, 40);
			}
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.site_url('users/edit/'.$result->user_id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.base_url('users/delete/'.$result->user_id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->user_id.'" />',
				'<img src="'.$image.'" alt="'.$result->username.'" class="img-thumbnail" />',
				$result->username,
				$result->firstname,
				$result->lastname,
				$result->email,
				$result->group,
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
		$this->lang->load('users');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$userdata=array(
				"user_group_id"=>$this->input->post('user_group_id'),
				"username"=>$this->input->post('username'),
				"password"=>md5($this->input->post('password')),
				"show_password"=>$this->input->post('password'),
				"firstname"=>$this->input->post('firstname'),
				"lastname"=>$this->input->post('lastname'),
				"email"=>$this->input->post('email'),
				"image"=>$this->input->post('image'),		
				"activated"=>1,
				"enabled"=>$this->input->post('enabled'),
				"date_modified"=>date('Y-m-d H:i:s')
			);
			
			$userid=$this->users_model->addUser($userdata);
			
			$this->session->set_flashdata('message', 'Users Saved Successfully.');
			redirect("users");
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('users');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$user_id=$this->uri->segment(3);
			$userdata=array(
				"user_group_id"=>$this->input->post('user_group_id'),
				"username"=>$this->input->post('username'),
				"password"=>md5($this->input->post('password')),
				"show_password"=>$this->input->post('password'),
				"firstname"=>$this->input->post('firstname'),
				"lastname"=>$this->input->post('lastname'),
				"email"=>$this->input->post('email'),
				"image"=>$this->input->post('image'),		
				"activated"=>1,
				"enabled"=>$this->input->post('enabled'),
				"date_added"=>date('Y-m-d H:i:s')
			);
			
			$userid=$this->users_model->editUser($user_id,$userdata);
			
			$this->session->set_flashdata('message', 'Users Updated Successfully.');
			redirect("users");
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(3);
       }
		$this->users_model->deleteUser($selected);
		$this->session->set_flashdata('message', 'Users deleted Successfully.');
		redirect("users");
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => base_url('users')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = base_url('users/add');
		$data['delete'] = base_url('users/delete');
		$data['datatable_url'] = base_url('users/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['column_username'] = $this->lang->line('column_username');
		$data['column_status'] = $this->lang->line('column_status');
		$data['column_date_added'] = $this->lang->line('column_date_added');
		$data['column_action'] = $this->lang->line('column_action');

		$data['button_add'] = $this->lang->line('button_add');
		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('users', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','colorbox','select2'),true);
      
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => base_url('users')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_add'),
			'href' => base_url('users/add')
		);
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(3) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['text_image'] =$this->lang->line('text_image');
		$data['text_none'] = $this->lang->line('text_none');
		$data['text_default'] = $this->lang->line('text_default');
		$data['text_enabled'] = $this->lang->line('text_enabled');
		$data['text_disabled'] = $this->lang->line('text_disabled');
		$data['text_clear'] = $this->lang->line('text_clear');
		$data['entry_firstname'] = $this->lang->line('entry_firstname');
		$data['entry_lastname'] = $this->lang->line('entry_lastname');
		$data['entry_user_group'] = $this->lang->line('entry_user_group');
		$data['entry_email'] = $this->lang->line('entry_email');
		$data['entry_username'] = $this->lang->line('entry_username');
		$data['entry_password'] = $this->lang->line('entry_password');
		$data['entry_status'] = $this->lang->line('entry_status');
		$data['entry_image'] = $this->lang->line('entry_image');
		
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		$data['cancel'] = base_url('users');

		if ($this->uri->segment(3) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$user_info = $this->users_model->getUser($this->uri->segment(3));
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

		$this->load->model('users/users_group_model');

		$data['user_groups'] = $this->users_group_model->getUserGroups();

		
		if ($this->input->post('firstname')) {
			$data['firstname'] = $this->input->post('firstname');
		} elseif (!empty($user_info)) {
			$data['firstname'] = $user_info->firstname;
		} else {
			$data['firstname'] = '';
		}
		
		if ($this->input->post('lastname')) {
			$data['lastname'] = $this->input->post('lastname');
		} elseif (!empty($user_info)) {
			$data['lastname'] = $user_info->lastname;
		} else {
			$data['lastname'] = '';
		}
		
		if ($this->input->post('email')) {
			$data['email'] = $this->input->post('email');
		} elseif (!empty($user_info)) {
			$data['email'] = $user_info->email;
		} else {
			$data['email'] = '';
		}
		
		if ($this->input->post('image')) {
			$data['image'] = $this->input->post('image');
		} elseif (!empty($user_info)) {
			$data['image'] = $user_info->image;
		} else {
			$data['image'] = '';
		}
		
		if ($this->input->post('image') && is_file(DIR_UPLOAD . $this->input->post('image'))) {
			$data['thumb_image'] = resize($this->input->post('image'), 100, 100);
		} elseif (!empty($user_info) && is_file(DIR_UPLOAD . $user_info->image)) {
			$data['thumb_image'] = resize($user_info->image, 100, 100);
		} else {
			$data['thumb_image'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		
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
		
		$this->template->view('users_form',$data);
	}

	protected function validateForm() {
		$user_id=$this->uri->segment(3);
		
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'firstname' => array(
				'field' => 'firstname', 
				'label' => 'First Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			'lastname' => array(
				'field' => 'lastname', 
				'label' => 'Last Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			'email' => array(
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => "trim|required|valid_email|callback_email_check[$user_id]"
			),
			'username' => array(
				'field' => 'username', 
				'label' => 'Username', 
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
      
		$User = $this->users_model->getUserByEmail($email);
		
      if (!empty($User) && $User->user_id != $user_id){
			$this->form_validation->set_message('email_check', "This email address is already in use.");
         return FALSE;
		}else{
         return TRUE;
      }
   }
	
	public function username_check($username, $user_id=''){
      $User = $this->users_model->getUserByUsername($username);
		
      if (!empty($User) && $User->user_id != $user_id){
            $this->form_validation->set_message('username_check', "This {field} provided is already in use.");
            return FALSE;
		}else{
         return TRUE;
      }
   }
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */