<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
use Zend\Barcode\Barcode;

class Angkutan extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_angkutan');
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
            $this->template->load('layoutbackend', 'angkutan/angkutan', $data);
        } else {
            $data['page'] = $link;
            $this->template->load('layoutbackend', 'admin/akses_ditolak', $data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_angkutan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pel) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pel->id_angkutan;
            $row[] = $pel->nama_angkutan;
            $row[] = $pel->jenis_angkutan;
            $row[] = $pel->jenis_barang;
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit('$pel->id_angkutan')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"hapus('$pel->id_angkutan')\"><i class=\"fas fa-trash\"></i></a> ";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_angkutan->count_all(),
            "recordsFiltered" => $this->Mod_angkutan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function insert()
    {
        $this->_validate();
        $save  = array(
            'nama_angkutan'         => ucwords($this->input->post('nama_angkutan')),
            'id_angkutan'         => $this->input->post('id_angkutan'),
            'jenis_angkutan'         => $this->input->post('jenis_angkutan'),
            'jenis_barang'         => $this->input->post('jenis_barang'),

        );
        $this->Mod_angkutan->insert("angkutan", $save);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_angkutan');
        $save  = array(
            'nama_angkutan'         => ucwords($this->input->post('nama_angkutan')),
            'jenis_angkutan'         => $this->input->post('jenis_angkutan'),
            'jenis_barang'         => $this->input->post('jenis_barang'),
        );

        $this->Mod_angkutan->update($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id)
    {
        $data = $this->Mod_angkutan->get($id);
        echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_angkutan');
        $this->Mod_angkutan->delete($id, 'angkutan');
        echo json_encode(array("status" => TRUE));
    }
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('id_angkutan') == '') {
            $data['inputerror'][] = 'id_angkutan';
            $data['error_string'][] = 'ID Angkutan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }
        if ($this->input->post('nama_angkutan') == '') {
            $data['inputerror'][] = 'nama_angkutan';
            $data['error_string'][] = 'Nama Angkutan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }
        if ($this->input->post('jenis_angkutan') == '') {
            $data['inputerror'][] = 'jenis_angkutan';
            $data['error_string'][] = 'Jenis Angkutan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }


        if ($this->input->post('jenis_barang') == '') {
            $data['inputerror'][] = 'jenis_barang';
            $data['error_string'][] = 'Jenis Barang Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
