<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_login extends CI_Model {
    function Aplikasi()
    {
        return $this->db->get('aplikasi');
    }

    function Auth($username, $password)
    {

        //menggunakan active record . untuk menghindari sql injection
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $this->db->where("is_active", 'Y');
        return $this->db->get("tbl_user");    
    }

    function check_db($username)
    {
        return $this->db->get_where('tbl_user', array('username' => $username));
    }

    function akses_menu($idlevel)
    {

        $this->db->select('b.link');
        $this->db->where('a.id_level', $idlevel, 'ORDER BY urutan ASC LIMIT 1');
        $this->db->where('a.view','Y');
        $this->db->join('tbl_menu b', 'a.id_menu=b.id_menu');
       return $this->db->get('tbl_akses_menu a');
    }


}

/* End of file Mod_login.php */
