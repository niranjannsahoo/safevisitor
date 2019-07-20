<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Country extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('country_model');		
	}
	
	public function index(){
      $this->lang->load('country');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getListForm();  
	}
	
	protected function getListForm() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => "Country",
			'href' => base_url('localisation/country')
		);
		/*form*/
		$this->template->add_package(array('datatable','select2'),true);
      
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(3) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		
		if (!$this->uri->segment(4)) {
			$data['action'] = site_url("localisation/country/add");
		} else {
			$data['action'] = site_url("localisation/country/edit/".$this->uri->segment(4));
		}
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		$data['cancel'] = base_url('localisation/country');

		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$country_info = $this->country_model->getCountry($this->uri->segment(4));
		}
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} elseif (!empty($country_info)) {
			$data['name'] = $country_info->name;
		} else {
			$data['name'] = '';
		}
		
		if ($this->input->post('iso_code_2')) {
			$data['iso_code_2'] = $this->input->post('iso_code_2');
		} elseif (!empty($country_info)) {
			$data['iso_code_2'] = $country_info->iso_code_2;
		} else {
			$data['iso_code_2'] = '';
		}
		
		if ($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif (!empty($country_info)) {
			$data['status'] = $country_info->status;
		} else {
			$data['status'] = '';
		}
		
		/*list*/
		$data['delete'] = base_url('localisation/country/delete');
		$data['datatable_url'] = base_url('localisation/country/search');

		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');


		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('country_list_form', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		
		$columns = array( 
			0 => '',
			1 => 'name',
			1 => 'iso_code_2',
			3 => 'status'
		);
		
		$totalData = $this->country_model->getTotalCountry();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => intval($requestData['order'][0]['column']) ? $columns[$requestData['order'][0]['column']] : 'name',
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->country_model->getTotalCountry($filter_data);
			
		$filteredData = $this->country_model->getCountries($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.site_url('localisation/country/edit/'.$result->country_id).'"><i class="glyphicon glyphicon-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.base_url('localisation/country/delete/'.$result->country_id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="glyphicon glyphicon-trash"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->country_id.'" />',
				$result->name,
				$result->iso_code_2,
				$result->status ? 'Enable':'Disable',
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
		$this->lang->load('country');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			
			$country_id=$this->country_model->addCountry($this->input->post());
			
			$this->session->set_flashdata('message', 'country Saved Successfully.');
			redirect("localisation/country");
		}
		$this->getListForm();
	}
	
	public function edit(){
		
		$this->lang->load('country');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$country_id=$this->uri->segment(4);
			
			
			$userid=$this->country_model->editCountry($country_id,$this->input->post());
			
			$this->session->set_flashdata('message', 'country Updated Successfully.');
			redirect("localisation/country");
		}
		$this->getListForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(3);
       }
		$this->country_model->deleteCountry($selected);
		$this->session->set_flashdata('message', 'country deleted Successfully.');
		redirect("localisation/country");
	}
	
	protected function validateForm() {
		$country_id=$this->uri->segment(3);
		
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Country Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run($this) == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=$this->lang->line('error_warning');
			return false;
    	}
		return !$this->error;
	}
	
	public function country($country_id){
		if (is_ajax())
      {
			$this->load->model('localisation/state_model');	
			$json = array();
			$json = array(
				'country_id'  	=> $country_id,
				'state'        => $this->state_model->getStatesByCountryId($country_id)		
			);
			echo json_encode($json);
		}
      else
      {
         return show_404();
      }
	}
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */