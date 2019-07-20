<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends Admin_Controller {
	private $error = array();
	
	public function __construct(){
      parent::__construct();
		$this->load->model('pages_model');
	}
	
	public function index(){
		$this->lang->load('pages');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	public function add(){
		
		$this->lang->load('pages');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$userid=$this->pages_model->addPage($this->input->post());
			
			$this->session->set_flashdata('message', 'Page Saved Successfully.');
			redirect(ADMIN_PATH.'/pages');
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('pages');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$id=$this->uri->segment(4);
			
			$this->pages_model->editPage($id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Page Updated Successfully.');
		
			redirect(ADMIN_PATH.'/pages');
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->pages_model->deletePage($selected);
		$this->session->set_flashdata('message', 'Page deleted Successfully.');
		redirect(ADMIN_PATH.'pages');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('pages')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('pages/add');
		$data['delete'] = admin_url('pages/delete');
		$data['datatable_url'] = admin_url('pages/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');
		
		$data['button_add'] = $this->lang->line('button_add');
		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('pages', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->pages_model->getTotalPages();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->pages_model->getTotalPages($filter_data);
			
		$filteredData = $this->pages_model->getPages($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('pages/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('pages/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->title,
				base_url($result->slug),
				$result->layout,
				$result->status,
				$action
			);
	
		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($json_data));  // send data as json format
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','colorbox'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('pages')
		);
		
		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(3) ? "Page Edit" : "Page Add";
		$data['text_image'] =$this->lang->line('text_image');
		$data['text_none'] = $this->lang->line('text_none');
		$data['text_clear'] = $this->lang->line('text_clear');
		$data['cancel'] = admin_url('pages');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$page_info = $this->pages_model->getPage($this->uri->segment(4));
		}
		
		foreach($this->db->list_fields('pages') as $field) {
			if($this->input->post($field)) {
				$data[$field] = $this->input->post($field);
			} else if(isset($page_info->{$field}) && $page_info->{$field}) {
				$data[$field] = html_entity_decode($page_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}
		
		
		if ($this->input->post('content')) {
			$data['content'] = $this->input->post('content');
		} elseif (!empty($page_info)) {
			$data['content'] = html_entity_decode(stripslashes($page_info->content), ENT_QUOTES, 'UTF-8');
		} else {
			$data['content'] = '';
		}

		if ($this->input->post('feature_image')) {
			$data['feature_image'] = $this->input->post('feature_image');
		} elseif (!empty($page_info)) {
			$data['feature_image'] = $page_info->feature_image;
		} else {
			$data['feature_image'] = '';
		}
		
		if ($this->input->post('feature_image') && is_file(DIR_UPLOAD . $this->input->post('feature_image'))) {
			$data['thumb_feature_image'] = resize($this->input->post('feature_image'), 100, 100);
		} elseif (!empty($page_info) && is_file(DIR_UPLOAD . $page_info->feature_image)) {
			$data['thumb_feature_image'] = resize($page_info->feature_image, 100, 100);
		} else {
			$data['thumb_feature_image'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		
		$front_theme = $this->settings->config_front_theme;
		//echo $front_theme;
		$data['layouts']=$this->template->get_theme_layouts($front_theme);
		$data['parents'] = $this->pages_model->getParents($this->uri->segment(4));
		
		//printr($data['parents']);
		$this->template->view('pageForm',$data);
	}
	
	protected function validateForm() {
		$id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules=array(
			'title' => array(
				'field' => 'title', 
				'label' => 'Title', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'slug' => array(
				'field' => 'slug', 
				'label' => 'Slug', 
				'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]|callback_unique_slug_check[$id]"
			),
			'meta_title' => array(
				'field' => 'meta_title', 
				'label' => 'Meta Title', 
				'rules' => 'trim'
			), 
			'meta_description' => array(
				'field' => 'meta_description', 
				'label' => 'Meta Description', 
				'rules' => 'trim'
			),
			'meta_keywords' => array(
				'field' => 'meta_keywords', 
				'label' => 'Meta Keywords', 
				'rules' => 'trim'
			),
			'status' => array(
				'field' => 'status', 
				'label' => 'Status', 
				'rules' => 'trim|required'
			),
			
		);

		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE){
			return true;
    	}
		else{
			$this->error['warning']="Warning: Please check the form carefully for errors!";
			return false;
    	}
		return !$this->error;
	}
	
	public function unique_slug_check($slug, $id = ''){
		$slug_info = $this->general_model->getSlug($slug);
		
		if ($slug_info && $slug_info->route != 'pages/index/' . $id) {
			$this->form_validation->set_message('unique_slug_check', 'This {field} provided is already in use.');
         return FALSE;
		}else{
			return TRUE;
		}
   }
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */