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
		
		// Users
		$borrow = array();
		
		if ($this->user->hasPermission('access', 'borrow/borrow')) {
			$borrow[] = array(
				'name'	  => $this->lang->line('text_issue_book'),
				'href'     => admin_url('borrow'),
				'children' => array()		
			);	
		}
		
		if ($borrow) {
			$data['menus'][] = array(
				'id'       => 'menu-borrow',
				'icon'	   => 'md-account-child', 
				'name'	   => $this->lang->line('text_circulation'),
				'href'     => '',
				'children' => $borrow
			);
		}
		
		// Students
		$student = array();
		
		if ($this->user->hasPermission('access', 'student/student')) {
			$student[] = array(
				'name'	  => $this->lang->line('text_student'),
				'href'     => admin_url('student'),
				'children' => array()		
			);	
		}
		
		if ($student) {
			$data['menus'][] = array(
				'id'       => 'menu-student',
				'icon'	   => 'md-account-child', 
				'name'	   => $this->lang->line('text_student'),
				'href'     => '',
				'children' => $student
			);
		}
		
		// Users
		$report = array();
		
		if ($this->user->hasPermission('access', 'report/report')) {
			$report[] = array(
				'name'	  => $this->lang->line('text_fine_report'),
				'href'     => admin_url('report'),
				'children' => array()		
			);	
		}
		/*if ($this->user->hasPermission('access', 'report/notification')) {
			$report[] = array(
				'name'	  => $this->lang->line('text_notification_report'),
				'href'     => admin_url('report/notification'),
				'children' => array()		
			);	
		}
		if ($this->user->hasPermission('access', 'report/delaymember')) {
			$report[] = array(
				'name'	  => $this->lang->line('text_delaymember_report'),
				'href'     => admin_url('report/delaymember'),
				'children' => array()		
			);	
		}
		if ($this->user->hasPermission('access', 'report/inventory')) {
			$report[] = array(
				'name'	  => $this->lang->line('text_inventory_report'),
				'href'     => admin_url('report/inventory'),
				'children' => array()		
			);	
		}*/
		
		
		if ($report) {
			$data['menus'][] = array(
				'id'       => 'menu-report',
				'icon'	   => 'md-account-child', 
				'name'	   => $this->lang->line('text_report'),
				'href'     => '',
				'children' => $report
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
		
		/*if ($this->user->hasPermission('access', 'users/user_group')) {
			$users[] = array(
				'name'	  => $this->lang->line('text_user_group'),
				'href'     => admin_url('users/user_group'),
				'children' => array()		
			);	
		}*/
	
		
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
