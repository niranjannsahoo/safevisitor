<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Pages_model extends CI_Model
{	
   public function __construct(){
		parent::__construct();
	}
	
	public function getPageinfobyId($page_id)
	{
		$this->db->where('id', $page_id);
		$query = $this->db->get('pages');
		$Page=$query->row();
		return $Page;
	}
	public function getPageinfobySlug($slug)
	{
		$this->db->where('slug', $slug);
		$query = $this->db->get('pages');
		$Page=$query->row();
		return $Page;
	}
	
	public function getIdbySlug($slug)
	{
		$this->db->where('slug', $slug);
		$query = $this->db->get('pages');
		$Page=$query->row();
		return $Page->id;
	}
	
}
