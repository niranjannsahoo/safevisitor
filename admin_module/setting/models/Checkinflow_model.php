<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Checkinflow_model extends MY_Model
{	
   public function __construct(){
		 parent::__construct();
	}
	
	public function editCheckin($module, $data) {
		$this->db->where('module',$module);
		$this->db->delete('config');
		
		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($module)) == $module) {
				
				if (!is_array($value)) {
					$this->db->insert('config', array("key"=>$key,"value"=>$value,"module"=>$module)); 
				} else {
					$this->db->insert('config', array("key"=>$key,"value"=>json_encode($value, true),"module"=>$module,"serialized"=>1)); 
				}
			}
		}
			
	}
}
