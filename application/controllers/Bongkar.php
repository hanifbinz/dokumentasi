<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
use Zend\Barcode\Barcode;

class Bongkar extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_bongkar');
        // $this->load->model('dashboard/Mod_dashboard');
    }

    public function index()
    {
        $link = $this->uri->segment(1);
        $level = $this->session->userdata['id_level'];

        // Cek Posisi Menu apakah Sub Menu Atau bukan
        $jml = $this->Mod_dashboard->get_akses_menu($link, $level)->num_rows();
        $data['barang'] = $this->Mod_bongkar->barang()->result();
        $data['angkutan'] = $this->Mod_bongkar->angkutan()->result();
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
            $this->template->load('layoutbackend', 'bongkar/bongkar', $data);
        } else {
            $data['page'] = $link;
            $this->template->load('layoutbackend', 'admin/akses_ditolak', $data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_bongkar->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pel) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pel->full_name;
            $row[] = $pel->tanggal;
            $row[] = $pel->nama_angkutan;
            $row[] = $pel->no_kontainer;
            $row[] = $pel->nama_barang;
            $row[] = $pel->jumlah_barang;
            $row[] = $pel->kode_bongkar;
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_kontainer . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_segel . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_sj . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_barang1 . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_barang2 . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit('$pel->id_bongkar')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"hapus('$pel->id_bongkar')\"><i class=\"fas fa-trash\"></i></a> ";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_bongkar->count_all(),
            "recordsFiltered" => $this->Mod_bongkar->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function insert()
    {
        $config['upload_path']          = 'assets/foto/uploads/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['overwrite']            = true;
        $config['encrypt_name'] = TRUE;


        $this->upload->initialize($config);
        $foto_kontainer = NULL;
        $foto_segel = NULL;
        $foto_sj = NULL;
        $foto_barang1 = NULL;
        $foto_barang2 = NULL;
        if ($this->upload->do_upload('foto_kontainer')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_kontainer = $gbr['file_name'];
        }
        if ($this->upload->do_upload('foto_segel')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_segel = $gbr['file_name'];
        }
        if ($this->upload->do_upload('foto_sj')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_sj = $gbr['file_name'];
        }
        if ($this->upload->do_upload('foto_barang1')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang1 = $gbr['file_name'];
        }
        if ($this->upload->do_upload('foto_barang2')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang2 = $gbr['file_name'];
        }
        $id_user = $this->session->userdata['id_user'];
        $save  = array(
            'id_user'         => $id_user,
            'tanggal'         => $this->input->post('tanggal'),
            'id_angkutan'         => $this->input->post('id_angkutan'),
            'no_kontainer'         => $this->input->post('no_kontainer'),
            'id_barang'         => $this->input->post('id_barang'),
            'jumlah_barang'         => $this->input->post('jumlah_barang'),
            'kode_bongkar'         => $this->input->post('kode_bongkar'),
            'foto_kontainer'         => $foto_kontainer,
            'foto_segel'         => $foto_segel,
            'foto_sj'         => $foto_sj,
            'foto_barang1'         => $foto_barang1,
            'foto_barang2'         => $foto_barang2,


        );
        $this->Mod_bongkar->insert("bongkar", $save);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        // $this->_validate();
        $id      = $this->input->post('id');
        $data = $this->Mod_bongkar->get($id);
        $config['upload_path']          = 'assets/foto/uploads';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['overwrite']            = true;
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);
        $foto_kontainer = $data->foto_kontainer;
        $foto_segel = $data->foto_segel;
        $foto_sj = $data->foto_sj;
        $foto_barang1 = $data->foto_barang1;
        $foto_barang2 = $data->foto_barang2;
        if ($this->upload->do_upload('foto_kontainer')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_kontainer = $gbr['file_name'];
            if (!empty($data->foto_kontainer)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_kontainer);
            }
        }
        if ($this->upload->do_upload('foto_segel')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_segel = $gbr['file_name'];
            if (!empty($data->foto_segel)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_segel);
            }
        }
        if ($this->upload->do_upload('foto_sj')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_sj = $gbr['file_name'];
            if (!empty($data->foto_sj)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_sj);
            }
        }
        if ($this->upload->do_upload('foto_barang1')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang1 = $gbr['file_name'];
            if (!empty($data->foto_barang1)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_barang1);
            }
        }
        if ($this->upload->do_upload('foto_barang2')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang2 = $gbr['file_name'];
            if (!empty($data->foto_barang2)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_barang2);
            }
        }
        $save  = array(
            'tanggal'         => $this->input->post('tanggal'),
            'id_angkutan'         => $this->input->post('id_angkutan'),
            'no_kontainer'         => $this->input->post('no_kontainer'),
            'id_barang'         => $this->input->post('id_barang'),
            'jumlah_barang'         => $this->input->post('jumlah_barang'),
            'kode_bongkar'         => $this->input->post('kode_bongkar'),
            'foto_kontainer'         => $foto_kontainer,
            'foto_segel'         => $foto_segel,
            'foto_sj'         => $foto_sj,
            'foto_barang1'         => $foto_barang1,
            'foto_barang2'         => $foto_barang2,
        );

        $this->Mod_bongkar->update($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id)
    {
        $data = $this->Mod_bongkar->get($id);
        echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->Mod_bongkar->get($id);
        if (!empty($data->foto_kontainer)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_kontainer);
        }
        if (!empty($data->foto_sj)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_sj);
        }
        if (!empty($data->foto_segel)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_segel);
        }
        if (!empty($data->foto_barang1)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_barang1);
        }
        if (!empty($data->foto_barang2)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_barang2);
        }
        $this->Mod_bongkar->delete($id, 'bongkar');
        echo json_encode(array("status" => TRUE));
    }

    public function resizeimg($path, $file)
    {
        // $sizes = array(200, 70, 40);

        $this->load->library('image_lib');

        // foreach ($sizes as $size) {
        $config['image_library']    = 'gd2';
        $config['source_image']     = $path;
        $config['create_thumb']     = false;
        $config['maintain_ratio']   = true;
        $config['width']            = 600;
        $config['height']           = 400;
        $config['new_image']        = 'assets/foto/uploads/' . $file;

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        // }
    }
}
