<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Setting_model extends MY_Model
{	
   public function __construct(){
		 parent::__construct();
	}
	public function group(){
      $this->db->select("id,name");
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}
		$res = $this->db->get()->result();
        return $res;
	}
	public function getUsers($data = array())
	{
		$this->db->from("users u");
		$this->db->join('groups gp', 'gp.id = u.group_id');
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("gp.type != ".SUPERADMIN);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("(concat_ws(' ', u.first_name, u.last_name) LIKE '%{$data['filter_search']}%' OR u.email LIKE '%{$data['filter_search']}%')");
		}

		if (!empty($data['filter_groupid'])) {
			$this->db->where("u.group_id", $data['filter_groupid']);
		}


		$sort_data = array(
			'u.first_name',
			'u.last_name',
			'u.email',
			'gp.name',
			'u.last_login'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "u.first_name";
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
	public function getTotalUsers($data = array())
	{
		$this->db->from("users u");
		$this->db->join('groups gp', 'gp.id = u.group_id');
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("gp.type != ".SUPERADMIN);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("(concat_ws(' ', u.first_name, u.last_name) LIKE '%{$data['filter_search']}%' OR u.email LIKE '%{$data['filter_search']}%')");
		}

		if (!empty($data['filter_groupid'])) {
			$this->db->where("u.group_id", $data['filter_groupid']);
		}

		$count = $this->db->count_all_results();

		return $count;
	}
	public function getGroups($data = array())
	{
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}

		$sort_data = array(
			'name'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "name";
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
	public function getTotalGroups($data = array())
	{
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}
		$count = $this->db->count_all_results();

		return $count;
	}
	public function getUserGroup($user_group_id) {
		$this->db->distinct();
		$this->db->where("id",$user_group_id);
		$query=$this->db->get('groups')->row();
		
		$user_group = array(
			'name'      	=> $query->name,
			'type'			=> $query->type,
			'permissions'	=> unserialize($query->permissions)
		);

		return $user_group;
	}
	public function groupname_check($name)
	{
		$this->db->where('name', $name);
		$query = $this->db->get('groups');
		$Group=$query->row();
		return $Group;
	}
	public function editUserGroup($user_group_id, $data) {
		$this->db->where("id",$user_group_id);
        $status=$this->db->update("groups", $data);
        
        if($status) 
        return "success";
	}
	public function getCountries($data = array()) {
		$this->db->from("country");
			
		$sort_data = array(
			'country_name',
			'country_code',
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "country_name";
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
	
	public function getCityByStateId($state_id) {
		$this->db->from("city");
		$this->db->where("city_state_id",$state_id);
		$res = $this->db->get()->result();
		return $res;
	}
	public function getPages() {
		$this->db->from("pages");
		$this->db->where("status","published");
		$res = $this->db->get()->result();
		return $res;
	}
	public function getBanners() {
		$this->db->from("banners");
		$this->db->where("status",1);
		$res = $this->db->get()->result();
		return $res;
	}
	public function getSliders() {
		$this->db->from("sliders");
		$this->db->where("status",1);
		$res = $this->db->get()->result();
		return $res;
	}
	public function editSetting($module, $data) {
		$this->db->where('module',$module);
		$this->db->delete('config');
		//printr($data);
		//exit;
		//echo $module;
		foreach ($data as $key => $value) {
			//echo substr($key, 0, strlen($module));
			if (substr($key, 0, strlen($module)) == $module) {
				
				if (!is_array($value)) {
					$this->db->insert('config', array("key"=>$key,"value"=>$value,"module"=>$module)); 
				} else {
					$this->db->insert('config', array("key"=>$key,"value"=>json_encode($value, true),"module"=>$module,"serialized"=>1)); 
				}
			}
		}
		//exit;	
	}
}
