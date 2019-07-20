<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('banner_model');
	}
	public function index(){
      $this->lang->load('banner');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	protected function getList() {
		
		$this->template->add_package(array('datatable'),true);
		$data['add'] = admin_url('banner/add');
		$data['delete'] = admin_url('banner/delete');
		$data['datatable_url'] = admin_url('banner/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('banners', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->banner_model->getTotalBanners();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->banner_model->getTotalBanners($filter_data);
			
		$filteredData = $this->banner_model->getBanners($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('banner/edit/'.$result->id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('banner/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				$result->title,
				$result->status?'Enabled':'Disabled',
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
	
	public function add(){
		$this->lang->load('banner');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$id=$this->banner_model->addBanner($this->input->post());
			
			$this->session->set_flashdata('message', 'Banner Saved Successfully.');
			redirect(ADMIN_PATH.'banner');
			
		}
		$this->getForm();
	}
	
	public function edit(){
		$this->lang->load('banner');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$id=$this->uri->segment(4);
			
			$this->banner_model->editBanner($id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Banner Updated Successfully.');
			redirect(ADMIN_PATH.'banner');
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->banner_model->deleteBanner($selected);
		$this->session->set_flashdata('message', 'Banner deleted Successfully.');
		redirect("banner");
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','tablednd','colorbox'),true);
		$_SESSION['KCFINDER'] = array();
      $_SESSION['KCFINDER']['disabled'] = false;
      $_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(4) ? "Banner Edit" : "Banner Add";
		$data['cancel'] = admin_url('banner');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$banner_info = $this->banner_model->getBanner($this->uri->segment(4));
		}
		
		//printr($banner_info);
		if ($this->input->post('title')) {
			$data['title'] = $this->input->post('title');
		} elseif (!empty($banner_info)) {
			$data['title'] = $banner_info->title;
		} else {
			$data['title'] = '';
		}
		
		if ($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif (!empty($banner_info)) {
			$data['status'] = $banner_info->status;
		} else {
			$data['status'] = 0;
		}
		

		// Images
		if ($this->input->post('banner_image')) {
			$banner_images = $this->input->post('banner_image');
		} elseif ($this->uri->segment(4)) {
			$banner_images = $this->banner_model->getBannerImages($this->uri->segment(4));
		} else {
			$banner_images = array();
		}

		$data['banner_images'] = array();

		foreach ($banner_images as $banner_image) {
			if (is_file(DIR_UPLOAD . $banner_image->image)) {
				$image = $banner_image->image;
				$thumb = $banner_image->image;
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['banner_images'][] = array(
				'image'      => $image,
				'thumb'      => resize($thumb, 100, 100),
				'title'		 => $banner_image->title,
				'link'		 => $banner_image->link,
				'description'=> $banner_image->description,
				'sort_order' => $banner_image->sort_order
			);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		
		$this->template->view('bannerForm',$data);
	}
	
	protected function validateForm() {
		
		$rules=array(
			'title' => array(
				'field' => 'title', 
				'label' => 'Title', 
				'rules' => 'trim|required|max_length[100]'
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
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */