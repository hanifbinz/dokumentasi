<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Aplikasi extends MY_Controller // Pastikan MY_Controller adalah kelas induk yang benar
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_aplikasi','Mod_dashboard'));
    }



	public function index()
	{
		
        $this->load->helper('url');
        $link = $this->uri->segment(1);
        $level = $this->session->userdata['id_level'];
        // Cek Posisi Menu apakah Sub Menu Atau bukan
        $jml = $this->Mod_dashboard->get_akses_menu($link,$level)->num_rows();;
        if ($jml > 0) {//Jika Menu
            $data['akses_menu'] = $this->Mod_dashboard->get_akses_menu($link,$level)->row();
            $a_menu = $this->Mod_dashboard->get_akses_menu($link,$level)->row();
            $akses=$a_menu->view;
        }else{
            $data['akses_menu'] = $this->Mod_dashboard->get_akses_submenu($link,$level)->row();
            $a_submenu = $this->Mod_dashboard->get_akses_submenu($link,$level)->row();
            $akses=$a_submenu->view;
        }
        if ($akses=="Y") {
            $data['aplikasi'] = $this->Mod_aplikasi->getAplikasi();
           $this->template->load('layoutbackend', 'admin/aplikasi',$data);
        }else{
            $data['page']=$link;
            $this->template->load('layoutbackend','login/akses_ditolak',$data);
        }
	}

	   

    public function edit_aplikasi($id)
    {   
            $data = $this->Mod_aplikasi->getAplikasi($id);
            echo json_encode($data);
        
    }

    public function update_template()
    {
       
    }
        public function update()
    {
        if(!empty($_FILES['imagefile']['name'])) {
        $this->_validate();
        $id = $this->input->post('id');
        
        $nama = slug($this->input->post('logo'));
        $config['upload_path']   = './assets/foto/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; //mencegah upload backdor
        $config['max_size']      = '1000';
        $config['overwrite']     = true;
        $config['file_name']     = $nama; 
        
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('imagefile')){
            $gambar = $this->upload->data();
            $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi'),
                'logo' => $gambar['file_name']
            );
            
            $g = $this->Mod_aplikasi->getImage($id)->row_array();

            if ($g != null || $g !="") {
                //hapus gambar yg ada diserver
                unlink('assets/foto/logo/'.$g['logo']);
            }
           
            $this->Mod_aplikasi->updateAplikasi($id, $save);
            echo json_encode(array("status" => TRUE));
            }else{//Apabila tidak ada gambar yang di upload
                $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi')
            );
                $this->Mod_aplikasi->updateAplikasi($id, $save);
                echo json_encode(array("status" => TRUE));
            }
        }else{
            $this->_validate();
            $id = $this->input->post('id');
            $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'alamat'    => $this->input->post('alamat'),
                'tlp'       => $this->input->post('tlp'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi')
            );
            $this->Mod_aplikasi->updateAplikasi($id, $save);
            echo json_encode(array("status" => TRUE));
        }
    }

public function backupdb()
    {
        $this->load->dbutil();
        $aturan = array(
            'format'    => 'zip',
            'filename'  => 'my_db_backup.sql',
            'foreign_key_checks'    => FALSE
        );

        $backup= $this->dbutil->backup($aturan);

        $nama_database = $this->db->database.'-'. date("Y-m-d-H-i-s").'.zip';
        $simpan= base_url('DB/').$nama_database;

        $this->load->helper('file');
        write_file($simpan, $backup);
        $this->load->helper('download');
        force_download($nama_database, $backup);
    }
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('nama_owner') == '')
        {
            $data['inputerror'][] = 'nama_owner';
            $data['error_string'][] = 'Nama Owner Tidak Boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('nama_aplikasi') == '')
        {
            $data['inputerror'][] = 'nama_aplikasi';
            $data['error_string'][] = 'Nama Aplikasi Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('alamat') == '')
        {
            $data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Alamat Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tlp') == '')
        {
            $data['inputerror'][] = 'tlp';
            $data['error_string'][] = 'No Telpon Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('title') == '')
        {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title Tidak boleh kosong';
            $data['status'] = FALSE;
        }
   

        if($this->input->post('copy_right') == '')
        {
            $data['inputerror'][] = 'copy_right';
            $data['error_string'][] = 'Copy Right tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tahun') == '')
        {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun tidak boleh kosong';
            $data['status'] = FALSE;
        }


        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }


     public function download()
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Aplikasi');
            $sheet->setCellValue('C1', 'Nama Owner');
            $sheet->setCellValue('D1', 'No Telp');
            $sheet->setCellValue('E1', 'Title');
            $sheet->setCellValue('F1', 'Copy Right');
            $sheet->setCellValue('G1', 'Alamat');

            $aplikasi = $this->Mod_aplikasi->getAll()->result();
            $no = 1;
            $x = 2;
            foreach($aplikasi as $row)
            {
                $sheet->setCellValue('A'.$x, $no++);
                $sheet->setCellValue('B'.$x, $row->nama_aplikasi);
                $sheet->setCellValue('C'.$x, $row->nama_owner);
                $sheet->setCellValue('D'.$x, $row->tlp);
                $sheet->setCellValue('E'.$x, $row->title);
                $sheet->setCellValue('F'.$x, $row->copy_right);
                $sheet->setCellValue('G'.$x, $row->alamat);
                $x++;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'laporan-Aplikasi';
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
    
            $writer->save('php://output');
        }
   
}
