<?php 
$segmen   = $this->uri->segment(1);
$menu1 = $this->db->select('nama_menu');
$menu1 = $this->db->where('link', $segmen);
$menu1 = $this->db->get('tbl_menu');



if ($menu1->num_rows() > 0) {
  $menu = $this->db->select('nama_menu as menu');
  $menu = $this->db->where('link', $segmen);
  $menu = $this->db->get('tbl_menu')->row();
  $namamenu=$menu->menu;
  $data['namamenu']=$namamenu;
}else{
  $submenu = $this->db->select('nama_submenu as menu');
  $submenu = $this->db->where('link', $segmen);
  $submenu = $this->db->get('tbl_submenu')->row();
  $namamenu=$submenu->menu;
  $data['namamenu']=$namamenu;
}
?>
<!-- header -->
<?php 
$this->load->view('templates/header',$data);

?>
<!-- end header -->

<!-- menu -->
<?php 
$this->load->view('templates/menu'); 

?>
<!-- end menu -->

<?php if ($akses_menu->add=='N') {?>
  <style type="text/css">
    .add {
      display: none;
    }
  </style>
<?php } ?>
<?php if ($akses_menu->edit=='N') {?>
  <style type="text/css">
    .edit {
      display: none;
    }
  </style>
<?php } ?>
<?php if ($akses_menu->delete=='N') {?>
 <style type="text/css">
    .delete {
      display: none;
    }
  </style>
<?php } ?>
<?php if ($akses_menu->print=='N') {?>
 <style type="text/css">
    .print {
      display: none;
    }
  </style>
<?php } ?>
<?php if ($akses_menu->upload=='N') {?>
 <style type="text/css">
    .upload {
      display: none;
    }
  </style>
<?php } ?>
<?php if ($akses_menu->download=='N') {?>
 <style type="text/css">
    .download {
      display: none;
    }
  </style>
<?php } ?>
    <!-- Content Header (Page header) -->
    <section class="content-header " style="opacity: 0.8; ">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
             <h4><?php echo $namamenu; ?></h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
               <li class="breadcrumb-item active"><?php echo $namamenu; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
<!-- page-wrapper -->

    <!-- Main content -->
		
      <?php 
        $this->load->view('templates/footer'); 
        ?>
      <?php echo $contents; ?>

  <!--   <script type="text/javascript">
      $(document).ready(function () {
        $("input").change(function(){
          $(this).parent().parent().removeClass('has-error');
          $(this).next().empty();
          $(this).removeClass('is-invalid');
        });
        $("textarea").change(function(){
          $(this).parent().parent().removeClass('has-error');
          $(this).next().empty();
          $(this).removeClass('is-invalid');
        });
        $("select").change(function(){
          $(this).parent().parent().removeClass('has-error');
          $(this).next().empty();
          $(this).removeClass('is-invalid');
        });
      })
    </script> -->
    <!-- </section> -->
<!-- /# end page-wrapper -->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- /#wrapper -->
        <!-- footer -->
<?php 

$aplikasi = $this->db->get("aplikasi")->row();
?>
<footer class="main-footer navbar-default">
<strong>Copyright &copy; <?php echo $aplikasi->tahun; ?> <a href="#"><?php  echo $aplikasi->nama_owner; ?></a>.</strong>
All rights reserved.
<div class="float-right d-none d-sm-inline-block">
  <b>Version</b> <?php echo $aplikasi->versi; ?>
</div>
</footer>
<!-- end footer -->

</body>

</html>
