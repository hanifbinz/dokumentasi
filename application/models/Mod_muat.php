<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_muat extends CI_Model
{
	var $table = 'muat';
	var $column_search = array('full_name', 'tanggal', 'nama_angkutan', 'no_mobil', 'nama_barang', 'nama_customer');
	var $column_order = array('nama_muat');
	var $order = array('id_muat' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->join('customer e', 'a.id_customer=e.id_customer', 'left');
		$this->db->join('angkutan d', 'a.id_angkutan=d.id_angkutan', 'left');
		$this->db->join('tbl_user c', 'a.id_user=c.id_user', 'left');
		$this->db->join('barang b', 'a.id_barang=b.id_barang');
		$this->db->from('muat a');
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
		$this->db->join('tbl_user c', 'a.id_user=c.id_user', 'left');
		$this->db->join('barang b', 'a.id_barang=b.id_barang', 'left');
		$this->db->from('muat a');
		return $this->db->count_all_results();
	}

	function insert($table, $data)
	{
		$insert = $this->db->insert($table, $data);
		return $insert;
	}

	function update($id_muat, $data)
	{
		$this->db->where('id_muat', $id_muat);
		$this->db->update('muat', $data);
	}

	function get($id_muat)
	{
		$this->db->where('id_muat', $id_muat);
		return $this->db->get('muat')->row();
	}

	function delete($id_muat, $table)
	{
		$this->db->where('id_muat', $id_muat);
		$this->db->delete($table);
	}



	function barang()
	{
		return $this->db->get('barang');
	}

	function angkutan()
	{
		return $this->db->get('angkutan');
	}
	function customer()
	{
		return $this->db->get('customer');
	}
}
