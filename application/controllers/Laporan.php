<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model(array('Mod_laporan'));
        // $this->load->model('dashboard/Mod_dashboard');
  }

  public function index()
  {
    $link = $this->uri->segment(1);
    $level = $this->session->userdata['id_level'];
        // Cek Posisi Menu apakah Sub Menu Atau bukan
    $jml = $this->Mod_dashboard->get_akses_menu($link,$level)->num_rows();
    $data['barang'] = $this->Mod_laporan->barang()->result();
    $data['angkutan'] = $this->Mod_laporan->angkutan()->result();
    $data['customer'] = $this->Mod_laporan->customer()->result();
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
          $this->template->load('layoutbackend','laporan/laporan',$data);
        }else{
          $data['page']=$link;
          $this->template->load('layoutbackend','admin/akses_ditolak',$data);
        }
      }



      public function laporan_bongkar()
      {
        $id_angkutan=$this->input->post('id_angkutan');
        $tglrange =$this->input->post('tgl');
        $id_barang =$this->input->post('id_barang');
        
        $data['tgl'] = $this->input->post('tgl');
        $data['act'] = $this->input->post('aksi');        
        $data['lap'] = $this->Mod_laporan->get_laporan_bongkar($tglrange,$id_barang,$id_angkutan);
        $this->load->view('laporan/lap_bongkar',$data);
      }

     public function laporan_muat()
      {
        $id_angkutan=$this->input->post('id_angkutan');
        $tglrange =$this->input->post('tgl');
        $id_barang =$this->input->post('id_barang');
        $id_customer =$this->input->post('id_customer');
        $data['act'] = $this->input->post('aksi');
        $data['tgl'] = $this->input->post('tgl');
        $data['lap'] = $this->Mod_laporan->get_laporan_muat($tglrange,$id_barang,$id_customer,$id_angkutan);
        $this->load->view('laporan/lap_muat',$data);
      }

      public function lap_excel()
      {
        $jenis_cetakan=$this->input->post('jenis_cetakan');
        $tglrange =$this->input->post('tgl');
        $id_pelanggan =$this->input->post('id_pelanggan');
        $no_invoice =$this->input->post('no_invoice');
        $data['act'] = 'xls';
        $data['lap'] = $this->Mod_laporan->get_laporan_penjualan($tglrange,$id_pelanggan,$jenis_cetakan,$no_invoice);
        
        // $spreadsheet = new Spreadsheet();
        /*$sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Invoice');
        $sheet->setCellValue('C1', 'Pelanggan');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Jenis Cetakan');
        $sheet->setCellValue('F1', 'Jumlah');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Diskon');
        $sheet->setCellValue('I1', 'Subtotal');
        // $sheet->setCellValue('J1', 'Subtotal');
        $no = 1;
        $x = 2;
        foreach($list as $row)
        {
          $sheet->setCellValue('A'.$x, $no++);
          $sheet->setCellValue('B'.$x, $row->no_invoice);
          $sheet->setCellValue('C'.$x, $row->nama_pelanggan);
          $sheet->setCellValue('D'.$x, date("d/m/Y", strtotime($row->waktu)));
          $sheet->setCellValue('E'.$x, $row->cetakan);
          $sheet->setCellValue('F'.$x, $row->jumlah);
          $sheet->setCellValue('G'.$x, $row->harga);
          $sheet->setCellValue('H'.$x, $row->diskon);
          $sheet->setCellValue('I'.$x, $row->total_harga);
          // $sheet->setCellValue('J'.$x, $subt);
          $x++;
        }*/
        // $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_penjualan';
        $this->load->view('laporan/view_lap_penjualan',$data);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
        header('Cache-Control: max-age=0');

        // $writer->save('php://output');
      }

      public function get_invoice()
      {
        $term = $this->input->get('term');
        $data = $this->Mod_laporan->get_invoice($term)->result();
        if (count($data) > 0) {

          foreach ($data as $row){
            $arr_result[] = array( 'value' => $row->no_invoice, 'label'  => $row->no_invoice,  );
          } 
          echo json_encode($arr_result);
        }else{
          $arr_result = array( 'label'  => "Invoice Tidak di Temukan" );
          echo json_encode($arr_result);
        }
      }
    }