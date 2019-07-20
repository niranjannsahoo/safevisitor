<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Member_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addMember($data) {
      $userdata=array(
			"user_group_id"=>$data['user_group_id'],
			"firstname"=>$data['firstname'],
			"phone"=>$data['phone'],
			"address"=>$data['address'],
			"username"=>$data['username'],
			"password"=>md5($data['password']),
			"show_password"=>$data['password'],
			"email"=>$data['email'],
			"enabled"=>$data['enabled'],
			"date_added"=>date("Y-m-d"),
		);
      $this->db->insert("user", $userdata);
      $user_id=$this->db->insert_id() ;
		
		$memberdata=array(
			"user_id"=>$user_id,
			"company"=>$data['company'],
			"business_id"=>$data['business_id'],
		);
		$this->db->insert("member", $memberdata);
		
		return $user_id;
	}
	
	public function editMember($user_id, $data) {
		$userdata=array(
			"user_group_id"=>$data['user_group_id'],
			"firstname"=>$data['firstname'],
			"phone"=>$data['phone'],
			"address"=>$data['address'],
			"username"=>$data['username'],
			"email"=>$data['email'],
			"password"=>md5($data['password']),
			"show_password"=>$data['password'],
			"enabled"=>$data['enabled'],
			"date_modified"=>date("Y-m-d"),
		);
		$this->db->where("user_id",$user_id);
      $this->db->update("user", $userdata);
      
		$memberdata=array(
			"user_id"=>$user_id,
			"company"=>$data['company'],
			"business_id"=>isset($data['business_id'])?$data['business_id']:0,
		);
		$this->db->where("user_id",$user_id);
		$this->db->update("member", $memberdata);
	}
	
	public function getMembers($data = array()){
		$this->db->select("*,ug.name as group");
		$this->db->from("user u");
		$this->db->join('user_group ug', 'ug.user_group_id = u.user_group_id');
		$this->db->where("u.user_group_id!=1");
		
		if (!empty($data['filter_group_id'])) {
			$this->db->where("u.user_group_id",$data['filter_group_id']);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("
				(concat_ws(' ', u.firstname, u.lastname) LIKE '%{$data['filter_search']}%'
				OR u.email LIKE '%{$data['filter_search']}%'
				OR u.username LIKE '%{$data['filter_search']}%'
				OR ug.name LIKE '%{$data['filter_search']}%')"				
			);
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "u.firstname";
		}

		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$order = "desc";
		} else {
			$order = "asc";
		}
		$this->db->order_by($sort, $order); 
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$this->db->limit((int)$data['limit'],(int)$data['start']);
		}

		$res = $this->db->get()->result();

		return $res;
	}
	
	public function getTotalMember($data = array()) {
		$this->db->from("user u");
		$this->db->join('user_group ug', 'ug.user_group_id = u.user_group_id');
		$this->db->where("u.user_group_id!=1");
		if (!empty($data['filter_group_id'])) {
			$this->db->where("u.user_group_id",$data['filter_group_id']);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("
				(concat_ws(' ', u.firstname, u.lastname) LIKE '%{$data['filter_search']}%'
				OR u.email LIKE '%{$data['filter_search']}%'
				OR u.username LIKE '%{$data['filter_search']}%'
				OR ug.name LIKE '%{$data['filter_search']}%')"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;
		
	}
	
	public function getMember($user_id){
		$this->db->select("*,b.name as business_name");
		$this->db->from("user u");
		$this->db->join('member m', 'u.user_id = m.user_id');
		$this->db->join('business_type b', 'm.business_id = b.business_id','left');
		$this->db->where("u.user_id",$user_id);
		$res = $this->db->get()->row();
		return $res;	
		
	}
	
	public function getMemberByEmail($email) {
		$this->db->where('email',$email);
		$query = $this->db->get('user');
		$Member=$query->row();
		
		return $Member;
	}
	
	public function getMemberByMembername($username) {
		$this->db->where('username', $username);
		$query = $this->db->get('user');
		$Member=$query->row();
		return $Member;
	}
	
	public function deleteMember($user_id){
		$this->db->where_in("user_id", $user_id);
		$this->db->delete("user");
		
		$this->db->where_in("user_id", $user_id);
		$this->db->delete("member");
	}
	
	public function getMemberGroups(){
		$this->db->select("*");
		$this->db->from("user_group");
		$this->db->where_in("user_group_id", array(3,4));
		$res = $this->db->get()->result();
		return $res;
	}
	
	public function getBusinessTypes(){
		$this->db->from("business_type");
		$res = $this->db->get()->result();
		return $res;	
	}
	
}
