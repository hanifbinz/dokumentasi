<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_dashboard extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	

	function get_akses_menu($link,$level)
	{
		
		$this->db->where('a.id_level',$level);
		$this->db->where('b.link',$link);
		$this->db->join('tbl_menu b','b.id_menu=a.id_menu');
		return $this->db->get('tbl_akses_menu a');
	}

	function get_akses_submenu($link,$level)
	{
		
		$this->db->where('a.id_level',$level);
		$this->db->where('b.link',$link);
		$this->db->join('tbl_submenu b','b.id_submenu=a.id_submenu');
		return $this->db->get('tbl_akses_submenu a');
	}

	function JmlUser()
	{
		$this->db->from('tbl_user');
		return $this->db->count_all_results();
	}
	
	function Jmlbarang()
	{
		$level = $this->session->userdata['id_level'];
		$id_cabang = $this->session->userdata['id_cabang'];
		if ($level!=1) {
			$this->db->where('id_cabang', $id_cabang);
		} 
		$this->db->from('barang');
		return $this->db->count_all_results();
	}


}
