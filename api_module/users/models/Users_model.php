<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Users_model extends CI_Model
{	
   	public function __construct(){
		parent::__construct();
	}
	
	public function addUser($data) {
      	$this->db->insert("user", $data);
      	$user_id=$this->db->insert_id() ;
		return $user_id;
	}

	public function getUser($id) {
      	$this->db->where("id",$id);
		$res=$this->db->get('user')->row();
		return $res;
	}
	
	public function getUserByPhone($phone) {
      $this->db->where("phone",$phone);
		$res=$this->db->get('user')->row();
		return (object)$res;
	}

	public function updateUser($id, $data) {
		$this->db->where("id",$id);
      $status=$this->db->update("user", $data); 
      if($status) {
			return "success";
		}
	}

	public function addForm($data) {
      	$this->db->insert("forms", $data);
      	$form_id=$this->db->insert_id() ;
		return $form_id;
	}

	public function updateForm($formid,$data) {
      	$this->db->where("id",$formid);
      	$status=$this->db->update("forms", $data); 
      	if($status) {
			return "success";
		}
	}

	public function deleteForm($formid) {
      	$this->db->where("id", $formid);
		$this->db->delete("forms");
	}
	
	public function getForms($id) {
      	$this->db->where("user_id",$id);
      	$res = $this->db->get('forms')->result();
      	return $res;
	}

	public function getForm($formid) {
      	$this->db->where("id",$formid);
      	$res = $this->db->get('forms')->row();
      	return $res;
	}
}
