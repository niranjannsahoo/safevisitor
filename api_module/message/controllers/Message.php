<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends REST_Controller {
	private $error = array();
	
	function __construct(){
		parent::__construct();
		$this->load->model('message_model');
		$this->load->model('users/users_model');
	}
	
	public function index_get(){
		$json=array(
				'status'=>false,
				'message'=>'No Message Found'
			);
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}

	public function otp_post(){
		$json=array();
		$otp = rand(1000, 9999);
		if (!$this->validateOTPForm()) {
			if(isset($this->error['warning'])){
				$json['warning'] 	= $this->error['warning'];
			}
			if(isset($this->error['errors'])){
				$json['message'] 	= $this->error['errors'];
			}
			$json['error'] 	= true;
		}
		if (!$json) {
				
			//$result=$this->sendOtp($otp,$this->post('mobile'));
			$result=array();
		
			if(isset($result['error_status'])){
				$json=array(
					'error'=>true,
					'message'=>"Enter valid Mobile Number",
				);
				
			}else{
				$user=$this->users_model->getUserByPhone($this->post('mobile'));
				
				$json=array(
					'error'=>false,
					'message'=>"SMS request is initiated! You will be receiving it shortly.",
					'otp'=>$otp,
					'user'=>$user
				);
			}
		}
		$this->set_response($json, REST_Controller::HTTP_OK);
	}
	
	protected function sendOtp($otp, $mobile){
		$sms_content = "Welcome to SafeVisitor : Your verification code is $otp";
		 
		//Encoding the text in url format
		$sms_text = urlencode($sms_content);
		 
		//This is the Actual API URL concatnated with required values 
		$api_url = 'http://enterprise.cloudsvas.com/SendSms?user_ID='. SMSUSER .'&user_Pwd='. PASSWORD .'&sender_ID='.SENDERID.'&MOB_NO='.$mobile.'&msg='.$sms_text.'&sms_Type=trans&param=eng';
		
		 //Envoking the API url and getting the response 
		$response = file_get_contents( $api_url);
		 
		 //Returning the response 
		 return json_decode($response,true);
	}
	
	
	protected function validateOTPForm(){
		
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
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */