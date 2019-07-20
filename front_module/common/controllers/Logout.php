<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends User_Controller {
	function __construct(){
      parent::__construct();
	}
	public function index(){
		$this->user->logout();
		//$this->common_model->destroy_remember_me();
      redirect('/');
	}
	
}
