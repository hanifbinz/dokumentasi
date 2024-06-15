<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_angkutan extends CI_Model
{
	var $table = 'angkutan';
	var $column_search = array('nama_angkutan', 'id_angkutan', 'nama_supplier', 'jenis_angkutan');
	var $column_order = array('nama_angkutan');
	var $order = array('id_angkutan' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from('angkutan a');
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

		$this->db->from('angkutan a');
		return $this->db->count_all_results();
	}

	function insert($table, $data)
	{
		$insert = $this->db->insert($table, $data);
		return $insert;
	}

	function update($id, $data)
	{
		$this->db->where('id_angkutan', $id);
		$this->db->update('angkutan', $data);
	}

	function get($id)
	{
		$this->db->where('id_angkutan', $id);
		return $this->db->get('angkutan')->row();
	}

	function delete($id, $table)
	{
		$this->db->where('id_angkutan', $id);
		$this->db->delete($table);
	}
}
