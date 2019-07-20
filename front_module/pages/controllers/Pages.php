<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends MY_Controller {
	private $error = array();
	function __construct(){
		parent::__construct();
		$this->load->model('pages_model');
	}
	
	public function index(){
		
		$this->lang->load('pages');
		$id=0;
		if($this->uri->segment(3)){
			$id=$this->uri->segment(3);
		}else if($this->uri->segment(1)){
			$id=$this->pages_model->getIdbySlug($this->uri->segment(1));
		}
		
		$Page = $this->pages_model->getPageinfobyId($id);
      if (isset($Page) && !empty($Page)){  
         if ($Page->status != 'published'){
            if ($Page->status != 'draft' || ($Page->status == 'draft' &&  ! $this->secure->group_types(array(ADMINISTRATOR))->is_auth())){
               return $this->_404_error();
				}
         }
         $this->template->set('page_id', $Page->id);
         $data['heading_title'] = $Page->title;
         $data['content'] = html_entity_decode($Page->content, ENT_QUOTES, 'UTF-8');
			$this->template->set_meta_description($Page->meta_description)
                           ->set_meta_keywords($Page->meta_keywords); 
			$this->template->set_layout($Page->layout);
			$data['layout']=$Page->layout;
         // Output Page
         $this->template->view('pages', $data);
      }else{
         return $this->_404_error();
      }
	}
	
	public function home(){
		
		$data=array();
		$Page = $this->pages_model->getPageinfobyId(1);
      if (isset($Page) && !empty($Page)){  
         if ($Page->status != 'published'){
            if ($Page->status != 'draft'){
               return $this->error();
				}
         }
         //$data['heading_title'] = $Page->title;
         $data['content'] = $Page->content;
      }
		//$data['banners'] = $this->plugin->getBanners(3);
		//$this->template->add_package('nivoslider',true);
		
		$this->template->view('home',$data);
	}
	
	public function contact(){
		
		$data=array();
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateContactForm()){
			$mailstaus=sendmail($this->settings->email,$this->input->post('subject'),"Thank You contact me");
			if($mailstaus){
				$this->session->set_flashdata('message', 'Thank You contact me');
				redirect("contact-us");
			}
		}
		if (isset($this->error['warning'])) {
			$data['error'] = $this->error['warning'];
		} 
	
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} else {
			$data['name'] = '';
		}
		
		if ($this->input->post('email')) {
			$data['email'] = $this->input->post('email');
		} else {
			$data['email'] = '';
		}

		if ($this->input->post('phone')) {
			$data['phone'] = $this->input->post('phone');
		} else {
			$data['phone'] = '';
		}

		if ($this->input->post('subject')) {
			$data['subject'] = $this->input->post('subject');
		} else {
			$data['subject'] = '';
		}
		
		
		if ($this->input->post('message')) {
			$data['message'] = $this->input->post('message');
		} else {
			$data['message'] = '';
		}
		
		$this->load->view('contact',$data);
	}
	
	protected function validateContactForm(){
		
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Name', 
				'rules' => 'trim|required'
			),
			'email' => array(
				'field' => 'email', 
				'label' => 'Email', 
				'rules' => 'trim|required|valid_email'
			),
			'subject' => array(
				'field' => 'subject', 
				'label' => 'Subject', 
				'rules' => "trim|required"
			),
			'message' => array(
				'field' => 'message', 
				'label' => 'Message', 
				'rules' => 'trim|required'
			),
			
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=' Warning: Please check the form carefully for errors !!';
			return false;
    	}
   }
	
	public function error(){
      // Send a 404 Header
      header("HTTP/1.0 404 Not Found");
		$data['heading_title'] = "Unknown Page.";
      $data['content'] = "Page not found.";
      $this->template->view('pages', $data);
   }
	
			
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */