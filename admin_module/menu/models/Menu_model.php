<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Menu_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function addMenuGroup($data){
		
		$this->db->insert("menu_group", $data);
		$menugroupid=$this->db->insert_id() ;
		return $menugroupid;
	}
	
	public function editMenuGroup($id, $data){
		$this->db->where("id",$id);
      $this->db->update("menu_group", $data);
		
	}
	
	public function deleteMenuGroup($id){
		$this->db->where("id",$id);
		$this->db->delete("menu_group");
		return true;
	}
	
	public function getMenuGroups() {
		$query = $this->db->get('menu_group');
		$result=$query->result_array();
		
		return $result;
	}
	
	public function getMenuGroup($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('menu_group')->row_array();
		return $result;
	}
	
	public function getMenuItems($id) {
		$this->db->from("menu");
		$this->db->where("menu_group_id",$id);
		$this->db->order_by("sort_order", "ASC");
		$menu_item_data = $this->db->get()->result();
		return $menu_item_data;
	}
	
	public function getMenuItem($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('menu')->row_array();
		return $result;
	}
	
	public function addMenuItem($data){
		$this->db->insert("menu", $data);
		return $this->db->insert_id() ;
	}
	public function editMenuItem($id, $data){
		$this->db->where("id",$id);
      $this->db->update("menu", $data);
	}
	
	public function deleteMenuItem($id){
		$this->db->where('id', $id);
		$this->db->delete('menu'); 
		return true;
	}
	
	public function updateMenuItemsOrder($id,$data){
		$this->db->where("id",$id);
		$this->db->update("menu", $data);
	}
	
	public function getMaxSortorder($id){
		$this->db->where("menu_group_id",$id);
		$this->db->select_max('sort_order');
		$result=$this->db->get("menu")->row();
		return $result->sort_order ;
	}
	
}
