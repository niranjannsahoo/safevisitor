<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Gallery_model extends MY_Model
{	
	public $table = 'gallery';
	public $primary_key = 'gallery.id';
	public function __construct(){
		parent::__construct();
	}
		
	public function getGallery($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('gallery')->row();
		return $result;
	}
	
	public function addgallery($data) {
		$this->db->empty_table('gallery_images');
		if (isset($data['gallery_image'])) {
			$sort_order=1;
			foreach ($data['gallery_image'] as $gallery_image) {
				$banner_image_data=array(
					"image"=>$gallery_image['image'],
					"name"=>$gallery_image['name'],
					"sort_order"=>$sort_order
				);
				$this->db->insert("gallery_images", $banner_image_data);
				$sort_order++;
			}
		}
	}
	public function getGalleryImages() {		
		$this->db->from("gallery_images");
		$this->db->order_by("sort_order", "ASC");
		$banner_image_data = $this->db->get()->result();
		return $banner_image_data;
	}
}
