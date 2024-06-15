<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
use Zend\Barcode\Barcode;

class customer extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_customer');
        // $this->load->model('dashboard/Mod_dashboard');
    }

    public function index()
    {
        $link = $this->uri->segment(1);
        $level = $this->session->userdata['id_level'];

        // Cek Posisi Menu apakah Sub Menu Atau bukan
        $jml = $this->Mod_dashboard->get_akses_menu($link, $level)->num_rows();

        if ($jml > 0) { //Jika Menu
            $data['akses_menu'] = $this->Mod_dashboard->get_akses_menu($link, $level)->row();
            $a_menu = $this->Mod_dashboard->get_akses_menu($link, $level)->row();
            $akses = $a_menu->view;
        } else {
            $data['akses_menu'] = $this->Mod_dashboard->get_akses_submenu($link, $level)->row();
            $a_submenu = $this->Mod_dashboard->get_akses_submenu($link, $level)->row();
            $akses = $a_submenu->view;
        }
        if ($akses == "Y") {
            $this->template->load('layoutbackend', 'customer/customer', $data);
        } else {
            $data['page'] = $link;
            $this->template->load('layoutbackend', 'admin/akses_ditolak', $data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_customer->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pel) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pel->id_customer;
            $row[] = $pel->nama_customer;
            $row[] = $pel->alamat_customer;
            $row[] = $pel->email_customer;
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit('$pel->id_customer')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"hapus('$pel->id_customer')\"><i class=\"fas fa-trash\"></i></a> ";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_customer->count_all(),
            "recordsFiltered" => $this->Mod_customer->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function insert()
    {
        $this->_validate();
        $save  = array(
            'nama_customer'         => ucwords($this->input->post('nama_customer')),
            'id_customer'         => $this->input->post('id_customer'),
            'alamat_customer'         => $this->input->post('alamat_customer'),
            'email_customer'         => $this->input->post('email_customer'),

        );
        $this->Mod_customer->insert("customer", $save);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_customer');
        $save  = array(
            'nama_customer'         => ucwords($this->input->post('nama_customer')),
            'alamat_customer'         => $this->input->post('alamat_customer'),
            'email_customer'         => $this->input->post('email_customer'),
        );

        $this->Mod_customer->update($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id)
    {
        $data = $this->Mod_customer->get($id);
        echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_customer');
        $this->Mod_customer->delete($id, 'customer');
        echo json_encode(array("status" => TRUE));
    }
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('id_customer') == '') {
            $data['inputerror'][] = 'id_customer';
            $data['error_string'][] = 'ID Customer Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }
        if ($this->input->post('nama_customer') == '') {
            $data['inputerror'][] = 'nama_customer';
            $data['error_string'][] = 'Nama Customer Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('alamat_customer') == '') {
            $data['inputerror'][] = 'alamat_customer';
            $data['error_string'][] = 'Alamat Customer Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('email_customer') == '') {
            $data['inputerror'][] = 'email_customer';
            $data['error_string'][] = 'Email Customer Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
