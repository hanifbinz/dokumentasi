<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
use Zend\Barcode\Barcode;

class Barang extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_barang');
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
            $this->template->load('layoutbackend', 'barang/barang', $data);
        } else {
            $data['page'] = $link;
            $this->template->load('layoutbackend', 'admin/akses_ditolak', $data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_barang->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pel) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pel->id_barang;
            $row[] = $pel->nama_barang;
            $row[] = $pel->nama_supplier;
            $row[] = $pel->jenis_barang;
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit('$pel->id_barang')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"hapus('$pel->id_barang')\"><i class=\"fas fa-trash\"></i></a> ";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_barang->count_all(),
            "recordsFiltered" => $this->Mod_barang->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function insert()
    {
        $this->_validate();
        $save  = array(
            'nama_barang'         => ucwords($this->input->post('nama_barang')),
            'id_barang'         => $this->input->post('id_barang'),
            'nama_supplier'         => $this->input->post('nama_supplier'),
            'jenis_barang'         => $this->input->post('jenis_barang'),

        );
        $this->Mod_barang->insert("barang", $save);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_barang');
        $save  = array(
            'nama_barang'         => ucwords($this->input->post('nama_barang')),
            // 'id_barang'         => $this->input->post('id_barang'),
            'nama_supplier'         => $this->input->post('nama_supplier'),
            'jenis_barang'         => $this->input->post('jenis_barang'),
        );

        $this->Mod_barang->update($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id)
    {
        $data = $this->Mod_barang->get($id);
        echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id_barang');
        $this->Mod_barang->delete($id, 'barang');
        echo json_encode(array("status" => TRUE));
    }
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_barang') == '') {
            $data['inputerror'][] = 'nama_barang';
            $data['error_string'][] = 'Nama Barang Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }
        if ($this->input->post('id_barang') == '') {
            $data['inputerror'][] = 'id_barang';
            $data['error_string'][] = 'ID Barang Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nama_supplier') == '') {
            $data['inputerror'][] = 'nama_supplier';
            $data['error_string'][] = 'Nama Supplier Tidak Boleh Kosong';
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
