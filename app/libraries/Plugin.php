<?php
class Plugin {
	public $CI;
	
	public function __construct(){
		$this->CI =& get_instance(); 
	}

	public function nav_menu($data = array()) {
		$this->CI->db->from("menu_group");
		$this->CI->db->join("menu","menu_group.id=menu.menu_group_id");
		$this->CI->db->where("menu_group.theme_location",$data['theme_location']);
		$this->CI->db->order_by("menu.sort_order","asc");
		$menu = $this->CI->db->get()->result();
		if(empty($menu) && $data['menu_type']=="admin")
		{
			$menu=array();
		}
		$arg=$data;
		$menus=$this->get_menu_nested($menu);
		return $this->create_nav($menus,$arg);
	}
	
	public function get_menu_nested($treelist,$parent = 0){
		$array = array();
		foreach($treelist as $tree)
		{
			if($tree->parent_id == $parent)
			{
				$tree->sub = isset($tree->sub) ? $tree->sub : $this->get_menu_nested($treelist, $tree->id);
				$array[] = $tree;
			}
		}
		return $array;
	}
	
	public function create_nav($nav,$arg, $depth = 1){
		if($arg['theme_location']=="admin"){
			$url='admin_url';
		}else{
			$url='base_url';
		}
      if(isset($arg['menu_class']) && $depth == 1){
			$list_item = '<ul class="'.$arg['menu_class'].'">';
		}else{
			$list_item = '<ul>';
		}
      foreach($nav as $item){
         $item->url = trim($item->url, '/');
         $list_item .= '<li'. ((isset($item->menu_id)) ? ' id="' . $item->menu_id . '"' : '') . '>';
         $list_item .= '<a href="' .  $url($item->url) . '"' . (($depth == 1) ? ' class="top"' : '') . '>' . $item->title . '</a>';
			if ( ! empty($item->sub)){
				$list_item .= $this->create_nav($item->sub,$arg, $depth + 1);
			}
         $list_item .= '</li>';
      }
      $list_item .= '</ul>';
      return $list_item;
	} 

	public function list_menu_nav($list, $depth = 1){
      $nav = '<ul class="dd-list">';
  
      foreach($list as $Item){
         $nav .= '<li class="dd-item" data-id="'.$Item->id.'">';
			$nav .=  '<div class="dd-handle dd3-handle"></div>';
			$nav .= 	'<div class="row dd3-content">';
			$nav .=			'<div class="col-md-3 title">'.($Item->title?:"&nbsp;") .'</div>';
			$nav .=			'<div class="col-md-4 url">'.($Item->url?:"&nbsp;") .'</div>';
			$nav .=			'<div class="col-md-3 class">'.($Item->class?:"&nbsp;") .'</div>';
			$nav .=			'<div class="col-md-2">';
			$nav .=				'<div class="btn-group btn-group-xs pull-right">';
			$nav .=					'<a href="#" title="Edit Menu" class="edit-menu btn btn-primary"><i class="fa fa-edit"></i></a>';
			$nav .=					'<a href="#" class="delete-menu btn btn-danger"><i class="fa fa-trash-o"></i></a>';
			$nav .=				'</div>';
			$nav .=			'</div>';
			$nav .=	'</div>';
			
			if ( ! empty($Item->sub)){
				 $nav .= $this->list_menu_nav($Item->sub, $depth + 1);
			}
         $nav .= '</li>';
      }
      $nav .= '</ul>';
      return $nav;
   } 

}