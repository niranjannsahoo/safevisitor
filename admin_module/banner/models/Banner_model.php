<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Banner_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function getBanners($data = array()){
		$this->db->select("*");
		$this->db->from("banners");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("title LIKE '%{$data['filter_search']}%'");
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
	
	public function getTotalBanners($data = array()) {
		$this->db->from("banners");

		if (!empty($data['filter_search'])) {
			$this->db->where("
				title LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	
	public function getBanner($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('banners')->row();
		return $result;
	}
	
	public function getBannerImages($id) {
		
		$this->db->from("banner_images");
		$this->db->where("banner_id",$id);
		$this->db->order_by("sort_order", "asc");
		$banner_image_data = $this->db->get()->result();
		return $banner_image_data;
	}
	
	public function editBanner($id, $data) {
		
		$bannerdata=array(
			"title"=>$data['title'],
			"status"=>$data['status']
		);
		$this->db->where("id",$id);
      $this->db->update("banners", $bannerdata);
		
		$this->db->where("banner_id",$id);
		$this->db->delete("banner_images");
		
      if (isset($data['banner_image'])) {
			$sort_order=1;
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data=array(
					"banner_id"=>$id,
					"image"=>$banner_image['image'],
					"title"=>$banner_image['title'],
					"link"=>$banner_image['link'],
					"description"=>$banner_image['description'],
					"sort_order"=>$sort_order
				);
				$this->db->insert("banner_images", $banner_image_data);
				$sort_order++;
			}
		}	
       
      return "success";
	}
	
	public function addBanner($data) {
      $bannerdata=array(
			"title"=>$data['title'],
			"status"=>$data['status']
		);
      $this->db->insert("banners", $bannerdata);
      $id=$this->db->insert_id() ;
		
		if (isset($data['banner_image'])) {
			$sort_order=1;
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data=array(
					"banner_id"=>$id,
					"image"=>$banner_image['image'],
					"title"=>$banner_image['title'],
					"link"=>$banner_image['link'],
					"description"=>$banner_image['description'],
					"sort_order"=>$sort_order
				);
				$this->db->insert("banner_images", $banner_image_data);
				$sort_order++;
			}
		}
	}
	
	public function deleteBanner($selected){
		$this->db->where_in("id", $selected)
					->delete("banners");
		
		$this->db->where_in("banner_id", $selected)
					->delete("banner_images");
		
	}
}
