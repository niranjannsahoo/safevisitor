<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Country_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addCountry($data) {
      $countrydata=array(
			"name"=>$data['name'],
			"iso_code_2"=>$data['iso_code_2'],
			"status"=>$data['status']
		);
		
		$this->db->insert("country",$countrydata);
		return $this->db->insert_id() ;
	}
	
	public function editCountry($country_id, $data) {
		$countrydata=array(
			"name"=>$data['name'],
			"iso_code_2"=>$data['iso_code_2'],
			"status"=>$data['status']
		);
		$this->db->where("country_id",$country_id)
					->update("country", $countrydata);
		
	}
	public function getCountries($data = array()){
		$this->db->select("*");
		$this->db->from("country");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR iso_code_2 LIKE '%{$data['filter_search']}%'
				OR iso_code_3 LIKE '%{$data['filter_search']}%'"				
			);
		}

		if (isset($data['sort']) && $data['sort']) {
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
	public function getTotalCountry($data = array()) {
		$this->db->from("country");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR iso_code_2 LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;
		
	}
	
	public function getCountry($country_id){
		$this->db->where("country_id",$country_id);
		$res=$this->db->get('country')->row();
		return $res;
	}
	
	
	public function deleteCountry($country_id)
	{
		$this->db->where_in("country_id", $country_id);
		$this->db->delete("country");
	}
	
}
