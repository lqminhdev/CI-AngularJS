<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Customer_model');
		$this->load->library('form_validation');
		$this->load->helper('security');
	}

	public function index()
	{		
		$data = array();
		$data['web_url'] = $this->config->item('web_url') ;
		
		$this->load->view('layout', $data);
	}
	
	public function load_list_customers()
	{
		$this->load->view('list_customers');		
	}
	
	public function load_edit_customer()
	{
		$this->load->view('edit_customer');
	}
	
	public function list_customers()
	{
		$data = $this->Customer_model->get_all();
	
		$this->output_success($data);
	}
	
	public function get_customer($customer_id)
	{
// 		$data = array(
// 				'customerName'					=> '',
// 				'email'					=> '',
// 				'city'					=> '',
// 				'address'					=> '',
// 				'country'					=> ''
// 		);
		$data = $this->Customer_model->get_customer($customer_id);		
	
		$this->output_success($data);
	}
	
	function add_customer() {
		$this->data['title'] = 'Add Product';

		//validate form input
		$this->form_validation->set_rules('name', 'Product name', 'required|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('price', 'Price', 'required|xss_clean');
		$this->form_validation->set_rules('picture', 'Picture', 'required|xss_clean');

		if ($this->form_validation->run() == true)
		{		
			$data = array(
				'name'				=> $this->input->post('name'),
				'description'		=> $this->input->post('description'),
				'price' 			=> $this->input->post('price'),
				'picture'  			=> $this->input->post('picture')
			);
			
			$this->Customer_model->insert_customer($data);
			
			$this->session->set_flashdata('message', "<p>Product added successfully.</p>");
			
			redirect(base_url().'manage_customers');
		}else{
			//display the add product form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

			$this->data['name'] = array(
				'name'  	=> 'name',
				'id'    	=> 'name',
				'type'  	=> 'text',
				'style'		=> 'width:300px;',
				'value' 	=> $this->form_validation->set_value('name'),
			);			
			$this->data['description'] = array(
				'name'  	=> 'description',
				'id'    	=> 'description',
				'type'  	=> 'text',
				'cols'		=>	60,
				'rows'		=>	5,
				'value' 	=> $this->form_validation->set_value('description'),
			);
			$this->data['price'] = array(
				'name'  	=> 'price',
				'id'    	=> 'price',
				'type'  	=> 'text',
				'style'		=> 'width:40px;text-align: right',
				'value' 	=> $this->form_validation->set_value('price'),
			);
			$this->data['picture'] = array(
				'name'  => 'picture',
				'id'    => 'picture',
				'type'  => 'text',
				'style'	=> 'width:250px;',
				'value' => $this->form_validation->set_value('picture'),
			);
			
			$this->load->view('add_customer', $this->data);
		}
	}
	function output_success($result)
	{
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}
	function submit_customer($customer_id) {
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata, true);
		//print_r($request);
		$data = array(
				'customerName'					=> $request['customerName'],
				'email'					=> $request['email'],
				'city'					=> $request['city'],
				'address'					=> $request['address'],
				'country'					=> $request['country']
		);
		
		if((int)$customer_id > 0)
		{
// 			$data = array(
// 					'customerName'					=> $request['customer']['customerName'],
// 					'email'					=> $request['customer']['email'],
// 					'city'					=> $request['customer']['city'],
// 					'address'					=> $request['customer']['address'],
// 					'country'					=> $request['customer']['country']
// 			);
				
			$result = $this->Customer_model->update_customer($customer_id, $data);
		}
		else
		{			
// 			$data = array(
// 					'customerName'					=> $request['customerName'],
// 					'email'					=> $request['email'],
// 					'city'					=> $request['city'],
// 					'address'					=> $request['address'],
// 					'country'					=> $request['country']
// 			);
				
			$result = $this->Customer_model->insert_customer($data);
		}
		
		$this->output_success($result);
	}	
	
	function delete_customer($customer_id) {
		//$result = true;
		$result = $this->Customer_model->del_customer($customer_id);
		$this->output_success($result);
		
	}
}