<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Niranjan Sahoo
 * @copyright   Copyright (c) 2014
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Settings 
{
   const TABLE = 'config';
	public $CI;
	
   function __construct()
   {
		$this->CI =& get_instance();
      $this->get_all();
   }
	
	function __set($key, $value)
   {
      $this->CI->config->set_item($key, $value);
   }

   function __get($key)
   {
      return $this->CI->config->item($key);
   }

   function get_all()
   {
      $Settings = $this->CI->db->get(self::TABLE);
		foreach ($Settings->result() as $Setting)
      {
			if (!$Setting->serialized) {
				$this->CI->config->set_item($Setting->key, $Setting->value);
			}else{
				$this->CI->config->set_item($Setting->key, json_decode($Setting->value, true));
			}
			
		}
		$Settings->free_result();
   }
}
