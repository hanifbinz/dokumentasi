<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
use Zend\Barcode\Barcode;

class Muat extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_muat');
        $this->load->library('upload');
        // $this->load->model('dashboard/Mod_dashboard');
    }

    public function index()
    {
        $link = $this->uri->segment(1);
        $level = $this->session->userdata['id_level'];

        // Cek Posisi Menu apakah Sub Menu Atau bukan
        $jml = $this->Mod_dashboard->get_akses_menu($link, $level)->num_rows();
        $data['barang'] = $this->Mod_muat->barang()->result();
        $data['angkutan'] = $this->Mod_muat->angkutan()->result();
        $data['customer'] = $this->Mod_muat->customer()->result();
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
            $this->template->load('layoutbackend', 'muat/muat', $data);
        } else {
            $data['page'] = $link;
            $this->template->load('layoutbackend', 'admin/akses_ditolak', $data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_muat->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pel) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pel->full_name;
            $row[] = $pel->tanggal;
            $row[] = $pel->nama_angkutan;
            $row[] = $pel->no_mobil;
            $row[] = $pel->nama_barang;
            $row[] = $pel->jumlah_barang;
            $row[] = $pel->nama_customer;
            $row[] = $pel->no_do;
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_mobil . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_bak . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_do . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_barang1 . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_barang2 . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a download class=\"btn btn-success\" href=" . base_url('assets/foto/uploads/' . $pel->foto_barang3 . '') . " title=\"Download\" ><i class=\"fas fa-download\"></i></a>";
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit('$pel->id_muat')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"hapus('$pel->id_muat')\"><i class=\"fas fa-trash\"></i></a> ";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_muat->count_all(),
            "recordsFiltered" => $this->Mod_muat->count_filtered(),
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
        $foto_mobil = NULL;
        $foto_bak = NULL;
        $foto_do = NULL;
        $foto_barang1 = NULL;
        $foto_barang2 = NULL;
        $foto_barang3 = NULL;
        if ($this->upload->do_upload('foto_mobil')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_mobil = $gbr['file_name'];
        }


        if ($this->upload->do_upload('foto_bak')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_bak = $gbr['file_name'];
        }

        if ($this->upload->do_upload('foto_do')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_do = $gbr['file_name'];
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

        if ($this->upload->do_upload('foto_barang3')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang3 = $gbr['file_name'];
        }
        $id_user = $this->session->userdata['id_user'];
        $save  = array(
            'id_user'         => $id_user,
            'tanggal'         => $this->input->post('tanggal'),
            'id_angkutan'         => $this->input->post('id_angkutan'),
            'no_mobil'         => $this->input->post('no_mobil'),
            'id_barang'         => $this->input->post('id_barang'),
            'jumlah_barang'         => $this->input->post('jumlah_barang'),
            'no_do'         => $this->input->post('no_do'),
            'id_customer'         => $this->input->post('id_customer'),
            'foto_mobil'         => $foto_mobil,
            'foto_bak'         => $foto_bak,
            'foto_do'         => $foto_do,
            'foto_barang1'         => $foto_barang1,
            'foto_barang2'         => $foto_barang2,
            'foto_barang3'         => $foto_barang3,


        );
        $this->Mod_muat->insert("muat", $save);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        // $this->_validate();
        $id      = $this->input->post('id');
        $data = $this->Mod_muat->get($id);
        $config['upload_path']          = 'assets/foto/uploads';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['overwrite']            = true;
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);
        $foto_mobil = $data->foto_mobil;
        $foto_bak = $data->foto_bak;
        $foto_do = $data->foto_do;
        $foto_barang1 = $data->foto_barang1;
        $foto_barang2 = $data->foto_barang2;
        $foto_barang3 = $data->foto_barang3;
        if ($this->upload->do_upload('foto_mobil')) {
            $gbr = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_mobil = $gbr['file_name'];
            if (!empty($data->foto_mobil)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_mobil);
            }
        }
        if ($this->upload->do_upload('foto_bak')) {
            $gbr1 = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr1['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_bak = $gbr1['file_name'];
            if (!empty($data->foto_bak)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_bak);
            }
        }
        if ($this->upload->do_upload('foto_do')) {
            $gbr2 = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr2['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_do = $gbr2['file_name'];
            if (!empty($data->foto_do)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_do);
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
        if ($this->upload->do_upload('foto_barang3')) {
            $gbr5 = $this->upload->data();
            $path = 'assets/foto/uploads/' . $gbr5['file_name'];
            $file = $gbr['file_name'];
            $this->resizeimg($path, $file);
            $foto_barang3 = $gbr5['file_name'];
            if (!empty($data->foto_barang3)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/uploads/' . $data->foto_barang3);
            }
        }
        $save  = array(
            'tanggal'         => $this->input->post('tanggal'),
            'id_angkutan'         => $this->input->post('id_angkutan'),
            'no_mobil'         => $this->input->post('no_mobil'),
            'id_barang'         => $this->input->post('id_barang'),
            'jumlah_barang'         => $this->input->post('jumlah_barang'),
            'no_do'         => $this->input->post('no_do'),
            'foto_mobil'         => $foto_mobil,
            'foto_bak'         => $foto_bak,
            'foto_do'         => $foto_do,
            'foto_barang1'         => $foto_barang1,
            'foto_barang2'         => $foto_barang2,
            'foto_barang3'         => $foto_barang3,
        );

        $this->Mod_muat->update($id, $save);
        echo json_encode(array("status" => TRUE));
    }

    public function edit($id)
    {
        $data = $this->Mod_muat->get($id);
        echo json_encode($data);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->Mod_muat->get($id);
        if (!empty($data->foto_mobil)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_mobil);
        }
        if (!empty($data->foto_bak)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_bak);
        }
        if (!empty($data->foto_do)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_do);
        }
        if (!empty($data->foto_barang1)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_barang1);
        }
        if (!empty($data->foto_barang2)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_barang2);
        }
        if (!empty($data->foto_barang3)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/uploads/' . $data->foto_barang3);
        }
        $this->Mod_muat->delete($id, 'muat');
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
