<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_aplikasi extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->database();
	}

	
        
      

    function getAll()
    {
        return $this->db->get("aplikasi");
    }
    function getAplikasi()
    {   
        return $this->db->get("aplikasi")->row();
    }

    function updateAplikasi($id, $data)
    {
        $this->db->where('id', $id);
		$this->db->update('aplikasi', $data);
    }

    function getImage($id)
    {
        $this->db->select('logo');
        $this->db->from('aplikasi');
        $this->db->where('id', $id);
        return $this->db->get();
    }
}
