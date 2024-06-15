<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_laporan extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_laporan_bongkar($tglrange,$id_barang,$id_angkutan)
	{

		if (!empty($id_barang)) {
			$this->db->where('a.id_barang',$id_barang);
		}

		$date=explode(" - ", $tglrange);
		$p1=date("Y-m-d", strtotime($date[0]));
		$p2=date("Y-m-d", strtotime($date[1]));
		
		if (!empty($tglrange)) {
			$this->db->where('date(a.tanggal) BETWEEN "'.$p1.'" AND "'.$p2.'"');
		}

		
		if (!empty($id_angkutan)) {
			$this->db->where('a.id_angkutan',$id_angkutan);
		}

		$this->db->join('angkutan d','a.id_angkutan=d.id_angkutan','left');
		$this->db->join('tbl_user c','a.id_user=c.id_user','left');
		$this->db->join('barang b','a.id_barang=b.id_barang');
		return $this->db->get('bongkar a');
	}

	public function get_laporan_muat($tglrange,$id_barang,$id_customer,$id_angkutan)
	{

		$and="";
		if (!empty($id_barang)) {
			$this->db->where('a.id_barang',$id_barang);
		}

		$date=explode(" - ", $tglrange);
		$p1=date("Y-m-d", strtotime($date[0]));
		$p2=date("Y-m-d", strtotime($date[1]));
		$and1="";
		if (!empty($tglrange)) {
			$this->db->where('date(a.tanggal) BETWEEN "'.$p1.'" AND "'.$p2.'"');
		}
		if (!empty($id_angkutan)) {
			$this->db->where('a.id_angkutan',$id_angkutan);
		}

		if (!empty($id_customer)) {
			$this->db->where('a.id_customer',$id_customer);
		}
		$this->db->join('customer e','a.id_customer=e.id','left');
		$this->db->join('angkutan d','a.id_angkutan=d.id_angkutan','left');
		$this->db->join('tbl_user c','a.id_user=c.id_user','left');
		$this->db->join('barang b','a.id_barang=b.id_barang');
		return $this->db->get('muat a');
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
