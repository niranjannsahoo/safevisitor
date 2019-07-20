<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Blog_model extends MY_Model
{	
	public $table = 'blog';
	public $primary_key = 'blog.id';
	public function __construct(){
		parent::__construct();
	}
	public function getBlogs($data = array()){
		$this->db->select("*");
		$this->db->from("blog");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("name LIKE '%{$data['filter_search']}%'");
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "readingOrder";
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
	
	public function getTotalBlogs($data = array()) {
		$this->db->from("blog");

		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	
	public function getBlog($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('blog')->row();
		return $result;
	}
	
	public function editBlog($id, $data) {		
		$bannerdata=array(
			"name"=>$data['name'],
			"position"=>$data['position'],
			"image"=>$data['image'],
			"banner"=>$data['banner'],					
			"description"=>$data['description'],
			"featured"=>isset($data['featured'])?$data['featured']:0,
			"date_added" => mdyToYmd($data['date_added']),
			"readingOrder"=>$data['readingOrder']
		);
		$this->db->where("id",$id);
		$this->db->update("blog", $bannerdata);
		$this->db->where('event_id', $id);
		$this->db->delete('event_gallery_images');
		if (isset($data['gallery_image'])) {
			$sort_order=1;
			foreach ($data['gallery_image'] as $gallery_image) {
				$banner_image_data=array(
					"event_id"=>$id,
					"image"=>$gallery_image['image'],
					"name"=>$gallery_image['name'],
					"sort_order"=>$sort_order
				);
				$this->db->insert("event_gallery_images", $banner_image_data);
				$sort_order++;
			}
		}
		return "success";
	}
	
	public function addBlog($data) {
		$bannerdata=array(
			"name"=>$data['name'],
			"position"=>$data['position'],
			"image"=>$data['image'],
			"banner"=>$data['banner'],	
			"description"=>$data['description'],
			"featured"=>isset($data['featured'])?$data['featured']:0,
			"date_added" => mdyToYmd($data['date_added']),
			"readingOrder"=>$data['readingOrder']
		);
		$this->db->insert("blog", $bannerdata);
		$id=$this->db->insert_id() ;
		if (isset($data['gallery_image'])) {
			$sort_order=1;
			foreach ($data['gallery_image'] as $gallery_image) {
				$banner_image_data=array(
					"event_id"=>$id,
					"image"=>$gallery_image['image'],
					"name"=>$gallery_image['name'],
					"sort_order"=>$sort_order
				);
				$this->db->insert("event_gallery_images", $banner_image_data);
				$sort_order++;
			}
		}
	}
	
	public function deleteBlog($selected){
		$this->db->where_in("id", $selected)
					->delete("blog");
		
	}
	public function blogReorder(){		
		$bannerdata=array();
		foreach($_POST['sortdata'] as $key => $value){			
			$bannerdata=array(
				"readingOrder"=>$value
			);
			$this->db->where("id",$key);
			$this->db->update("blog", $bannerdata);
		}
	}
	public function getGalleryImages() {		
		$this->db->from("event_gallery_images");
		$this->db->where("event_id",$this->uri->segment(3));
		$this->db->order_by("sort_order", "ASC");
		$banner_image_data = $this->db->get()->result();
		return $banner_image_data;
	}
}
