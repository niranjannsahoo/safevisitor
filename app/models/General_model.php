<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class General_Model extends CI_Model
{
	
	function __construct ()
	{
		parent::__construct();
	}

	function count_row($table,$cond = '')
   {
		$this->db->from($table);
		if($cond != '')
        {
            $this->db->where($cond);
        }
		$count = $this->db->count_all_results();
		return $count;
   }
	function select_val($table,$field = '',$cond = '')
	{
		$this->db->select($field);
        $this->db->from($table);
        if($cond != '')
        {
            $this->db->where($cond);
        }
        
        $res = $this->db->get()->result_array();
		if(!empty($res)){
             return $res[0];
        }
        
	}
	function select($table,$field = '',$cond = '')
    {
        $this->db->select($field);
        $this->db->from($table);
        if($cond != '')
        {
            $this->db->where($cond);
        }
        
        $res = $this->db->get()->result_array();
        return $res;
    }
	function selectmax($table,$max = '',$field='',$cond = '')
    {
		$this->db->select($field);
        $this->db->select_max($max);
        $this->db->from($table);
        if($cond != '')
        {
            $this->db->where($cond);
        }
        
        $res = $this->db->get()->result_array();
        if(!empty($res)){
             return $res[0];
        }
    }
	function selectdistinct($table,$field = '',$cond = '')
    {
		$this->db->select($field);
		$this->db->distinct();
       
        $this->db->from($table);
        if($cond != '')
        {
            $this->db->where($cond);
        }
        
        $res = $this->db->get()->result_array();
        return $res;
    }
	function update($table , $update_field , $cond)
   {
        $this->db->where($cond);
        $status=$this->db->update($table, $update_field);
        //error_log($this->db->last_query());
        //$this->db->last_query();
        if($status) 
        return "success";
   }
	function insert($table,$data)
   {
       $this->db->insert($table, $data); 
       return $this->db->insert_id() ;
      //error_log($this->db->last_query());
   }
	function get_jointable($table1,$table2,$field,$cond2,$where='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		
		if($where != '')
		{
			$this->db->where($where);
		}
        $res= $this->db->get()->result_array();
		
			return $res;
		
    }
	function get_join2table($table1,$table2,$field,$cond2,$where='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		
		if($where != '')
		{
			$this->db->where($where);
		}
        $res= $this->db->get()->result_array();
		$count=count($res);
		if($count==1)
		{
			return $res[0];
		}
		else
		{
			return $res;
		}
    }
	function get_join3table($table1,$table2,$table3,$field,$cond2,$cond3,$where='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		$this->db->join($table3,$cond3);
		if($where != '')
		{
			$this->db->where($where);
		}
        return $this->db->get()->result_array();
    }

    function get_val($table , $field , $cond)
    {
        $this->db->select($field);
        $this->db->from($table);
        $this->db->where($cond);
        $res = $this->db->get()->result_array();
        error_log($this->db->last_query());
        if(!empty($res)){
             return $res[0][$field];
        }
        else 
        {
            return 'invalid';
        }
        
    }

    
    
    function delete_row($table,$where)
	 {
		//$tables = array($table);
		$this->db->where($where);
		$status=$this->db->delete($table);
		if($status) 
        return "success";
	 }
	 
	function delete_rows($table,$where,$id)
	{	
		
		$this->db->where_in($where, $id);
		$this->db->delete($table);
	}
	function truncate($table)
	{
		$this->db->truncate($table); 
	}
	function update_row($table,$update_field,$where)
	 {
		//$tables = array($table);
		$this->db->where($where);
		$status=$this->db->update($table, $update_field);
		if($status) 
        return "success";
	 }
	 
	function update_rows($table,$update_field,$where,$id)
	{	
		
		$this->db->where_in($where, $id);
		$status=$this->db->update($table, $update_field);
	}
	function select_rows($table,$field,$cond="",$orderby,$limit="",$offset="",$where="")
	{
		$this->db->select($field);
		$this->db->from($table);
		
		$this->db->order_by($cond,$orderby);
		if($where !='')
		{
			$this->db->where($where);
		}
		if($limit != '')
		{
			$this->db->limit($limit,$offset);
		}
		$res = $this->db->get()->result_array();
		
		return $res;
		
	} 
	function select_ord($table,$field,$cond,$orderby,$where="")
	{
		$this->db->select($field);
		$this->db->from($table);
		
		$this->db->order_by($cond,$orderby);
		if($where != '')
		{
			$this->db->where($where);
		}
		$res = $this->db->get()->result_array();
		
		return $res;
	}
	function select_groupby($table,$field,$groupby,$where="")
	{
		$this->db->select($field);
		$this->db->from($table);
		
		$this->db->group_by($groupby);
		if($where!='')
		{
			$this->db->where($where);
		}
		
		$res = $this->db->get()->result_array();
		
		return $res;
	}
	function selectmult_jointableord($table1,$table2,$field,$cond2,$where,$orderbycond='',$orderby='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		$this->db->where($where);
		if($orderby!="")
		{
			$this->db->order_by($orderbycond,$orderby);
		}
        $res= $this->db->get()->result_array();
		
		return $res;
		
    }
	function selectmult_jointable($table1,$table2,$field,$cond2,$wherecond,$wherein,$orderbycond='',$orderby='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		$this->db->where_in($wherecond,$wherein);
		if($orderby!="")
		{
			$this->db->order_by($orderbycond,$orderby);
		}
        $res= $this->db->get()->result_array();
		
		return $res;
		
    }
	function selectmult_jointablelike($table1,$table2,$field,$cond2,$wherecond,$wherein,$like,$orderbycond='',$orderby='')
    {
        $this->db->select($field);
        $this->db->from($table1);
        $this->db->join($table2,$cond2);
		$this->db->where_in($wherecond,$wherein);
		$this->db->where($like);
		if($orderby!="")
		{
			$this->db->order_by($orderbycond,$orderby);
		}
        $res= $this->db->get()->result_array();
		
		return $res;
		
    }
	
	
	public function getSlug($slug){
		$this->db->where('slug', $slug);
		$query = $this->db->get('slug');
		$Slug=$query->row();
		return $Slug;
	}
	public function addSlug($data) {
      $status=$this->db->insert("slug", $data);
      return $this->db->insert_id() ;
	}
	public function deleteSlug($route){
		$this->db->where("route",$route);
		$this->db->delete("aio_slug");
	}
	
	
}
?>
