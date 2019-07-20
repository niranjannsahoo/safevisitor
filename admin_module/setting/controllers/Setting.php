<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('setting_model');
		$this->load->model('users/users_model');		
	}
	
	public function index(){
		// Init
      $data = array();
		$data = $this->lang->load('setting');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->template->add_package(array('ckeditor','colorbox'),true);
        
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => base_url('setting')
		);
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateSetting()){
			//printr($this->input->post());
			$this->setting_model->editSetting('config',$this->input->post());
			
			$userdata=array(
				"username"=>$this->input->post('username'),
				"password"=>md5($this->input->post('password')),
				"show_password"=>$this->input->post('password'),
			);
			
			$userid=$this->users_model->editUser(1,$userdata);
			$this->session->set_flashdata('message', 'Settings Saved');
			redirect(current_url());
		}
		
		
		$data['action'] = admin_url('setting');
		$data['cancel'] = admin_url('setting');

				
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			$user_info = $this->users_model->getUser(1);
		}
		
		/*General Tab*/
		if ($this->input->post('config_site_title')){
			$data['config_site_title'] = $this->input->post('config_site_title');
		} else {
			$data['config_site_title'] = $this->settings->config_site_title;
		}
		
		if ($this->input->post('config_site_tagline')){
			$data['config_site_tagline'] = $this->input->post('config_site_tagline');
		} else {
			$data['config_site_tagline'] = $this->settings->config_site_tagline;
		}
		
        if ($this->input->post('config_site_logo')) {
			$data['config_site_logo'] = $this->input->post('config_site_logo');
		} else {
			$data['config_site_logo'] = $this->settings->config_site_logo;
		}
		
		if ($this->input->post('config_site_logo') && is_file(DIR_UPLOAD . $this->input->post('config_site_logo'))) {
			$data['thumb_logo'] = resize($this->input->post('config_site_logo'), 100, 100);
		} elseif ($this->settings->config_site_logo && is_file(DIR_UPLOAD . $this->settings->config_site_logo)) {
			$data['thumb_logo'] = resize($this->settings->config_site_logo, 100, 100);
		} else {
			$data['thumb_logo'] = resize('no_image.png', 100, 100);
		}
        
		if ($this->input->post('config_site_icon')) {
			$data['config_site_icon'] = $this->input->post('config_site_icon');
		} else {
			$data['config_site_icon'] = $this->settings->config_site_icon;
		}

		if ($this->input->post('config_site_icon') && is_file(DIR_UPLOAD . $this->input->post('config_site_icon'))) {
			$data['thumb_icon'] = resize($this->input->post('config_site_icon'), 100, 100);
		} elseif ($this->settings->config_site_icon && is_file(DIR_UPLOAD . $this->settings->config_site_icon)) {
			$data['thumb_icon'] = resize($this->settings->config_site_icon, 100, 100);
		} else {
			$data['thumb_icon'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		
		if ($this->input->post('config_meta_title')) {
			$data['config_meta_title'] = $this->input->post('config_meta_title');
		} else {
			$data['config_meta_title'] = $this->settings->config_meta_title;
		}
		
		if ($this->input->post('config_meta_description')) {
			$data['config_meta_description'] = $this->input->post('config_meta_description');
		} else {
			$data['config_meta_description'] = $this->settings->config_meta_description;
		}
		
		if ($this->input->post('config_meta_keywords')) {
			$data['config_meta_keywords'] = $this->input->post('config_meta_keywords');
		} else {
			$data['config_meta_keywords'] = $this->settings->config_meta_keywords;
		}
		
		/*Site info tab*/
		
		if ($this->input->post('config_site_owner')) {
			$data['config_site_owner'] = $this->input->post('config_site_owner');
		} else {
			$data['config_site_owner'] = $this->settings->config_site_owner;
		}
		
		if ($this->input->post('config_address')) {
			$data['config_address'] = $this->input->post('config_address');
		} else {
			$data['config_address'] = $this->settings->config_address;
		}
		
		$this->load->model('localisation/country_model');
		$data['countries'] = $this->country_model->getCountries();
		
		if ($this->input->post('config_country_id')) {
			$data['config_country_id'] = $this->input->post('config_country_id');
		} else {
			$data['config_country_id'] = $this->settings->config_country_id;
		}
		
		if ($this->input->post('config_state_id')) {
			$data['config_state_id'] = $this->input->post('config_state_id');
		} else {
			$data['config_state_id'] = $this->settings->config_state_id;
		}
		
		if ($this->input->post('config_email')) {
			$data['config_email'] = $this->input->post('config_email');
		} else {
			$data['config_email'] = $this->settings->config_email;
		}
		
		if ($this->input->post('config_telephone')) {
			$data['config_telephone'] = $this->input->post('config_telephone');
		} else {
			$data['config_telephone'] = $this->settings->config_telephone;
		}
		/*account tab*/
		
		if ($this->input->post('username')) {
			$data['username'] = $this->input->post('username');
		} elseif (!empty($user_info)) {
			$data['username'] = $user_info->username;
		} else {
			$data['username'] = '';
		}
		
		if ($this->input->post('password')) {
			$data['password'] = $this->input->post('password');
		} elseif (!empty($user_info)) {
			$data['password'] = $user_info->show_password;
		} else {
			$data['password'] = '';
		}
		
		/*social tab*/
		
		if ($this->input->post('config_facebook')) {
			$data['config_facebook'] = $this->input->post('config_facebook');
		} else {
			$data['config_facebook'] = $this->settings->config_facebook;
		}
		
		if ($this->input->post('config_twitter')) {
			$data['config_twitter'] = $this->input->post('config_twitter');
		} else {
			$data['config_twitter'] = $this->settings->config_twitter;
		}
		
		if ($this->input->post('config_instagram')) {
			$data['config_instagram'] = $this->input->post('config_instagram');
		} else {
			$data['config_instagram'] = $this->settings->config_instagram;
		}
		
		if ($this->input->post('config_linkedin')) {
			$data['config_linkedin'] = $this->input->post('config_linkedin');
		} else {
			$data['config_linkedin'] = $this->settings->config_linkedin;
		}
		
		/*Library tab*/
		
		if ($this->input->post('config_library_fine')) {
			$data['config_library_fine'] = $this->input->post('config_library_fine');
		} else {
			$data['config_library_fine'] = $this->settings->config_library_fine;
		}
		
		if ($this->input->post('config_auto_fine')) {
			$data['config_auto_fine'] = $this->input->post('config_auto_fine');
		} else {
			$data['config_auto_fine'] = $this->settings->config_auto_fine;
		}
		
		if ($this->input->post('config_issue_limit_books')) {
			$data['config_issue_limit_books'] = $this->input->post('config_issue_limit_books');
		} else {
			$data['config_issue_limit_books'] = $this->settings->config_issue_limit_books;
		}
		
		if ($this->input->post('config_issue_limit_days')) {
			$data['config_issue_limit_days'] = $this->input->post('config_issue_limit_days');
		} else {
			$data['config_issue_limit_days'] = $this->settings->config_issue_limit_days;
		}
		
		if ($this->input->post('config_receipt_prefix')) {
			$data['config_receipt_prefix'] = $this->input->post('config_receipt_prefix');
		} else {
			$data['config_receipt_prefix'] = $this->settings->config_receipt_prefix;
		}
		
		if ($this->input->post('config_display_stock')) {
			$data['config_display_stock'] = $this->input->post('config_display_stock');
		} else {
			$data['config_display_stock'] = $this->settings->config_display_stock;
		}
		
		if ($this->input->post('config_stock_warning')) {
			$data['config_stock_warning'] = $this->input->post('config_stock_warning');
		} else {
			$data['config_stock_warning'] = $this->settings->config_stock_warning;
		}
		
		if ($this->input->post('config_display_stock')) {
			$data['config_display_stock'] = $this->input->post('config_display_stock');
		} else {
			$data['config_display_stock'] = $this->settings->config_display_stock;
		}
		
		if ($this->input->post('config_mail_alert')) {
			$data['config_mail_alert'] = $this->input->post('config_mail_alert');
		} else {
			$data['config_mail_alert'] = $this->settings->config_mail_alert;
		}
		
		if ($this->input->post('config_sms_alert')) {
			$data['config_sms_alert'] = $this->input->post('config_sms_alert');
		} else {
			$data['config_sms_alert'] = $this->settings->config_sms_alert;
		}
		
		if ($this->input->post('config_delay_members')) {
			$data['config_delay_members'] = $this->input->post('config_delay_members');
		} else {
			$data['config_delay_members'] = $this->settings->config_delay_members;
		}
		
		/*Apperance tab*/
		
		$data['pages'] = $this->setting_model->getPages();
		
		if ($this->input->post('config_site_homepage')) {
			$data['config_site_homepage'] = $this->input->post('config_site_homepage');
		} else {
			$data['config_site_homepage'] = $this->settings->config_site_homepage;
		}
		
		$data['front_themes'] = $this->template->get_themes();
		//printr($data['front_themes']);
		
		if ($this->input->post('config_front_theme')) {
			$data['config_front_theme'] = $this->input->post('config_front_theme');
		} else {
			$data['config_front_theme'] = $this->settings->config_front_theme;
		}
		
		$front_theme = $this->settings->config_front_theme?$this->settings->config_front_theme:'default';
		
      $data['front_templates'] = $this->template->get_theme_layouts($front_theme);
		
		if ($this->input->post('config_front_template')) {
			$data['config_front_template'] = $this->input->post('config_front_template');
		} else {
			$data['config_front_template'] = $this->settings->config_front_template;
		}
		
		
		if ($this->input->post('config_header_layout')) {
			$data['config_header_layout'] = $this->input->post('config_header_layout');
		} else {
			$data['config_header_layout'] = $this->settings->config_header_layout;
		}
		
		if ($this->input->post('config_header_image')) {
			$data['config_header_image'] = $this->input->post('config_header_image');
		} else {
			$data['config_header_image'] = $this->settings->config_header_image;
		}
		
		if ($this->input->post('config_header_image') && is_file(DIR_UPLOAD . $this->input->post('config_header_image'))) {
			$data['thumb_header_image'] = resize($this->input->post('config_header_image'), 100, 100);
		} elseif ($this->settings->config_header_image && is_file(DIR_UPLOAD . $this->settings->config_header_image)) {
			$data['thumb_header_image'] = resize($this->settings->config_header_image, 100, 100);
		} else {
			$data['thumb_header_image'] = resize('no_image.png', 100, 100);
		}
		
		$data['banners'] = $this->setting_model->getBanners();
		
		if ($this->input->post('config_header_banner')) {
			$data['config_header_banner'] = $this->input->post('config_header_banner');
		} else {
			$data['config_header_banner'] = $this->settings->config_header_banner;
		}
		
		if ($this->input->post('config_background_image')) {
			$data['config_background_image'] = $this->input->post('config_background_image');
		} else {
			$data['config_background_image'] = $this->settings->config_background_image;
		}
		
		if ($this->input->post('background_image') && is_file(DIR_UPLOAD . $this->input->post('background_image'))) {
			$data['thumb_background_image'] = resize($this->input->post('background_image'), 100, 100);
		} elseif ($this->settings->config_background_image && is_file(DIR_UPLOAD . $this->settings->config_background_image)) {
			$data['thumb_background_image'] = resize($this->settings->config_background_image, 100, 100);
		} else {
			$data['thumb_background_image'] = resize('no_image.png', 100, 100);
		}
		
		if ($this->input->post('config_background_position')) {
			$data['config_background_position'] = $this->input->post('config_background_position');
		} else {
			$data['config_background_position'] = $this->settings->config_background_position;
		}
		
		if ($this->input->post('config_background_repeat')) {
			$data['config_background_repeat'] = $this->input->post('config_background_repeat');
		} else {
			$data['config_background_repeat'] = $this->settings->config_background_repeat;
		}
		
		if ($this->input->post('config_background_attachment')) {
			$data['config_background_attachment'] = $this->input->post('config_background_attachment');
		} else {
			$data['config_background_attachment'] = $this->settings->config_background_attachment;
		}
		
		if ($this->input->post('config_background_color')) {
			$data['config_background_color'] = $this->input->post('config_background_color');
		} else {
			$data['config_background_color'] = $this->settings->config_background_color;
		}
		
		if ($this->input->post('config_text_color')) {
			$data['config_text_color'] = $this->input->post('config_text_color');
		} else {
			$data['config_text_color'] = $this->settings->config_text_color;
		}
		
		/*Ftp tab*/
		
		if ($this->input->post('config_ftp_host')) {
			$data['config_ftp_host'] = $this->input->post('config_ftp_host');
		} else {
			$data['config_ftp_host'] = $this->settings->config_ftp_host;
		}
		
		if ($this->input->post('config_ftp_port')) {
			$data['config_ftp_port'] = $this->input->post('config_ftp_port');
		} else {
			$data['config_ftp_port'] = $this->settings->config_ftp_port;
		}
		
		if ($this->input->post('config_ftp_username')) {
			$data['config_ftp_username'] = $this->input->post('config_ftp_username');
		} else {
			$data['config_ftp_username'] = $this->settings->config_ftp_username;
		}
		
		if ($this->input->post('config_ftp_password')) {
			$data['config_ftp_password'] = $this->input->post('config_ftp_password');
		} else {
			$data['config_ftp_password'] = $this->settings->config_ftp_password;
		}
		
		if ($this->input->post('config_ftp_root')) {
			$data['config_ftp_root'] = $this->input->post('config_ftp_root');
		} else {
			$data['config_ftp_root'] = $this->settings->config_ftp_root;
		}
		
		if ($this->input->post('config_ftp_enable')) {
			$data['config_ftp_enable'] = $this->input->post('config_ftp_enable');
		} else {
			$data['config_ftp_enable'] = $this->settings->config_ftp_enable;
		}
		
		/*Mail tab*/
		
		if ($this->input->post('config_mail_protocol')) {
			$data['config_mail_protocol'] = $this->input->post('config_mail_protocol');
		} else {
			$data['config_mail_protocol'] = $this->settings->config_mail_protocol;
		}
		
		if ($this->input->post('config_mail_parameter')) {
			$data['config_mail_parameter'] = $this->input->post('config_mail_parameter');
		} else {
			$data['config_mail_parameter'] = $this->settings->config_mail_parameter;
		}
		
		if ($this->input->post('config_smtp_host')) {
			$data['config_smtp_host'] = $this->input->post('config_smtp_host');
		} else {
			$data['config_smtp_host'] = $this->settings->config_smtp_host;
		}
		
		if ($this->input->post('config_smtp_username')) {
			$data['config_smtp_username'] = $this->input->post('config_smtp_username');
		} else {
			$data['config_smtp_username'] = $this->settings->config_smtp_username;
		}
		
		if ($this->input->post('config_smtp_password')) {
			$data['config_smtp_password'] = $this->input->post('config_smtp_password');
		} else {
			$data['config_smtp_password'] = $this->settings->config_smtp_password;
		}
		
		if ($this->input->post('config_smtp_port')) {
			$data['config_smtp_port'] = $this->input->post('config_smtp_port');
		} else {
			$data['config_smtp_port'] = $this->settings->config_smtp_port;
		}
		
		if ($this->input->post('config_smtp_timeout')) {
			$data['config_smtp_timeout'] = $this->input->post('config_smtp_timeout');
		} else {
			$data['config_smtp_timeout'] = $this->settings->config_smtp_timeout;
		}
		
		/*Server tab*/
		
		if ($this->input->post('config_ssl')) {
			$data['config_ssl'] = $this->input->post('config_ssl');
		} else {
			$data['config_ssl'] = $this->settings->config_ssl;
		}
		
		if ($this->input->post('config_robots')) {
			$data['config_robots'] = $this->input->post('config_robots');
		} else {
			$data['config_robots'] = $this->settings->config_robots;
		}
		//$this->load->helper('date');
		//printr(tz_list());
		$data['timezone']=tz_list();
		//printr($data['timezone']);
		if ($this->input->post('config_time_zone')) {
			$data['config_time_zone'] = $this->input->post('config_time_zone');
		} else {
			$data['config_time_zone'] = $this->settings->config_time_zone;
		}
		
		if ($this->input->post('config_date_format')) {
			$data['config_date_format'] = $this->input->post('config_date_format');
		} else {
			$data['config_date_format'] = $this->settings->config_date_format;
		}
		
		if ($this->input->post('config_date_format_custom')) {
			$data['config_date_format_custom'] = $this->input->post('config_date_format_custom');
		} else {
			$data['config_date_format_custom'] = $this->settings->config_date_format_custom;
		}
		
		if ($this->input->post('config_time_format')) {
			$data['config_time_format'] = $this->input->post('config_time_format');
		} else {
			$data['config_time_format'] = $this->settings->config_time_format;
		}
		
		if ($this->input->post('config_time_format_custom')) {
			$data['config_time_format_custom'] = $this->input->post('config_time_format_custom');
		} else {
			$data['config_time_format_custom'] = $this->settings->config_time_format_custom;
		}
		
		if ($this->input->post('config_pagination_limit_front')) {
			$data['config_pagination_limit_front'] = $this->input->post('config_pagination_limit_front');
		} else {
			$data['config_pagination_limit_front'] = $this->settings->config_pagination_limit_front;
		}
		
		if ($this->input->post('config_pagination_limit_admin')) {
			$data['config_pagination_limit_admin'] = $this->input->post('config_pagination_limit_admin');
		} else {
			$data['config_pagination_limit_admin'] = $this->settings->config_pagination_limit_admin;
		}
		
		if ($this->input->post('config_seo_url')) {
			$data['config_seo_url'] = $this->input->post('config_seo_url');
		} else {
			$data['config_seo_url'] = $this->settings->config_seo_url;
		}
		
		if ($this->input->post('config_max_file_size')) {
			$data['config_max_file_size'] = $this->input->post('config_max_file_size');
		} else {
			$data['config_max_file_size'] = $this->settings->config_max_file_size;
		}
		
		if ($this->input->post('config_file_extensions')) {
			$data['config_file_extensions'] = $this->input->post('config_file_extensions');
		} else {
			$data['config_file_extensions'] = $this->settings->config_file_extensions;
		}
		
		if ($this->input->post('config_file_mimetypes')) {
			$data['config_file_mimetypes'] = $this->input->post('config_file_mimetypes');
		} else {
			$data['config_file_mimetypes'] = $this->settings->config_file_mimetypes;
		}
		
		if ($this->input->post('config_maintenance_mode')) {
			$data['config_maintenance_mode'] = $this->input->post('config_maintenance_mode');
		} else {
			$data['config_maintenance_mode'] = $this->settings->config_maintenance_mode;
		}
		
		if ($this->input->post('config_compression_level')) {
			$data['config_compression_level'] = $this->input->post('config_compression_level');
		} else {
			$data['config_compression_level'] = $this->settings->config_compression_level;
		}
		
		if ($this->input->post('config_encryption_key')) {
			$data['config_encryption_key'] = $this->input->post('config_encryption_key');
		} else {
			$data['config_encryption_key'] = $this->settings->config_encryption_key;
		}
		
		if ($this->input->post('config_display_error')) {
			$data['config_display_error'] = $this->input->post('config_display_error');
		} else {
			$data['config_display_error'] = $this->settings->config_display_error;
		}
		
		if ($this->input->post('config_log_error')) {
			$data['config_log_error'] = $this->input->post('config_log_error');
		} else {
			$data['config_log_error'] = $this->settings->config_log_error;
		}
		
		if ($this->input->post('config_error_log_filename')) {
			$data['config_error_log_filename'] = $this->input->post('config_error_log_filename');
		} else {
			$data['config_error_log_filename'] = $this->settings->config_error_log_filename;
		}
			
      $this->template->view('setting', $data);
	}
	
	public function validateSetting(){
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		
		$rules=array(
			'config_site_title' => array(
				'field' => 'config_site_title', 
				'label' => 'Site Title', 
				'rules' => "trim|required"
			),
			'config_site_tagline' => array(
				'field' => 'config_site_tagline', 
				'label' => 'Site Tagline', 
				'rules' => "trim|required"
			),
			'config_meta_title' => array(
				'field' => 'config_meta_title', 
				'label' => 'Meta Title', 
				'rules' => "trim|required"
			),
			'config_site_owner' => array(
				'field' => 'config_site_owner', 
				'label' => 'Site Owner', 
				'rules' => "trim|required"
			),
			'config_address' => array(
				'field' => 'config_address', 
				'label' => 'Site Address', 
				'rules' => "trim|required"
			),
			'config_country_id' => array(
				'field' => 'config_country_id', 
				'label' => 'Country', 
				'rules' => "trim|required"
			),
			'config_state_id' => array(
				'field' => 'config_state_id', 
				'label' => 'State', 
				'rules' => "trim|required"
			),
			'config_email' => array(
				'field' => 'config_email', 
				'label' => 'Email', 
				'rules' => "trim|required|valid_email"
			),
			'config_telephone' => array(
				'field' => 'config_telephone', 
				'label' => 'Telephone', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_front' => array(
				'field' => 'config_pagination_limit_front', 
				'label' => 'Pagination limit For front', 
				'rules' => "trim|required|numeric"
			),
			'config_pagination_limit_admin' => array(
				'field' => 'config_pagination_limit_admin', 
				'label' => 'pagination limit for admin', 
				'rules' => "trim|required|numeric"
			),
			'username' => array(
				'field' => 'username', 
				'label' => 'Username', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]"
			),
			'password' => array(
				'field' => 'password', 
				'label' => 'Password', 
				'rules' => 'trim|required|max_length[100]'
			),
			
		);
		
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=$this->lang->line('error_warning');
			return false;
    	}
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */