<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('blog_model');
		$this->load->helper('date');
	}
	public function index(){
      $this->lang->load('blog');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	protected function getList() {
		$this->template->add_package(array('datatable','tablednd'),true);
		$data['add'] = base_url('blog/add');
		$data['delete'] = base_url('blog/delete');
		$data['datatable_url'] = base_url('blog/search');
		$data['datatable_reorder_url'] = base_url('blog/reOrder');

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

		$this->template->view('blog', $data);
	}
	
	public function search() {
		
		$requestData= $_REQUEST;
		//print_r($requestData);
		//exit;
		$totalData = $this->blog_model->getTotalBlogs();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->blog_model->getTotalBlogs($filter_data);
			
		$filteredData = $this->blog_model->getBlogs($filter_data);
		
		$datatable=array();
		$i=1;
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" title="Edit Artist" href="'.site_url('blog/edit/'.$result->id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" title="Delete Artist" href="'.site_url('blog/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				$i,
				$result->name,
				$result->id,
				$action
			);
		$i++;
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
		$this->lang->load('blog');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			$id=$this->blog_model->addBlog($this->input->post());		
			$this->session->set_flashdata('message', 'Artist Saved Successfully.');
			redirect("blog");
		}
		$this->getForm();
	}
	
	public function edit(){
		$this->lang->load('blog');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			$id=$this->uri->segment(3);
			
			$this->blog_model->editBlog($id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Artist Updated Successfully.');
			redirect("blog");
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(3);
       }
		$this->blog_model->deleteBlog($selected);
		$this->session->set_flashdata('message', 'Artist deleted Successfully.');
		redirect("blog");
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','tablednd','colorbox','datetimepicker'),true);
		$data = $this->lang->load('blog');
		$_SESSION['KCFINDER'] = array();
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(3) ? "Artist Edit" : "Artist Add";
		$data['cancel'] = base_url('blog');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(3) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$blog_info = $this->blog_model->getBlog($this->uri->segment(3));
			
			$gallery_images = $this->blog_model->getGalleryImages();
			$data['gallery_images'] = array();
			foreach ($gallery_images as $gallery_image) {
				if (is_file(DIR_IMAGE . $gallery_image->image)) {
					$image = $gallery_image->image;
					$thumb = $gallery_image->image;
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}

				$data['gallery_images'][] = array(
					'image'      => $image,
					'thumb'      => resize($thumb, 100, 100),
					'name' => $gallery_image->name,
					'sort_order' => $gallery_image->sort_order
				);
			}
			
			$data['no_image'] = resize('no_image.png', 100, 100);
		}
		if(empty($gallery_images)){
			$data['gallery_images']	=array();
		}
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} elseif (!empty($blog_info)) {
			$data['name'] = $blog_info->name;
		} else {
			$data['name'] = '';
		}
		
		if ($this->input->post('position')) {
			$data['position'] = $this->input->post('position');
		} elseif (!empty($blog_info)) {
			$data['position'] = $blog_info->position;
		} else {
			$data['position'] = '';
		}
				
		// Images
		if ($this->input->post('image')) {
			$data['image'] = $this->input->post('image');
		}elseif (!empty($blog_info)) {
			$data['image'] = $blog_info->image;
		} else {
			$data['image'] = '';
		} 

		if ($this->input->post('image') && is_file(DIR_IMAGE . $this->input->post('image'))) {
			$data['thumb_logo'] = resize($this->input->post('image'), 100, 100);
		} elseif (!empty($blog_info) && is_file(DIR_IMAGE . $blog_info->image)) {
			$data['thumb_logo'] = resize($blog_info->image, 100, 100);
		} else {
			$data['thumb_logo'] = resize('no_image.png', 100, 100);
		}
		
		
		if ($this->input->post('banner')) {
			$data['banner'] = $this->input->post('banner');
		}elseif (!empty($blog_info)) {
			$data['banner'] = $blog_info->banner;
		} else {
			$data['banner'] = '';
		} 

		if ($this->input->post('banner') && is_file(DIR_IMAGE . $this->input->post('banner'))) {
			$data['thumb_icon_logo'] = resize($this->input->post('banner'), 100, 100);
		} elseif (!empty($blog_info) && is_file(DIR_IMAGE . $blog_info->banner)) {
			$data['thumb_icon_logo'] = resize($blog_info->banner, 100, 100);
		} else {
			$data['thumb_icon_logo'] = resize('no_image.png', 100, 100);
		}
		
		$data['no_image'] = resize('no_image.png', 100, 100);
		
		if($this->input->post('date_added')) {
			$data['date_added'] = $this->input->post('date_added');
		} else if(isset($blog_info->date_added) && $blog_info->date_added) {
			if($blog_info->date_added == '0000-00-00'){
				$data['date_added'] ='';
			}else{
				$data['date_added'] = ymdToMdy($blog_info->date_added);
			}
		} else {
			$data['date_added'] = '';
		}
		
		if ($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
		} elseif (!empty($blog_info)) {
			$data['description'] = $blog_info->description;
		} else {
			$data['description'] = '';
		}
		
		if ($this->input->post('featured')) {
			$data['featured'] = $this->input->post('featured');
		} elseif (!empty($blog_info)) {
			$data['featured'] = $blog_info->featured;
		} else {
			$data['featured'] = '';
		}
		
		if ($this->input->post('readingOrder')) {
			$data['readingOrder'] = $this->input->post('readingOrder');
		} elseif (!empty($blog_info)) {
			$data['readingOrder'] = $blog_info->readingOrder;
		} else {
			$data['readingOrder'] = '';
		}
		$this->template->view('blogForm',$data);
	}
	
	protected function validateForm() {
		$id=$this->uri->segment(3);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Blog Name', 
				'rules' => 'trim|required|max_length[100]'
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
	public function reOrder(){
		$this->blog_model->blogReorder($_POST);
	}
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */