<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Checkin extends REST_Controller {
	private $error = array();
	
	function __construct(){
		parent::__construct();
		$this->load->model('checkin_model');
	}
	
	public function index_get(){
		$json=array(
				'status'=>false,
				'message'=>'No Checkin Found'
			);
		
		$this->set_response($json, REST_Controller::HTTP_OK);
	}

	public function flow_get(){
		$json=array();
			
		$phoneScreen=array(
			'displaytext'=>$this->settings->checkin_phone_label,
			'otp'=>$this->settings->checkin_phone_otp?'show':'hide',
		);
		
		$addressScreen=array(
			'hosttext'=>$this->settings->checkin_address_host,
			'fields'=>$this->settings->checkin_address_field,
		);
		
		$idScreen=array(
			'idcard'=>$this->settings->checkin_card_info?'show':'hide',
		);
		
		$photoScreen=array(
			'photo'=>$this->settings->checkin_photo_info?'show':'hide',
		);
		
		$notification=array(
			'host'=>$this->settings->checkin_notification_host?'show':'hide',
			'visitor'=>$this->settings->checkin_notification_visitor?'show':'hide',
			'sms'=>$this->settings->checkin_notification_sms,
		);
		
		$json=array(
			'phonescreen'=>$phoneScreen,
			'addressscreen'=>$addressScreen,
			'idscreen'=>$idScreen,
			'photoscreen'=>$photoScreen,
			'notification'=>$notification
		);
		
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