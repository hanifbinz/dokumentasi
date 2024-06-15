<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_fungsi extends CI_Model
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

	function Jmlmasuk()
	{
		$level = $this->session->userdata['id_level'];
		$id_cabang = $this->session->userdata['id_cabang'];
		if ($level!=1) {
			$this->db->where('id_cabang', $id_cabang);
		} 
		$this->db->select('SUM(masuk) as total');
		$this->db->from('stok_opname');
		return $this->db->get()->row();
	}

	function Jmlkeluar()
	{
		$level = $this->session->userdata['id_level'];
		$id_cabang = $this->session->userdata['id_cabang'];
		if ($level!=1) {
			$this->db->where('id_cabang', $id_cabang);
		} 
		$this->db->select('SUM(keluar) as total');
		$this->db->from('stok_opname');
		return $this->db->get()->row();
	}


	function terlaris($id, $tgl)
	{
		$date=explode(" - ", $tgl);
 		$p1=date("Y-m-d", strtotime($date[0]));
		$p2=date("Y-m-d", strtotime($date[1]));

		$level = $this->session->userdata['id_level'];
		$id_cabang = $this->session->userdata['id_cabang'];
		$and="";
		if ($level!=1) {
			$and .= " AND c.id_cabang='$id_cabang'";
		} 
		$and1='';
		if (!empty($id)) {
			$and1 .= " AND b.`golongan`='$id'";
		}

		$sql=$this->db->query("SELECT b.`nama` , COUNT(a.id_barang) AS total FROM keluar_detail a 
			JOIN barang b ON a.`id_barang`=b.`id`
			JOIN keluar c ON a.`id_keluar`=c.`id` WHERE 1=1 $and $and1 AND date(c.`tanggal`) BETWEEN '$p1' AND '$p2'  GROUP BY a.`id_barang` ORDER BY total LIMIT 10 ");
		return $sql;
	}

	function chart_pelanggan($tgl)
	{
		$date=explode(" - ", $tgl);
 		$p1=date("Y-m-d", strtotime($date[0]));
		$p2=date("Y-m-d", strtotime($date[1]));

		$level = $this->session->userdata['id_level'];
		$id_cabang = $this->session->userdata['id_cabang'];
		$and="";
		if ($level!=1) {
			$and= ' AND b.id_cabang='.$id_cabang.' ';
		} 
		$sql=$this->db->query("SELECT c.`nama`, COUNT(b.id_pelanggan) AS total FROM keluar_detail a 
			JOIN keluar b ON a.`id_keluar`=b.`id`
			JOIN pelanggan c ON b.`id_pelanggan`=c.`id` WHERE 1=1   AND date(b.`tanggal`) BETWEEN '$p1' AND '$p2' $and GROUP BY b.`id_pelanggan` ORDER BY total LIMIT 10  ");
		return $sql;
	}

	function getAllcabang()
	{
		$this->db->from('cabang');
		return $this->db->get();
	}


	public function get_laporan($id_cabang, $tglrange)
	{

		$level = $this->session->userdata['id_level'];
			
		$and="";
		if (!empty($id_cabang)) {
			$and = " AND a.id_cabang='".$id_cabang."' ";
		}

		
		$and1="";
		if (!empty($tglrange)) {
			$date=explode(" - ", $tglrange);
			$p1=date("Y-m-d", strtotime($date[0]));
			$p2=date("Y-m-d", strtotime($date[1]));
			$and1 = " AND date(a.tanggal) BETWEEN '$p1' AND '$p2'";
		}

		$sql = $this->db->query("SELECT a.id_cabang, b.nama AS nama_cabang, SUM(a.masuk) AS masuk, SUM(a.`keluar`) AS keluar  FROM `stok_opname` a  JOIN cabang b ON a.id_cabang=b.id WHERE 1=1  $and $and1   GROUP BY a.`id_cabang`  ");
		return $sql;
	}

	 public function stokawal($id_cabang, $tanggal)
    {
        $a= $this->db->select('(sum(masuk)-sum(keluar)) as awal');
        $a= $this->db->where('id_cabang',$id_cabang);
        $a= $this->db->where('tanggal <',$tanggal);
        $a= $this->db->get('stok_opname')->row();
         return $a;
    }

    public function stokakhir($id_cabang, $tanggal)
    {
        $b= $this->db->select('(sum(masuk)-sum(keluar)) as sisa');
        $b= $this->db->where('id_cabang',$id_cabang);
        $b= $this->db->where('tanggal <=',$tanggal);
        $b= $this->db->get('stok_opname')->row();
        return $b;
    }

    public function pmasuk($id_cabang, $tanggal)
    {
    	$this->db->select('sum(keluar) as pmasuk');
        $this->db->where('id_cabang',$id_cabang);
        $this->db->where('tgl_input <=',$tanggal);
        $this->db->where('transaksi', 'Retur Penerimaan');
        return $this->db->get('stok_opname')->row();
    }

    public function pkeluar($id_cabang, $tanggal)
    {
    	$this->db->select('sum(masuk) as pkeluar');
        $this->db->where('id_cabang',$id_cabang);
        $this->db->where('tgl_input <=',$tanggal);
        $this->db->where('transaksi', 'Retur Keluar');
        return $this->db->get('stok_opname')->row();
    }

        function get_brg($id)
    {   
    	$level = $this->session->userdata['id_level'];
		 $id_cabang = $this->session->userdata['id_cabang'];
		 if ($level!=1) {
			$this->db->where('a.id_cabang', $id_cabang);
		} 
		$this->db->select('a.*,b.nama as nama_satuan');
    	$this->db->like('a.barcode', $id);
    	$this->db->or_like('a.id_cabang="'.$id_cabang.'" AND a.nama', $id);
    	$this->db->join('satuan b', 'a.id_satuan=b.id');
    	$this->db->limit(10);
        return $this->db->get('barang a')->result();
    }
}
