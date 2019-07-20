<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class City_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addCity($data) {
      $citydata=array(
			"name"=>$data['name'],
			"state_id"=>$data['state_id'],
			"country_id"=>$data['country_id'],
		);
		
		$this->db->insert("city",$citydata);
		return $this->db->insert_id() ;
	}
	
	public function editCity($city_id, $data) {
		$citydata=array(
			"name"=>$data['name'],
			"state_id"=>$data['state_id'],
			"country_id"=>$data['country_id'],
		);
		$this->db->where("city_id",$city_id)
					->update("city", $citydata);
		
	}
	public function getCities($data = array()){
		$this->db->select("ct.*,s.name as state,c.name as country");
		$this->db->from("city ct");
		$this->db->join('state s', 'ct.state_id = s.state_id');
		$this->db->join('country c', 'ct.country_id = c.country_id');
		
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				ct.name LIKE '%{$data['filter_search']}%'
				OR s.name LIKE '%{$data['filter_search']}%'
				OR c.name LIKE '%{$data['filter_search']}%'"				
			);
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "ct.name";
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
	public function getTotalCity($data = array()) {
		$this->db->select("*");
		$this->db->from("city ct");
		$this->db->join('state s', 'ct.state_id = s.state_id');
		$this->db->join('country c', 'ct.country_id = c.country_id');
		
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				ct.name LIKE '%{$data['filter_search']}%'
				OR s.name LIKE '%{$data['filter_search']}%'
				OR c.name LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;
		
	}
	
	public function getCity($city_id){
		$this->db->where("city_id",$city_id);
		$res=$this->db->get('city')->row();
		return $res;
	}
	
	
	public function deleteCity($city_id)
	{
		$this->db->where_in("city_id", $city_id);
		$this->db->delete("city");
	}
	
}
