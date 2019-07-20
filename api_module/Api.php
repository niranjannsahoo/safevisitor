<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

	/**
 * This is an example of a few basic game interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
	private $error = array();
	
	public function __construct(){
	   parent::__construct();
		$this->load->model('api_model');
	}

	public function index_get(){
		$this->load->view('welcome_message');
	}
	
	public function users_get(){
		$json=array();
		$users = $this->api_model->getUsers();
		
		if($users){
			foreach($users as $key=>$user){
				if ($user['image'] && is_file(DIR_UPLOAD . $user['image'])) {
					$users[$key]['image'] = resize($user['image'], 100, 100);
				} else {
					$users[$key]['image'] = resize('no_image.png', 100, 100);
				}
			}
			
			$json=array(
				'status'=>true,
				'users'=>$users,
				'message'=>'All Users information'
			);
		}else{
			$json=array(
				'status'=>false,
				'users'=>'',
				'message'=>'No Users Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function user_post(){
		$json=array();
		if (!$this->post('id')){
			$json=array(
				'status'=>false,
				'message'=>'No ID was provided.'
			);
		}else{
			$user = $this->api_model->getUser($this->post('id'));
			if($user){
				if ($user['image'] && is_file(DIR_UPLOAD . $user['image'])) {
					$user['image']= resize($user['image'], 100, 100);
				} else {
					$user['image']= resize('no_image.png', 100, 100);
				}
				$json=array(
					'status'=>true,
					'user'=>$user,
					'message'=>'User Found'
				);
				
			}else{
				$json=array(
					'status'=>false,
					'message'=>'No User found'
				);
			}
		}

		$this->set_response($json,REST_Controller::HTTP_OK);
	}
	
	public function userupdate_post(){
		$json=array();
		/*if(!$this->validateUserForm()){
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}*/
		if (!$json) {
			$user_id=$this->post('user_id');
			if($_FILES && $user_id){
				$config = array(
					'upload_path' => DIR_UPLOAD.'images',
					'allowed_types' => "gif|jpg|png|jpeg",
					'overwrite' => TRUE,
					'max_size' => "10000000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'file_name' => "user-".$user_id.time()
				); 
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('image')) {
					$json['errors'] 	= $this->upload->display_errors();
				}else { 
					$data = array('upload_data' => $this->upload->data()); 
					$image	= 'images/'.$data['upload_data']['file_name'];
				} 
			}
		}
		if(!$json){
			$usersdata=array(
				'firstname'	=>$this->post('firstname'),
				'lastname'=>$this->post('lastname'),
				'email'=>$this->post('email'),
				'phone'=>$this->post('phone'),
				'date_modified' => date('Y-m-d H:i:s')
			);
			
			if(isset($image)){
				$usersdata['image']=$image;
			}
			$user=$this->api_model->updateUser($user_id,$usersdata);
			
			$defaultdept=$this->api_model->getDeptById($this->settings->config_safeact_department);
			$tophod=$this->settings->config_safeact_member;
			//array_push($tophod,$defaultdept['user_id']);
			$tophod=array_merge($tophod,explode(',',$defaultdept['user_id']));
			
			
			if(in_array($user['user_id'],$tophod)){
				$user['tophod']=true;
			}else{
				$user['tophod']=false;
			}
			
			
			
			
			
			if ($user['image'] && is_file(DIR_UPLOAD . $user['image'])) {
				$user['image']= resize($user['image'], 100, 100);
			} else {
				$user['image']= resize('no_image.png', 100, 100);
			}
			$json=array(
				'error'=>false,
				'user'=>$user,
				'message'=>'Profile Updated successfully'
			);
			
		}else{
			$json=array(
				'error'=>true,
				'message'=>$json['errors'] 
			);
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
			$result=$this->api_model->login($this->post('username'),$this->post('password'));
			
			if(!$result['error']){
				
				$user=$this->api_model->getUser($result['user_id']);
				$defaultdept=$this->api_model->getDeptById($this->settings->config_safeact_department);
				$tophod=$this->settings->config_safeact_member;
				//array_push($tophod,$defaultdept['user_id']);
				$tophod=array_merge($tophod,explode(',',$defaultdept['user_id']));
			
				
				//printr($user);
				if ($user['image'] && is_file(DIR_UPLOAD . $user['image'])) {
					$user['image']= resize($user['image'], 100, 100);
				} else {
					$user['image']= resize('no_image.png', 100, 100);
				}
				
				if(in_array($user['user_id'],$tophod)){
					$user['tophod']=true;
				}else{
					$user['tophod']=false;
				}
				
				//printr($user);
				//exit;
				
				$tokendata=array(
					"device_token"=>$this->post('token')
				);
				
				$this->api_model->updateToken($result['user_id'],$tokendata);
				
				$json=array(
					'error'=>false,
					'user'=>$user,
					'message'=>$result['message'],
				);
			}else{
				$json=array(
					'error'=>true,
					'message'=>$result['message'],
				);
			}
			
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
		
	}
	
	public function guestlogin_post(){
		$json=array();
		$otp = rand(1000, 9999);
		if (!$this->validateGuestLoginForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['message'] 	= $this->error['errors'];
			}
			$json['error'] 	= true;
		}
		if (!$json) {
			//$user_id=$this->api_model->guestlogin($this->post('mobile'));
			$user_id=false;
			$user=array();
			if($user_id){
				$user=$this->api_model->getUser($user_id);
				//printr($user);
				$user['ROLEDATA']=$this->api_model->getUserRole($user_id);
				$tokendata=array(
					"TOKEN"=>$this->post('token')
				);
				$this->api_model->updateToken($user_id,$tokendata);
				$json=array(
					'error'=>false,
					'user'=>$user,
					'verified' => true,
					'message'=>"Login Successfully",
				);
			}else{
				$result=$this->sendOtp($otp,$this->post('mobile'));
				if(isset($result['error_status'])){
					$json=array(
						'error'=>true,
						'message'=>"Enter valid Mobile Number",
					);
					
				}else{
					$json=array(
						'error'=>false,
						'verified'=>false,
						'message'=>"SMS request is initiated! You will be receiving it shortly.",
						'otp'=>$otp
					);
					
				}
			}
			
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function changepassword_post(){
		$json=array();
		if (!$this->validatePasswordForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}
		if (!$json) {
			$count=$this->api_model->checkPassword($this->post('user_id'),$this->post('cpassword'));
			
			if($count){
				
				$passworddata=array(
					"password"=>md5($this->post('npassword')),
					"show_password"=>$this->post('npassword')
				);
				$this->api_model->updatePassword($this->post('user_id'),$passworddata);
				
				$json=array(
					'error'=>false,
					'message'=>"Password Changed Successfully",
				);
			}else{
				$json=array(
					'error'=>true,
					'message'=>"Current Password Wrong",
				);
			}
			
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
		
	}
	
	public function confirmotp_post(){
		$json=array();
		if (!$this->validateOtpForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}
		if (!$json) {
			$user_id=$this->api_model->guestlogin($this->post('mobile'));
			if($user_id){
				$user=$this->api_model->getUser($user_id);
				//printr($user);
				$user['ROLEDATA']=$this->api_model->getUserRole($user_id);
				$tokendata=array(
					"TOKEN"=>$this->post('token')
				);
				$this->api_model->updateToken($user_id,$tokendata);
				$json=array(
					'error'=>false,
					'user'=>$user,
					'verified' => true,
					'message'=>"Login Successfully",
				);
			}else{
				//add user
				$usersdata=array(
					'USERNAME'	=>$this->post('mobile'),
					'PASSWORD'	=>md5(1234),
					'ENABLED'	=>1,
					'ACTIVE'		=>"Y",
					'CMP_ID'		=>1
				);
				$user_id=$this->api_model->addUser($usersdata);
			
				//add user role
				$roledata=array(
					'ROLE_ID'=>16,
					'USER_ID'=>$user_id,
					'CMP_ID'		=>1,
				);
				$user_role_id=$this->api_model->addUserRole($roledata);
			
				//add guest employee
				$empdata=array(
					'FIRST_NAME'	=>$this->post('mobile'),
					'USER_ID'		=>$user_id,
					'MOBILE'			=>$this->post('mobile'),
					'ACTIVE'			=>"Y",
					'CMP_ID'			=>1,
					'OTP'				=>$this->post('otp'),
					'TOKEN'			=>$this->post('token')
				);
				$emp_id=$this->api_model->addEmp($empdata);
				$user=array();
				if($user_id && $user_role_id && $emp_id){
					$user=$this->api_model->getUser($user_id);
					$user['ROLEDATA']=$this->api_model->getUserRole($user_id);
					
					if($user){
						//$this->api_model->updateToken($this->post('token'),$user['USER_ID']);
						$json=array(
							'error'=>false,
							'user'=>$user,
							'verified'=>true,
							'message'=>"Login Successfully",
						);
					}
				}
			}
			
		}
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function plants_get(){
		$json=array();
		$plants = $this->api_model->getPlants();
		$plant_data=array();
		if($plants){
			foreach($plants as $key=>$plant){
				$plant_data[]=array(
					'PLANT_ID'=>$plant['PLANT_ID'],
					'AREA_CODE'=>$plant['PLANT_CODE'],
					'PLANT_SHORT_NAME'=>$plant['PLANT_SHORT_NAME'],
					'PLANT_NAME'=>$plant['PLANT_NAME'],
					'PLANT_INCHARGE_ID'=>$plant['INCHARGE_ID'],
					'ACTIVE'=>$plant['ACTIVE']
				);
			}
			
			$json=array(
				'status'=>true,
				'plants'=>$plant_data,
				'message'=>'All plants information'
			);
		}else{
			$json=array(
				'status'=>false,
				'plants'=>'',
				'message'=>'No plants Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function areas_get(){
		$json=array();
		$areas = $this->api_model->getAreas();
		$area_data=array();
		if($areas){
			foreach($areas as $key=>$area){
				$area_data[]=array(
					'AREA_ID'=>$area['AREA_ID'],
					'PLANT_ID'=>$area['PLANT_ID'],
					'AREA_CODE'=>$area['AREA_CODE'],
					'AREA_SHORT_NAME'=>$area['AREA_SHORT_NAME'],
					'AREA_NAME'=>$area['AREA_NAME'],
					'AREA_INCHARGE_ID'=>$area['AREA_INCHARGE_ID'],
					'ACTIVE'=>$area['ACTIVE']
				);
			}
			
			$json=array(
				'status'=>true,
				'areas'=>$area_data,
				'message'=>'All areas information'
			);
		}else{
			$json=array(
				'status'=>false,
				'areas'=>'',
				'message'=>'No areas Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function depts_get(){
		$json=array();
		$depts = $this->api_model->getDepts();
		$dept_data=array();
		if($depts){
			foreach($depts as $key=>$dept){
				$dept_data[]=array(
					'DEPT_ID'=>$dept['DEPT_ID'],
					'DEPT_CODE'=>$dept['DEPT_CODE'],
					'DEPT_NAME'=>$dept['DEPT_NAME'],
					'ACTIVE'=>$dept['ACTIVE']
				);
			}
			
			$json=array(
				'status'=>true,
				'depts'=>$dept_data,
				'message'=>'All Department information'
			);
		}else{
			$json=array(
				'status'=>false,
				'depts'=>'',
				'message'=>'No Department Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function formdata_get(){
		
		$json=array();
		$result=array();
		$plants = $this->api_model->getPlants();
		if($plants){
			foreach($plants as $key=>$plant){
				$result['plants'][]=array(
					'plant_id'=>$plant['id'],
					'plant_name'=>$plant['name'],
					'status'=>$plant['status']
				);
			}
		}
		$depts = $this->api_model->getDepts();
		
		if($depts){
			foreach($depts as $key=>$dept){
				$result['departments'][]=array(
					'dept_id'=>$dept['id'],
					'dept_name'=>$dept['name'],
					'staus'=>$dept['status']
				);
			}
		}
		$json=array(
			'error'=>false,
			'result'=>$result,
			'message'=>'Form Data'
		);
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function employees_post(){
		$json=array();
		$employees = $this->api_model->getEmployees();
		$emp_data=array();
		if($employees){
			
			
			$json=array(
				'error'=>false,
				'employees'=>$employees,
				'message'=>'All Employee information'
			);
		}else{
			$json=array(
				'error'=>true,
				'employees'=>'',
				'message'=>'No Employee Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function graphdata_post(){
		
		$json=array();
		$result=array();
		if (!$this->post('user_id')){
			$json=array(
				'error'=>true,
				'message'=>'No data found.'
			);
		}else{
			$result['open_status'] = $this->api_model->getTotalOpenMAT($this->post('user_id'),$this->post('dept_id'),'Open');
			$result['assign_status'] = $this->api_model->getTotalAssignMAT($this->post('user_id'));
			$result['close_status'] = $this->api_model->getTotalCloseMAT($this->post('user_id'),$this->post('dept_id'),'Close');
			$result['final_status'] = $this->api_model->getTotalFinalMAT($this->post('user_id'),$this->post('dept_id'),'Final Close');
			$result['my_status'] = $this->api_model->getTotalMyMAT($this->post('user_id'));
			$result['my_assign_status'] = $this->api_model->getTotalMyAssignMAT($this->post('user_id'));
			$result['mat_hold_status'] = $this->api_model->getTotalMATHold();
			$result['graphurl'] = base_url('api/graph');
			
			$json=array(
				'error'=>false,
				'result'=>$result,
				'message'=>'Graph Data'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function graph_get(){
		$categories=$this->api_model->getGraph();
		$this->load->view('graph');
	}
	
	public function issuelist_post(){
		$json=array();
		$mats = $this->api_model->getMATs($this->post('dept_id'),$this->post('user_id'),$this->post('status'),$this->post('action'));
		$priority=array("0"=>"Low","1"=>"Moderate","2"=>"High");
		$mat_data=array();
		
		if($mats){
			foreach($mats as $key=>$mat){
				$assignmember=array();
				$assigndata=$this->api_model->getAssignMATByMatId($mat['id']);
				foreach($assigndata as $assign){
					$assign_to[]=$assign['assignto'];
				}
				if($assigndata){
					$assignmember=array(
						"assignBy"	=>$assigndata[0]['assignby'],
						"createdDate"=>date("Y-m-d",strtotime($assigndata[0]['date_added'])),
						"assignTo"=>$assign_to
					);
				}
				
				if ($mat['image'] && is_file(DIR_UPLOAD . $mat['image'])) {
					$thumb= resize($mat['image'], 100, 100);
					$image= resize($mat['image'], 500, 500);
				} else {
					$image= $thumb= resize('no_image.png', 100, 100);
				}
				

				$now = time(); // or your date as well
				$your_date = strtotime($mat['date_added']);
				$datediff = $now - $your_date;

				$totalday=round($datediff / (60 * 60 * 24));
				$action=$this->post('action');

				$mat_data[]=array(
					'matId'=>$mat['id'],
					'deptId'=>$mat['dept_id'],
					'deptName'=>$mat['dept_name'],
					'plantId'=>$mat['plant_id'],
					'plantName'=>$mat['plant_name'],
					'observerName'=>$mat['firstname'].' '.$mat['lastname'],
					'description'=>$mat['description'],
					'recommendation'=>$mat['recommendation'],
					'actionTaken'=>$mat['actiontaken'],
					'conditions'=>json_decode($mat['conditions'],true), 
					'createdDate'=>date("Y-m-d",strtotime($mat['date_added'])),
					'status'=>$mat['status'],
					'totalday'=>$totalday,
					'priority'=>$priority[$mat['priority']],
					'active'=>$mat['active'],
					'thumb'=>$thumb,
					'image'=>$image,
					'assignmember'=>$assignmember,
					'action'=>$action
				);
			}
			
			//print_r($mat_data);
			
			$json=array(
				'status'=>true,
				'result'=>$mat_data,
				'message'=>'All Mat information'
			);
		}else{
			$json=array(
				'status'=>false,
				'result'=>'',
				'message'=>'No Mat Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function misreport_post(){
		$json=array();
		$filter_array=array(
			"dept_id"=>$this->post('dept_id'),
			"status"=>$this->post('status'),
			"start_date"=>$this->post('start_date'),
			"end_date"=>$this->post('end_date')
		);
		$mats = $this->api_model->searchMATs($filter_array);
		$priority=array("0"=>"Low","1"=>"Moderate","2"=>"High");
		
		$mat_data=array();
		
		if($mats){
			foreach($mats as $key=>$mat){
				
				if ($mat['image'] && is_file(DIR_UPLOAD . $mat['image'])) {
					$thumb= resize($mat['image'], 100, 100);
					$image= resize($mat['image'], 500, 500);
				} else {
					$image= $thumb= resize('no_image.png', 100, 100);
				}
				
				$now = time(); // or your date as well
				$your_date = strtotime($mat['date_added']);
				$datediff = $now - $your_date;

				$totalday=round($datediff / (60 * 60 * 24));
				$action="Open";

				$mat_data[]=array(
					'matId'=>$mat['id'],
					'deptId'=>$mat['dept_id'],
					'deptName'=>$mat['dept_name'],
					'plantId'=>$mat['plant_id'],
					'plantName'=>$mat['plant_name'],
					'observerName'=>$mat['firstname'].' '.$mat['lastname'],
					'description'=>$mat['description'],
					'recommendation'=>$mat['recommendation'],
					'actionTaken'=>$mat['actiontaken'],
					'conditions'=>json_decode($mat['conditions'],true),
					'totalday'=>$totalday,
					'priority'=>$priority[$mat['priority']],
					'createdDate'=>date("Y-m-d",strtotime($mat['date_added'])),
					'status'=>$mat['status'],
					'active'=>$mat['active'],
					'thumb'=>$thumb,
					'image'=>$image,
					'action'=>$action
				);
			}
			
			//print_r($mat_data);
			
			$json=array(
				'status'=>true,
				'result'=>$mat_data,
				'message'=>'All Mat information'
			);
		}else{
			$json=array(
				'status'=>false,
				'result'=>'',
				'message'=>'No Mat Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function assignlist_post(){
		$json=array();
		$mats = $this->api_model->getAssignMATs($this->post('USER_ID'));
		$mat_data=array();
		
		if($mats){
			foreach($mats as $key=>$mat){
				//$date = DateTime::createFromFormat('Y-m-d h:i:s.u???',$mat['CREATED_ON']);
				//print_r($date->format('Y-m-d'));
				$status=array(
					80=>"Open",
					4=>"Close",
					105=>"Final Close"
				);
				
				$assign_member=$this->api_model->getUser($mat['ASSIGNED_ID']);
				
				$mat_data[]=array(
					'matId'=>$mat['MAT_ID'],
					'matCode'=>$mat['MAT_CODE'],
					'deptId'=>$mat['DEPT_ID'],
					'deptName'=>$mat['DEPT_NAME'],
					'plantId'=>$mat['PLANT_ID'],
					'plantName'=>$mat['PLANT_NAME'],
					'observerName'=>$mat['FIRST_NAME'].' '.$mat['LAST_NAME'],
					'description'=>$mat['DESCRIPTION'],
					'recommendation'=>$mat['RECOMMENDATION'],
					'actionTaken'=>$mat['ACTION_TAKEN'],
					'conditions'=>json_decode($mat['CONDITIONS'],true), 
					'createdDate'=>$mat['START_DATE'],
					'status'=>$status[$mat['STATUS']],
					'active'=>$mat['ACTIVE'],
					'image'=>is_object($mat['BASE64STRING'])?"yes":"no",
					'assignerName'=>$assign_member['FIRST_NAME'].' '.$assign_member['LAST_NAME'],
					'assignDate'=>$mat['ASSIGN_DATE'],
				);
			}
			
			//print_r($mat_data);
			
			$json=array(
				'status'=>true,
				'result'=>$mat_data,
				'message'=>'All Mat information'
			);
		}else{
			$json=array(
				'status'=>false,
				'result'=>'',
				'message'=>'No Mat Found'
			);
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function addissue_post(){
		$json=array();
		if (!$this->validateIssueForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['errors'] 	= $this->error['errors'];
			}
		}
		if (!$json) {
			//add user
			$adddate=date("Y-m-d H:i:s");
			$matdata=array(
				'plant_id'		=> $this->post('plant_id'),
				'dept_id'		=> $this->post('dept_id'),
				'user_id'		=> $this->post('user_id'),
				'description'	=> $this->post('description'),
				'priority'		=> $this->post('priority'),
				'conditions' 	=>$this->post('conditions'),
				'recommendation'=>$this->post('recommendation'),
				'actiontaken'	=>$this->post('actiontaken'),
				'status'			=>"Open",
				'active'			=>1,
				'date_added'	=>$adddate,
				
			);
			$mat_id=$this->api_model->addMAT($matdata);
			
			if($_FILES && $mat_id){
				$config = array(
					'upload_path' => DIR_UPLOAD.'images',
					'allowed_types' => "gif|jpg|png|jpeg",
					'overwrite' => TRUE,
					'max_size' => "10000000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'file_name' => 'mat_'.$mat_id.time()
				); 
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('image')) {
					$json['errors'] 	= $this->upload->display_errors();
				}else { 
					$data = array('upload_data' => $this->upload->data()); 
					$image	= 'images/'.$data['upload_data']['file_name'];
					$imagedata=array(
						'image'=>$image
					);
					
					$this->api_model->updateMAT($mat_id,$imagedata);
				} 
			}
			
			//add mat history
			
			$history_data=array(
				"mat_id"=>$mat_id,
				"user_id"=>$this->post('user_id'),
				"description"=>$this->post('description'),
				"action_completed"=>$this->post('actiontaken'),
				"status"=>"Open",
				'active'		=>1,
				'date_added'	=>$adddate
			);
			if(isset($image)){
				$history_data['image']=$image;
			}
			
			$this->api_model->addObservation($history_data);
			
			if($mat_id ){
				
				//send Notification
				$mdate=date("Y-m-d",strtotime($adddate));
				$mtime=date("H:i:s A",strtotime($adddate));
				$messages="SafeAct - A new MAT generated with No.".$mat_id." on dated ".$mdate." time ".$mtime;
				
				$data=array(
					'title'=>'Issue Report',
					'message'=>$messages,
					'status'=>'open'
				);
				$message="Safety Aarti";
				$targets=$this->api_model->getTokensByDept($this->post('dept_id'));
				
				$tokens=array();
				foreach($targets as $target){
					$tokens[]=$target['device_token'];
				}
				$this->sendNotification($data,$message,$tokens);
				
				
				//send Message
				$mobiles=$this->api_model->getTophodMobile();
				$this->sendMessage($messages,$mobiles);
				
						
				//echo $mat_id;
				$json=array(
					'error'=>false,
					'message'=>"Data Submitted Successfully"
				);
			}else{
				$json=array(
					'error'=>true,
					'message'=>"Server Error"
				);
			}
			
		}
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function notification_get(){
		$data=array(
			'title'=>'Issue Report',
			'message'=>'Safety conditions Issue',
			'status'=>'open',
			'image'=>null
		);
		$message="Safety Open MAT";
		$targets=$this->api_model->getTokens();
		$tokens=array();
		foreach($targets as $target){
			$tokens[]=$target['TOKEN'];
		}
		
		//printr($tokens);
		$this->fcm->sendNotification($data,$message,$tokens);
		$json=array(
			'error'=>false,
			'message'=>"Notification Successfully"
		);
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function assignmat_post(){
		$adddate=date("Y-m-d H:i:s");
		$json=array();
		$mat_id=$this->post('mat_id');
		$matstatus=$this->post('matstatus');
		$observationId=$this->post('observationId');
		$userlist=json_decode($this->post('userlist'),true);
		$assigned_by=$this->post('assigned_by');
		//printr($userlist);
		$assign_data=array();
		if (!$json) {
			foreach($userlist as $user_id){
				$assign_data=array(
					"mat_id"=>$mat_id,
					"observation_history_id"=>$observationId,
					"assign_to"=>$user_id,
					"assign_by"=>$assigned_by,
					"date_added"=>$adddate,
					"assign_after"=>$matstatus
				);
				$this->api_model->addAssign($assign_data);
			}
			
			//$this->api_model->addAssign($assign_data);
			
			//send Notification
			$mdate=date("Y-m-d",strtotime($adddate));
			$mtime=date("H:i:s A",strtotime($adddate));
			$messages="SafeAct - Assign Mat with MAT No.".$mat_id." on dated ".$mdate." time ".$mtime;
			
			$data=array(
				'title'=>'Issue Assign MAT',
				'message'=>$messages,
				'status'=>'open'
			);
			$message="Safety Aarti";
			$targets=$this->api_model->getTokensByIds($userlist);
			
			$tokens=array();
			foreach($targets as $target){
				$tokens[]=$target['device_token'];
			}
			$this->sendNotification($data,$message,$tokens);
			
			
			//send Message
			$mobiles=$this->api_model->getTophodMobile();
			$this->sendMessage($messages,$mobiles);
					
			$json=array(
				'error'=>false,
				'message'=>"MAT Assigned Successfully"
			);
		}else{
			$json=array(
				'error'=>true,
				'message'=>"Server Error"
			);
		}
			
		
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function addobservation_post(){
		
		$mat_id=$this->post('mat_id');
		$description=$this->post('description');
		$actioncompleted=$this->post('actioncompleted');
		$user_id=$this->post('user_id');
		$adddate=date("Y-m-d H:i:s");
		
		$json=array();
		
		if (!$json) {
			//add user
			
			$history_data=array(
				"mat_id"=>$mat_id,
				"user_id"=>$user_id,
				"description"=>$description,
				"action_completed"=>$actioncompleted,
				"status"=>"Close",
				'active'		=>1,
				'date_added'	=>$adddate
			);
			
			$observation_id=$this->api_model->addObservation($history_data);
			
			$this->api_model->updateMATStatus($mat_id);
			
			
			if($_FILES && $observation_id){
				$config = array(
					'upload_path' => DIR_UPLOAD.'images',
					'allowed_types' => "gif|jpg|png|jpeg",
					'overwrite' => TRUE,
					'max_size' => "10000000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'file_name' => 'obser_'.$observation_id.time()
				); 
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('image')) {
					$json['errors'] 	= $this->upload->display_errors();
				}else { 
					$data = array('upload_data' => $this->upload->data()); 
					$image	= 'images/'.$data['upload_data']['file_name'];
					$imagedata=array(
						'image'=>$image
					);
					
					$this->api_model->updateObservation($observation_id,$imagedata);
				} 
			}
			
			
			if($observation_id){
				
				//send Notification
				$mdate=date("Y-m-d",strtotime($adddate));
				$mtime=date("H:i:s A",strtotime($adddate));
				$messages="SafeAct - Add action with MAT No.".$mat_id." on dated ".$mdate." time ".$mtime;
				
				/*$data=array(
					'title'=>'Issue Report',
					'message'=>$messages,
					'status'=>'open'
				);
				$message="Safety Aarti";
				$targets=$this->api_model->getTokensByDept($this->post('dept_id'));
				
				$tokens=array();
				foreach($targets as $target){
					$tokens[]=$target['device_token'];
				}
				$this->sendNotification($data,$message,$tokens);*/
				
				
				//send Message
				$mobiles=$this->api_model->getTophodMobile();
				$this->sendMessage($messages,$mobiles);
				
				//echo $mat_id;
				$json=array(
					'error'=>false,
					'message'=>"Observation Submitted Successfully"
				);
			}else{
				$json=array(
					'error'=>true,
					'message'=>"Server Error"
				);
			}
			
		}
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function observationlist_post(){
		$json=array();
		$observations=$this->api_model->getObservation($this->post('mat_id'));
		$observation_data=array();
		$action=0;
		$assign=0;
		if($observations){
			foreach($observations as $observation){
				
				if ($observation['image'] && is_file(DIR_UPLOAD . $observation['image'])) {
					$thumb= resize($observation['image'], 100, 100);
					$image= resize($observation['image'], 500, 500);
				} else {
					$image= $thumb= resize('no_image.png', 100, 100);
				}
				
				$observation_data[]=array(
					'observationId'=>$observation['id'],
					'observationCode'=>$observation['id'],
					'description'=>$observation['description'],
					'actionCompleted'=>$observation['action_completed'],
					'observerName'=>$observation['firstname'].' '.$observation['lastname'],
					'createdDate'=>date("Y-m-d",strtotime($observation['date_added'])),
					'status'=>$observation['status'],
					'active'=>$observation['active'],
					'thumb'=>$thumb,
					'image'=>$image,
				);
			}
		}
		
		//check departments head
		$actions=$this->post('action');
		$deptheaddata=$this->api_model->getDeptById($this->post('dept_id'));
		$defaultdept=$this->api_model->getDeptById($this->settings->config_safeact_department);
		$assigndata=$this->api_model->getAssignByMatId($this->post('mat_id'),$this->post('user_id'));
		$mat=$this->api_model->getMAT($this->post('mat_id'));
		$userrole=$this->api_model->getUserRole($this->post('user_id'));
		
		$deptheaddata1=explode(',',$deptheaddata['user_id']);
		$defaultdept1=explode(',',$defaultdept['user_id']);
		$tophod1=$this->settings->config_safeact_member;
		
		$tophod=array_merge($deptheaddata1,$defaultdept1,$tophod1);
		
		//echo $this->post('user_id');
		//printr($deptheaddata);
		//printr($defaultdept);
		//printr($userrole);
		switch($actions){
			case "open":
				if(in_array($this->post('user_id'),$tophod) || $userrole['name']=="Super Admin")	{
					$action=1;
					$assign=1;
				}
				break;
			case "close":
				if(in_array($this->post('user_id'),$tophod) || $userrole['name']=="Super Admin")	{
					$action=1;
					$assign=1;
				}
				break;
			case "final":
				if(in_array($this->post('user_id'),$tophod) || $userrole['name']=="Super Admin"){
					$action=0;
					$assign=0;
				}
			break;
			case "assign":
				if($assigndata && $mat['status']=="Open"){
					$action=1;
					$assign=0;
				}
			break;
			default:
				$action=0;
				$assign=0;
		}
		

		
		//print_r($mat_data);
			
		$json=array(
			'status'=>true,
			'result'=>$observation_data,
			'action'=>$action,
			'assign'=>$assign,
			'message'=>'All Observation'
		);
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function blob_post(){
		$json=array();
		$mat_id=$this->post('MAT_ID');
		if($_FILES && $mat_id){
			$filename = $_FILES['IMAGE']['name'];
			$filetype = $_FILES['IMAGE']['type'];
			$base64string = file_get_contents($_FILES['IMAGE']['tmp_name']);
			$matdata=array(
				'FILE_NAME'=>$filename,
				'FILE_TYPE'=>$filetype,
				'BASE64STRING'=>$base64string
			);
			
			//print_r($matdata);
			$result=$this->api_model->updateMAT($mat_id,$matdata);
			
		}
		if($mat_id ){
				//echo $mat_id;
				$json=array(
					'error'=>false,
					'message'=>"Data Submitted Successfully"
				);
			}else{
				$json=array(
					'error'=>true,
					'message'=>"Server Error"
				);
			}
			$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	public function matblob_get(){
		$json=array();
		$mat_id=$this->get('mat_id');
		$files = $this->api_model->getMatBlobImage($mat_id);
		if (is_object($files['BASE64STRING']))
		{
			$image = $files['BASE64STRING']->load();
		}
		Header ("Content-type: image/png");
		echo $image;
		exit;
	}
	
	public function obserblob_get(){
		$json=array();
		$observation_id=$this->get('observation_id');
		$files = $this->api_model->getObsBlobImage($observation_id);
		if (is_object($files['BASE64STRING']))
		{
			$image = $files['BASE64STRING']->load();
		}
		Header ("Content-type: image/png");
		echo $image;
		exit;
	}
	
	protected function sendOtp($otp, $mobile){
		$sms_content = "Welcome to SafeAct : Your verification code is $otp";
		 
		//Encoding the text in url format
		$sms_text = urlencode($sms_content);
		 
		//This is the Actual API URL concatnated with required values 
		$api_url = 'http://enterprise.cloudsvas.com/SendSms?user_ID='. SMSUSER .'&user_Pwd='. PASSWORD .'&sender_ID='.SENDERID.'&MOB_NO='.$mobile.'&msg='.$sms_text.'&sms_Type=trans&param=eng';
		
		 //Envoking the API url and getting the response 
		 $response = file_get_contents( $api_url);
		 
		 //Returning the response 
		 return json_decode($response,true);
	}
	
	protected function sendMessage($message, $mobile){
		 
		//Encoding the text in url format
		$sms_text = urlencode($message);
		 
		//This is the Actual API URL concatnated with required values 
		$api_url = 'http://enterprise.cloudsvas.com/SendSms?user_ID='. SMSUSER .'&user_Pwd='. PASSWORD .'&sender_ID='.SENDERID.'&MOB_NO='.$mobile.'&msg='.$sms_text.'&sms_Type=trans&param=eng';
		
		 //Envoking the API url and getting the response 
		 $response = file_get_contents( $api_url);
		 //printr($response);
		 //Returning the response 
		 return json_decode($response,true);
	}
	
	protected function sendNotification($data,$message, $tokens){
		
		$this->fcm->sendNotification($data,$message,$tokens);
			
	}
	
	protected function validateUserForm() {
		
		$rules=array(
			'firstname' => array(
				'field' => 'firstname', 
				'label' => 'First Name', 
				'rules' => "trim|required|max_length[255]"
			),
			
			'mobile' => array(
				'field' => 'mobile', 
				'label' => 'Mobile Number', 
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
	
	protected function validateIssueForm() {
		
		$rules=array(
			'name' => array(
				'field' => 'plant_id', 
				'label' => 'Plant', 
				'rules' => "trim|required|max_length[255]"
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
	
	protected function validateLoginForm(){
		
		$rules=array(
			'username' => array(
				'field' => 'username', 
				'label' => 'Username', 
				'rules' => 'trim|required'
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
	
	protected function validatePasswordForm(){
		
		$rules=array(
			'npassword' => array(
				'field' => 'npassword', 
				'label' => 'Password', 
				'rules' => 'trim|required'
			),
			'ccpassword' => array(
				'field' => 'ccpassword', 
				'label' => 'Confirm Password', 
				'rules' => 'trim|required|matches[npassword]'
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
	
	protected function validateGuestLoginForm(){
		
		$rules=array(
			'mobile' => array(
				'field' => 'mobile', 
				'label' => 'Mobile', 
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
	
	protected function validateOtpForm() {
		
		$rules=array(
			'mobile' => array(
				'field' => 'mobile', 
				'label' => 'Mobile', 
				'rules' => "trim|required|max_length[10]"
			),
			'otp' => array(
				'field' => 'otp', 
				'label' => 'Otp', 
				'rules' => 'trim|required'
			),
			'token' => array(
				'field' => 'token', 
				'label' => 'Token', 
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
	
}
