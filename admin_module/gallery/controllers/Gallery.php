<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gallery extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('gallery_model');
	}
	public function index(){
      $this->lang->load('gallery');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	protected function getList() {
		
		$this->template->add_package(array('datatable'),true);
		$data['datatable_url'] = base_url('gallery/search');

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

		$this->template->add_package(array('ckeditor','tablednd','colorbox'),true);
		$_SESSION['KCFINDER'] = array();
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(3) ? "Banner Edit" : "Banner Add";
		$data['cancel'] = base_url('category');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			$this->gallery_model->addGallery($this->input->post());			
			$this->session->set_flashdata('message', 'Gallery Updated Successfully.');
			redirect("gallery");
		}else {
			$gallery_images = $this->gallery_model->getGalleryImages();
		}
		
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
		
		$this->template->view('getGalleryForm',$data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->gallery_model->getTotalgalleries();	
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->gallery_model->getTotalgalleries($filter_data);

		$filteredData = $this->gallery_model->getGalleries($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" title="Edit Gallery" href="'.site_url('gallery/edit/'.$result->id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .= 		'<a class="btn btn-sm btn-primary" title="Add Category Images" href="'.site_url('gallery/addGallery/'.$result->id).'"><i class="fa fa-picture-o" aria-hidden="true"></i></a>';			
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" title="Delete Gallery" href="'.site_url('gallery/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
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

}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */