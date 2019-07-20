<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Common_model extends MY_Model{	
   
	public function __construct(){
		parent::__construct();
	}
	
	public function addMember($data) {
		$userdata=array(
			"user_group_id"=>$data['user_group_id'],
			"firstname"=>$data['name'],
			"phone"=>$data['phone'],
			"address"=>$data['address'],
			"username"=>$data['username'],
			"password"=>md5($data['password']),
			"show_password"=>$data['password'],
			"email"=>$data['email'],
			"enabled"=>($data['user_group_id']==3)?0:1,
			"date_added"=>date("Y-m-d"),
		);
      $this->db->insert("user", $userdata);
      $user_id=$this->db->insert_id() ;
		
		$memberdata=array(
			"user_id"=>$user_id,
			"company"=>$data['company'],
			"business_id"=>isset($data['business_id'])?$data['business_id']:'',
		);
		$this->db->insert("member", $memberdata);
		
		return $user_id;
	}
	public function getBusinessTypes(){
		$this->db->from("business_type");
		$res = $this->db->get()->result_array();
		return $res;	
	}
	
}
