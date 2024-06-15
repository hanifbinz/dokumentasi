<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class User extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_user','Mod_dashboard'));

    }

    public function index()
    {
        $this->load->helper('url');
        $data['user'] = $this->Mod_user->getAll();
        $data['user_level'] = $this->Mod_user->userlevel();

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
            $this->template->load('layoutbackend', 'admin/user_data', $data);
        }else{
            $data['page']=$link;
            $this->template->load('layoutbackend','admin/akses_ditolak',$data);
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_user->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();
            if ( $user->image!=null) {
               $row[] = "<img class=\"myImgx\"  src=\"assets/foto/user/$user->image\" width=\"100px\" height=\"100px\">";
           }else{
               $row[] = "<img class=\"myImgx\"  src=\"assets/foto/default-150x150.png\" width=\"100px\" height=\"100px\">";
           }
            $row[] = $user->username;
            $row[] = $user->full_name;
            $row[] = $user->nama_level;           
            $row[] = $user->is_active;
            $row[] = "<a class=\"btn btn-xs btn-outline-primary edit\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_user('$user->id_user')\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger delete\" href=\"javascript:void(0)\" title=\"Delete\"  onclick=\"deluser('$user->id_user')\"><i class=\"fas fa-trash\"></i></a>";
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_user->count_all(),
                        "recordsFiltered" => $this->Mod_user->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

     public function insert()
    {
       // var_dump($this->input->post('username'));
        $this->_validate();
        $username = $this->input->post('username');
        $cek = $this->Mod_user->cekUsername($username);
        if($cek->num_rows() > 0){
            echo json_encode(array("error" => "Username Sudah Ada!!"));
        }else{
            $nama = slug($this->input->post('username'));
            $config['upload_path']   = './assets/foto/user/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; //mencegah upload backdor
            $config['max_size']      = '1000';
            $config['max_width']     = '2000';
            $config['max_height']    = '1024';
            $config['file_name']     = $nama; 
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('imagefile')){
            $gambar = $this->upload->data();
            
            $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'password'  => get_hash($this->input->post('password')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active'),
                'image' => $gambar['file_name']
            );

            $this->Mod_user->insertUser("tbl_user", $save);
            echo json_encode(array("status" => TRUE));
            }else{//Apabila tidak ada gambar yang di upload
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'password'  => get_hash($this->input->post('password')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active')
            );
            
            $this->Mod_user->insertUser("tbl_user", $save);
            echo json_encode(array("status" => TRUE));
            }
        }
    }

    public function viewuser()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['data_table'] = $this->Mod_user->view_user($id)->result_array();
            $this->load->view('admin/view', $data);
        
    }

    public function edituser($id)
    {
            
            $data = $this->Mod_user->getUser($id);
            echo json_encode($data);
        
    }


    public function update()
    {
        $nip = $this->input->post('nip');
        if(!empty($_FILES['imagefile']['name'])) {
        // $this->_validate();
        $id = $this->input->post('id_user');
        
        $nama = slug($this->input->post('username'));
        $config['upload_path']   = './assets/foto/user/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; //mencegah upload backdor
        $config['max_size']      = '1000';
        $config['max_width']     = '2000';
        $config['max_height']    = '1024';
        $config['file_name']     = $nama; 
        
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('imagefile')){
            $gambar = $this->upload->data();
            //Jika Password tidak kosong
            if ($this->input->post('password')!="") {
                    $save  = array(
                    'username' => $this->input->post('username'),
                    'full_name' => ucwords($this->input->post('full_name')),
                    'password'  => get_hash($this->input->post('password')),
                    'id_level'  => $this->input->post('level'),
                    'is_active' => $this->input->post('is_active'),
                    'id_cabang' => $this->input->post('id_cabang'),
                    'image' => $gambar['file_name']
                );
            }else{//Jika password kosong
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active'),
                'image' => $gambar['file_name']
                );
            }
            
            
            $g = $this->Mod_user->getImage($id)->row();

            if (!empty($g->image)) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/user/'.$g->image);
            }
            
            
            $this->Mod_user->updateUser($id, $save);
            echo json_encode(array("status" => TRUE));
            }else{//Apabila tidak ada gambar yang di upload

                 //Jika Password tidak kosong
            if ($this->input->post('password')!="") {
                    $save  = array(
                    'username' => $this->input->post('username'),
                    'full_name' => ucwords($this->input->post('full_name')),
                    'password'  => get_hash($this->input->post('password')),
                    'id_level'  => $this->input->post('level'),
                    'is_active' => $this->input->post('is_active')
                );
            }else{//Jika password kosong
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active')
                );
            }
             
           
                $this->Mod_user->updateUser($id, $save);
                echo json_encode(array("status" => TRUE));
            }
        }else{
            // $this->_validate();
            $id_user = $this->input->post('id_user');
            if ($this->input->post('password')!="") {
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'password'  => get_hash($this->input->post('password')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active')
                );
            }else{
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => ucwords($this->input->post('full_name')),
                'id_level'  => $this->input->post('level'),
                'is_active' => $this->input->post('is_active')
                );
            }
             $level=$this->input->post('level');
            
            $this->Mod_user->updateUser($id_user, $save);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete(){
        $id = $this->input->post('id');
        $g = $this->Mod_user->getImage($id)->row();
        if (!empty($g->image)) {
            //hapus gambar yg ada diserver
            unlink('assets/foto/user/'.$g->image);
        }
        $this->Mod_user->deleteUsers($id, 'tbl_user');
        $data['status'] = TRUE;
        echo json_encode($data);
    }

    public function reset(){
        $id = $this->input->post('id');
        $data = array(
            'password'  => get_hash('123456')
        );
        $this->Mod_user->reset_pass($id, $data);
        $data['status'] = TRUE;
        echo json_encode($data);
    }

    public function ganti_password()
    {

        $id_user = $this->session->userdata['id_user'];
        $db = $this->Mod_user->check_db_user($id_user)->row();
        if(hash_verified(anti_injection($this->input->post('p_lama')), $db->password)) {
            $data = array(
                'password'  => get_hash($this->input->post('p_baru'))
            );
            $this->Mod_user->updateUser($id_user, $data);
            $data['status'] = TRUE;
            echo json_encode($data);
        }else{
            $data['pesan'] = "Password Yang Lama Salah!!";
            $data['status'] = FALSE;
            echo json_encode($data);
        }
        
    }
    public function download()
        {
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Username');
            $sheet->setCellValue('C1', 'Full name');
            $sheet->setCellValue('D1', 'password');
            $sheet->setCellValue('E1', 'level');
            $sheet->setCellValue('F1', 'Image');
            $sheet->setCellValue('G1', 'Active');

            $user = $this->Mod_user->getAll()->result();
            $no = 1;
            $x = 2;
            foreach($user as $row)
            {
                $sheet->setCellValue('A'.$x, $no++);
                $sheet->setCellValue('B'.$x, $row->username);
                $sheet->setCellValue('C'.$x, $row->full_name);
                $sheet->setCellValue('D'.$x, $row->password);
                $sheet->setCellValue('E'.$x, $row->nama_level);
                $sheet->setCellValue('F'.$x, $row->image);
                $sheet->setCellValue('F'.$x, $row->is_active);
                $x++;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'laporan-User';
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
            header('Cache-Control: max-age=0');
    
            $writer->save('php://output');
        }


    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('username') == '')
        {
            $data['inputerror'][] = 'username';
            $data['error_string'][] = 'Username is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('full_name') == '')
        {
            $data['inputerror'][] = 'full_name';
            $data['error_string'][] = 'Full Name is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('password') == '')
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Password is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('is_active') == '')
        {
            $data['inputerror'][] = 'is_active';
            $data['error_string'][] = 'Please select Is Active';
            $data['status'] = FALSE;
        }

        if($this->input->post('level') == '')
        {
            $data['inputerror'][] = 'level';
            $data['error_string'][] = 'Please select is level';
            $data['status'] = FALSE;
        }

        /*if($this->input->post('image') == '')
        {
            $data['inputerror'][] = 'image';
            $data['error_string'][] = 'Image is required';
            $data['status'] = FALSE;
        }*/

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
