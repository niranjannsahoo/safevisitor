<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Column_left extends MX_Controller {

	public function index()
	{
		$data=array();
		$this->lang->load('column_left');
		
		// Menu
		$data['menus'][] = array(
			'id'       => 'menu-dashboard',
			'icon'	  => 'md-home',
			'name'	  => $this->lang->line('text_dashboard'),
			'href'     => admin_url('common/dashboard'),
			'children' => array()
		);
		
		
		// Pages
		$pages = array();
		
		if ($this->user->hasPermission('access', 'pages/pages')) {
			$pages[] = array(
				'name'	  => $this->lang->line('text_list_page'),
				'href'     => admin_url('pages'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'pages/pages')) {
			$pages[] = array(
				'name'	  => $this->lang->line('text_add_page'),
				'href'     => admin_url('pages/add'),
				'children' => array()		
			);	
		}
		
		if ($pages) {
			$data['menus'][] = array(
				'id'       => 'menu-pages',
				'icon'	   => 'md-pages', 
				'name'	   => $this->lang->line('text_page'),
				'href'     => '',
				'children' => $pages
			);
		}
		
		// posts
		$post = array();
		
		if ($this->user->hasPermission('access', 'post/post')) {
			$post[] = array(
				'name'	  => $this->lang->line('text_list_post'),
				'href'     => admin_url('post'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'post/post')) {
			$post[] = array(
				'name'	  => $this->lang->line('text_add_post'),
				'href'     => admin_url('post/add'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'post/category')) {
			$post[] = array(
				'name'	  => $this->lang->line('text_category'),
				'href'     => admin_url('post/category'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'post/comment')) {
			$post[] = array(
				'name'	  => $this->lang->line('text_comment'),
				'href'     => admin_url('post/comment'),
				'children' => array()		
			);	
		}
		
		if ($post) {
			$data['menus'][] = array(
				'id'       => 'menu-post',
				'icon'	  => 'md-account-child', 
				'name'	  => $this->lang->line('text_post'),
				'href'     => '',
				'children' => $post
			);
		}
		
		
		// Visitors
		$visitor = array();
		
		if ($this->user->hasPermission('access', 'visitor/visitor')) {
			$visitor[] = array(
				'name'	  => $this->lang->line('text_list_visitor'),
				'href'     => admin_url('visitor'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'visitor/visitor')) {
			$visitor[] = array(
				'name'	  => $this->lang->line('text_web_checkin'),
				'href'     => admin_url('visitor/checkin'),
				'children' => array()		
			);	
		}
	
		
		if ($visitor) {
			$data['menus'][] = array(
				'id'       => 'menu-user',
				'icon'	   => 'md-account-child', 
				'name'	   => $this->lang->line('text_visitor'),
				'href'     => '',
				'children' => $visitor
			);
		}
		
		
		// Users
		$users = array();
		
		if ($this->user->hasPermission('access', 'users/users')) {
			$users[] = array(
				'name'	  => $this->lang->line('text_users'),
				'href'     => admin_url('users'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'users/user_group')) {
			$users[] = array(
				'name'	  => $this->lang->line('text_user_group'),
				'href'     => admin_url('users/user_group'),
				'children' => array()		
			);	
		}
	
		
		if ($users) {
			$data['menus'][] = array(
				'id'       => 'menu-user',
				'icon'	   => 'md-account-child', 
				'name'	   => $this->lang->line('text_user'),
				'href'     => '',
				'children' => $users
			);
		}
		
		
		
		// System
		$system = array();
		
		if ($this->user->hasPermission('access', 'setting/setting')) {
			$system[] = array(
				'name'	  => $this->lang->line('text_setting'),
				'href'     => admin_url('setting'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'setting/checkinflow')) {
			$system[] = array(
				'name'	  => $this->lang->line('text_checkinflow'),
				'href'     => admin_url('setting/checkinflow'),
				'children' => array()		
			);	
		}
		
		// Localisation
		$localisation = array();
		
		if ($this->user->hasPermission('access', 'localisation/country')) {
			$localisation[] = array(
				'name'	   => $this->lang->line('text_country'),
				'href'     	=> admin_url('localisation/country'),
				'children' 	=> array()		
			);
		}
		
		if ($this->user->hasPermission('access', 'localisation/state')) {
			$localisation[] = array(
				'name'	   => $this->lang->line('text_state'),
				'href'     	=> admin_url('localisation/state'),
				'children' 	=> array()		
			);
		}
		
		if ($this->user->hasPermission('access', 'localisation/city')) {
			$localisation[] = array(
				'name'	  => $this->lang->line('text_city'),
				'href'     => admin_url('localisation/city'),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'localisation/language')) {
			$localisation[] = array(
				'name'	   => $this->lang->line('text_language'),
				'href'     	=> admin_url('localisation/language'),
				'children' 	=> array()		
			);
		}
		
		if ($localisation) {																
			$system[] = array(
				'name'	   => $this->lang->line('text_localisation'),
				'href'     => '',
				'children' => $localisation	
			);
		}
		
		
		if ($this->user->hasPermission('access', 'setting/serverinfo')) {
			$system[] = array(
				'name'	  => $this->lang->line('text_serverinfo'),
				'href'     => admin_url('setting/serverinfo'),
				'children' => array()		
			);	
		}
	
		
		if ($system) {
			$data['menus'][] = array(
				'id'       => 'menu-system',
				'icon'	   => 'md-settings', 
				'name'	   => $this->lang->line('text_system'),
				'href'     => '',
				'children' => $system
			);
		}
		$this->load->view('column_left',$data);
	}
}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
