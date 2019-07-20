<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Users_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addUser($data) {
      $status=$this->db->insert("user", $data);
      return $this->db->insert_id() ;
	}
	
	public function editUser($id, $data) {
		$this->db->where("id",$id);
      $status=$this->db->update("user", $data); 
      if($status) {
			return "success";
		}
	}
	public function getUsers($data = array()){
		$this->db->select("*,ug.name as group");
		$this->db->from("user u");
		$this->db->join('user_group ug', 'ug.user_group_id = u.user_group_id');
		
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
	public function getTotalUsers($data = array()) {
		$this->db->from("user u");
		$this->db->join('user_group ug', 'ug.user_group_id = u.user_group_id');
		
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
	
	public function getUser($id){
		$this->db->where("id",$id);
		$res=$this->db->get('user')->row();
		return $res;
	}
	
	public function getUserByEmail($email) {
		$this->db->where('email',$email);
		$query = $this->db->get('user');
		$User=$query->row();
		
		return $User;
	}
	
	public function getUserByUsername($username) {
		$this->db->where('username', $username);
		$query = $this->db->get('user');
		$User=$query->row();
		return $User;
	}
	
	
	public function deleteUser($id)
	{
		$this->db->where_in("id", $id);
		$this->db->delete("user");
	}
	public function editMember($id, $data) {
		$this->db->where("userid",$id);
        $status=$this->db->update("aio_stephanie_member", $data);
        
        if($status) 
		{
			
			return "success";
		}
	}
	public function delete_members($id)
	{
		$this->db->where_in("userid", $id);
		$this->db->delete("aio_stephanie_member");
	}
	public function getCountry($country_id) {
		$this->db->from("aio_country");
		$this->db->where("country_id",$country_id);
		$res = $this->db->get()->row();
		return $res->name;
	}
	public function getState($state_id) {
		$this->db->from("aio_state");
		$this->db->where("state_id",$state_id);
		$res = $this->db->get()->row();
		if(!empty($res))
		return $res->name;
		else
		return '';
	}
}
