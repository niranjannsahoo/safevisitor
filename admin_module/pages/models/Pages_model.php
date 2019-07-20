<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Pages_model extends MY_Model
{	
   public function __construct(){
		parent::__construct(); 
	}
	
	public function addPage($data) {
		$page_data=array(
			"parent_id"=>$data['parent_id'],
			"slug"=>$data['slug'],
			"title"=>$data['title'],
			"content"=>$data['content'],
			"layout"=>$data['layout'],
			"meta_title"=>$data['meta_title'],
			"meta_description"=>$data['meta_description'],
			"meta_keywords"=>$data['meta_keywords'],
			"feature_image"=>$data['feature_image'],
			"status"=>$data['status'],
			"visibilty"=>$data['visibilty'],
			"sort_order"=>$data['sort_order'],
			"date_added"=>date("Y-m-d")
			
		);
		$this->db->insert("pages", $page_data);
      $id=$this->db->insert_id() ;
		if (isset($data['slug'])) {
			$slugdata=array(
				"slug"=>$this->input->post('slug'),
				"route"=>"pages/index/$id"
			);
			$this->db->insert("slug", $slugdata);
		}
		return $id;
	}
	
	public function editPage($id, $data) {
		$page_data=array(
			"parent_id"=>$data['parent_id'],
			"slug"=>$data['slug'],
			"title"=>$data['title'],
			"content"=>$data['content'],
			"layout"=>$data['layout'],
			"meta_title"=>$data['meta_title'],
			"meta_description"=>$data['meta_description'],
			"meta_keywords"=>$data['meta_keywords'],
			"feature_image"=>$data['feature_image'],
			"status"=>$data['status'],
			"visibilty"=>$data['visibilty'],
			"sort_order"=>$data['sort_order'],
			"date_modified"=>date("Y-m-d")
			
		);
		
		$this->db->where("id",$id);
      $this->db->update("pages", $page_data);
		
		$this->db->where("route","pages/index/$id");
		$this->db->delete("slug");
		
		if ($data['slug']) {
			$slugdata=array(
				"slug"=>$this->input->post('slug'),
				"route"=>"pages/index/$id"
			);
			$this->db->insert("slug", $slugdata);
		}
	}
	
	public function deletePage($selected){
		$this->db->where_in("id",$selected);
		$this->db->delete("pages");
	}
	
	public function getPages($data = array()){
		$this->db->select("*");
		$this->db->from("pages");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				title LIKE '%{$data['filter_search']}%'
				OR slug LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "title";
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
	
	public function getTotalPages($data = array()) {
		$this->db->from("pages");

		if (!empty($data['filter_search'])) {
			$this->db->where("
				title LIKE '%{$data['filter_search']}%'
				OR slug LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	
	public function getPage($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('pages')->row();
		return $result;
	}

	public function getParents($page_id="")
	{
		$this->db->select('id,title,parent_id');
		$this->db->from('pages');
		if($page_id)
		{
			$this->db->where('id !='.$page_id);
		}
		$this->db->order_by("id", "asc");
		$res = $this->db->get()->result();
		return $res;
	}
	
}
