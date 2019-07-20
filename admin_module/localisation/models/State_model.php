<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class State_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addState($data) {
      $Statedata=array(
			"country_id"=>$data['country_id'],
			"name"=>$data['name'],
			"code"=>$data['code'],
			"status"=>$data['status']
		);
		
		$this->db->insert("state",$Statedata);
		return $this->db->insert_id() ;
	}
	
	public function editState($id, $data) {
		$Statedata=array(
			"country_id"=>$data['country_id'],
			"name"=>$data['name'],
			"code"=>$data['code'],
			"status"=>$data['status']
		);
		$this->db->where("id",$id)
					->update("State", $Statedata);
		
	}
	
	public function getStates($data = array()){
		$this->db->select("*,c.name as country");
		$this->db->from("state s");
		$this->db->join('country c', 's.country_id = c.country_id');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				s.name LIKE '%{$data['filter_search']}%'
				OR c.name LIKE '%{$data['filter_search']}%'
				OR s.code LIKE '%{$data['filter_search']}%'"				
			);
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "s.name";
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
	
	public function getTotalState($data = array()) {
		$this->db->select("*,c.name as country");
		$this->db->from("state s");
		$this->db->join('country c', 's.country_id = c.country_id');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				s.name LIKE '%{$data['filter_search']}%'
				OR c.name LIKE '%{$data['filter_search']}%'
				OR s.code LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;
		
	}
	
	public function getState($id){
		$this->db->where("id",$id);
		$res=$this->db->get('state')->row();
		return $res;
	}
	
	public function deleteState($id){
		$this->db->where_in("id", $id);
		$this->db->delete("state");
	}
	
	public function getStatesByCountryId($country_id) {
		$this->db->from("state");
		$this->db->where("country_id",$country_id);
		$res = $this->db->get()->result();
		return $res;
	}
	
}
