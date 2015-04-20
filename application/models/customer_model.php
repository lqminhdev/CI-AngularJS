<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	function get_all() {
		$this->db->select('customerNumber, customerName, email, address, city, state, postalCode, country');
		$query = $this->db->get('angularcode_customers');

		return $query->result_array();
	}
	
	function get_customer($customer_id) {
		$this->db->select('customerNumber, customerName, email, address, city, state, postalCode, country');
		$this->db->where('customerNumber', $customer_id);
		$query = $this->db->get('angularcode_customers');

		return $query->row_array();
	}

	
	public function insert_customer($data)
	{
		$this->db->insert('angularcode_customers', $data);
		
		$id = $this->db->insert_id();
		
		return (isset($id)) ? $id : FALSE;
	}

	public function update_customer($customer_id, $data)
	{
		$this->db->where('customerNumber', $customer_id);
		return $this->db->update('angularcode_customers', $data);
	}
	
	public function del_customer($customer_id)
	{
		$this->db->where('customerNumber', $customer_id);
		return $this->db->delete('angularcode_customers');
	}
}