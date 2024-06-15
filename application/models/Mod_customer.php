<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_customer extends CI_Model
{
	var $table = 'customer';
	var $column_search = array('nama_customer', 'id_customer', 'alamat_customer', 'email_customer');
	var $column_order = array('nama_customer');
	var $order = array('id_customer' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from('customer a');
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get()->result();
		return count($query);
	}

	function count_all()
	{

		$this->db->from('customer a');
		return $this->db->count_all_results();
	}

	function insert($table, $data)
	{
		$insert = $this->db->insert($table, $data);
		return $insert;
	}

	function update($id, $data)
	{
		$this->db->where('id_customer', $id);
		$this->db->update('customer', $data);
	}

	function get($id)
	{
		$this->db->where('id_customer', $id);
		return $this->db->get('customer')->row();
	}

	function delete($id, $table)
	{
		$this->db->where('id_customer', $id);
		$this->db->delete($table);
	}

	function max_no()
	{
		$today = date("Y-m-d");
		$this->db->select('MAX(SUBSTR(kdcustomer,-4)) AS kode');
		$this->db->order_by('kdcustomer', 'desc');
		return $this->db->get('customer')->result_array();
	}

	function satuan()
	{
		return $this->db->get('satuan');
	}

	function golongan()
	{
		return $this->db->get('golongan');
	}
}
