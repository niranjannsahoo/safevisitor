<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends REST_Controller {
	private $error = array();
	
	function __construct(){
		parent::__construct();
		$this->load->model('users_model');
	}
	
	public function index_get(){
		$json=array(
				'status'=>false,
				'users'=>'',
				'message'=>'No Users Found'
			);
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}

	public function forms_post(){
		$json=array();

		$results=$this->users_model->getForms($this->post('id'));
		$res=array();
		//printr($results);
		if($results){
			foreach($results as $result){
				$res[]=array(
					'id'=>$result->id,
					'user_id'=>$result->user_id,
					'section0'=>json_decode($result->section0),
					'section1'=>json_decode($result->section1),
					'section2'=>json_decode($result->section2),
					'section3'=>json_decode($result->section3),
					'section4'=>json_decode($result->section4),
					'section5'=>json_decode($result->section5),
					'section6'=>json_decode($result->section6),
					'section7'=>json_decode($result->section7),
					'date_added'=>$result->date_added
				);
			}
			$json=array(
				'status'=>1,
				'forms'=>$res,
				
			);
		}else{
			$json=array(
				'status'=>0,
				'errors'=>'No data Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}

	public function form_post(){
		$json=array();

		$result=$this->users_model->getForm($this->post('formid'));
		$res=array();
		//printr($results);
		if($result){
			
			$res=array(
				'id'=>$result->id,
				'user_id'=>$result->user_id,
				'section0'=>json_decode($result->section0),
				'section1'=>json_decode($result->section1),
				'section2'=>json_decode($result->section2),
				'section3'=>json_decode($result->section3),
				'section4'=>json_decode($result->section4),
				'section5'=>json_decode($result->section5),
				'section6'=>json_decode($result->section6),
				'section7'=>json_decode($result->section7),
				'date_added'=>$result->date_added
			);
		
			$json=array(
				'status'=>1,
				'form'=>$res,
			);
		}else{
			$json=array(
				'status'=>0,
				'errors'=>'No data Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}


	public function register_post(){
		$json=array();

		if(!$this->validateRegisterForm()){
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}
		if (!$json) {
			$usersdata=array(
				'user_group_id'	=>3,
				'email'=>$this->post('email'),
				'password'	=>md5($this->post('password')),
				'show_password'	=>$this->post('password'),
				"enabled"=>1,
				'activated'	=>1,
				'date_added' => date("Y-m-d")
			);
			$user_id=$this->users_model->addUser($usersdata);
			
			$json=array(
				'status'=>true,
				'user_id'=>$user_id,
				'message'=>'User Successfully Registered'
			);
			
		}else{
			$json['status'] 	= false;
		}
		$this->set_response($json, REST_Controller::HTTP_CREATED);
	}

	public function login_post(){
		$json=array();
		if (!$this->validateLoginForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}
		if (!$json) {
			$result=$this->muser->login($this->post('email'), $this->post('password'));
			
			if($result){
				$json=array(
					'status'=>1,
					'id'=>$result['id'],
					'email'=>$result['email'],
					'message'=>"Login Successfully",
				);
			}else{
				$json=array(
					'status'=>0,
					'errors'=>array("Wrong Email and Password"),
				);
			}
			
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
		
	}

	public function profile_post(){
		$json=array();
		
		
		$result=$this->users_model->getUser($this->post('id'));
			
		if($result){
			$json=array(
				'status'=>1,
				'user'=>$result,
				
			);
		}else{
			$json=array(
				'status'=>0,
				'errors'=>'No data'
			);
		}
			
		
		$this->set_response($json, REST_Controller::HTTP_OK);
		
	}

	public function update_post(){

		$json=array();
		$usersdata=array(
			'firstname'	=>$this->post('firstname'),
			'lastname'	=>$this->post('lastname'),
			'phone'	=>$this->post('phone'),
			
		);
		$this->users_model->updateUser($this->post('id'),$usersdata);
		
		$json=array(
			'status'=>true,
			'message'=>'User Successfully Registered'
		);
		
		
		$this->set_response($json, REST_Controller::HTTP_CREATED);
	}

	public function formdelete_post(){

		$json=array();
		
		$this->users_model->deleteForm($this->post('id'));
		
		$json=array(
			'status'=>true,
			'message'=>'Form Successfully deleted'
		);
		
		
		$this->set_response($json, REST_Controller::HTTP_CREATED);
	}

	public function saveform_post(){
		$json=array();
		$formdata=$this->post('formdata');

		
		$form=array(
			'user_id'	=>$this->post('id'),
			'section0'	=>isset($formdata['section0'])?json_encode($formdata['section0']):'',
			'section1'	=>isset($formdata['section1'])?json_encode($formdata['section1']):'',
			'section2'	=>isset($formdata['section2'])?json_encode($formdata['section2']):'',
			'section3'	=>isset($formdata['section3'])?json_encode($formdata['section3']):'',
			'section4'	=>isset($formdata['section4'])?json_encode($formdata['section4']):'',
			'section5'	=>isset($formdata['section5'])?json_encode($formdata['section5']):'',
			'section6'	=>isset($formdata['section6'])?json_encode($formdata['section6']):'',
			'section7'	=>isset($formdata['section7'])?json_encode($formdata['section7']):'',
			'date_added'=>date("Y-m-d"),
			'status'=>1
		);
		if($this->post('formid')){
			$this->users_model->updateForm($this->post('formid'),$form);
		}else{
			$this->users_model->addForm($form);
		}
		
		
		$json=array(
			'status'=>true,
			'message'=>'Form Successfully Added'
		);
		
		
		$this->set_response($json, REST_Controller::HTTP_CREATED);
	}


	protected function validateLoginForm(){
		
		$rules=array(
			'email' => array(
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => 'trim|required|valid_email'
			),
			'password' => array(
				'field' => 'password', 
				'label' => 'Password', 
				'rules' => 'trim|required'
			),
		);
		$this->form_validation->set_data($this->post());
		
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run($this) == TRUE){
			return true;
    	}
		else
		{
			$this->error['warning']="Warning: Please check the form carefully for errors! ";
			$this->error['errors'] = $this->form_validation->error_array();
			return false;
    	}
		return !$this->error;
   }

	protected function validateRegisterForm() {
		$rules=array(
			
			'password' => array(
				'field' => 'password', 
				'label' => 'Password', 
				'rules' => 'trim|required'
			),
			
			'email' => array(
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => 'trim|required|valid_email|is_unique[user.email]'
			),
		);
		$this->form_validation->set_data($this->post());
		
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run($this) == TRUE){
			return true;
    	}
		else
		{
			$this->error['warning']="Warning: Please check the form carefully for errors! ";
			$this->error['errors'] = $this->form_validation->error_array();
			return false;
    	}
		return !$this->error;
	}

	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */