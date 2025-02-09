
<?php 
$apl = $this->db->get("aplikasi")->row();
$id = $this->session->userdata('id_user');
$user=$this->db->where('id_user',$id);
$user=$this->db->get("tbl_user")->row();
$idlevel  = $this->session->userdata['id_level'];

?>
<!-- main-header navbar navbar-expand navbar-default navbar-dark navbar-cyan -->
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-default navbar-dark navbar-cyan">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Home</a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- SEARCH FORM -->
   <!--  <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    
      <li class="nav-item">
        <a href="#" class="nav-link">
          <div class="user-panel">
        <!-- <div class="image"> -->
          <?php if (isset($user->image)) {?>
            <img src="<?php echo base_url();?>assets/foto/user/<?php echo $user->image;?>" class="img-circle" alt="User Image">
          <?php }else{ ?>
            <img  id="pasfotox" width="100px" height="140px" border="2"  src="<?php echo base_url("assets/foto/logo/$apl->logo");?>">
          <?php } ?>
          
          <?php echo $this->session->userdata['full_name']; ?>
        </div>
      <!-- </div> -->
        
        </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('login/logout') ?>" role="button">
          <i class="fas fa-sign-out-alt" title="Sign Out" ></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-cyan  elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url('dashboard'); ?>" class="brand-link navbar-cyan">

      <img src="<?php echo base_url();?>assets/foto/logo/<?php echo $apl->logo; ?>" alt="<?php echo $apl->title; ?>" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo  $apl->title; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar ">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if (isset($user->image)) {?>
            <img src="<?php echo base_url();?>assets/foto/user/<?php echo $user->image;?>" class="img-circle elevation-2" alt="User Image">
          <?php }else{ ?>
            <img  id="pasfotox" width="100px" height="140px" border="2"  src="<?php echo base_url("assets/foto/logo/$apl->logo");?>">
          <?php } ?>
          
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $this->session->userdata['full_name']; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      <?php
            // data main menu
      $idlevel  = $this->session->userdata['id_level'];
      $main_menu = $this->db->select('b.nama_menu,b.icon,b.link,b.id_menu');
      $main_menu =$this->db->join('tbl_menu b', 'a.id_menu=b.id_menu');
      $main_menu =$this->db->join('tbl_userlevel c', 'a.id_level=c.id_level' );
      $main_menu =$this->db->where('a.id_level',$idlevel);
      $main_menu =$this->db->where('a.view','Y');
      $main_menu = $this->db->where('b.is_active', 'Y');
      $main_menu =$this->db->order_by('urutan ASC');
      $main_menu =$this->db->get('tbl_akses_menu a');
      foreach ($main_menu->result() as $main) {
        $idlevel  = $this->session->userdata['id_level'];
        
        $sub_menu = $this->db->join('tbl_submenu b','a.id_submenu=b.id_submenu');
        $sub_menu = $this->db->where('a.id_level', $idlevel);
        $sub_menu = $this->db->where('b.id_menu', $main->id_menu);
        $sub_menu = $this->db->where('a.view', 'Y');
        $sub_menu = $this->db->where('b.is_active', 'Y');
        $sub_menu = $this->db->order_by('b.urutan', 'ASC');
        $sub_menu = $this->db->get('tbl_akses_submenu a');
       
        if ($sub_menu->num_rows() > 0) {
          $segmen   = $this->uri->segment(1);
          $submenu = $this->db->select('link');
          $submenu = $this->db->where('id_menu', $main->id_menu);
          $submenu = $this->db->where('link', $segmen);
          $submenu = $this->db->where('is_active', 'Y');
          $submenu = $this->db->get('tbl_submenu');
          $link='';
          if ($submenu->num_rows() > 0) {
            $sub = $submenu->row();
            $link = $sub->link;
          }
        ?>
          <li class="nav-item has-treeview <?=$this->uri->segment(1) == $link ? 'menu-open' : '' ?>">

            <a href="<?=$main->link;?>" <?=$this->uri->segment(1) == $link ? 'class="nav-link active"' : 'class="nav-link"' ?> >
              <i class="nav-icon <?=$main->icon?>"></i>
              <p>
                <?php echo $main->nama_menu; ?>
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php foreach ($sub_menu->result() as $sub): ?>
              <li class="nav-item">
                <a href="<?=$sub->link;?>"  <?=$this->uri->segment(1) == $sub->link ? 'class="nav-link active"' : 'class="nav-link"' ?> >
                  <i class="<?=$sub->icon;?> nav-icon"></i>
                  <p><?php echo $sub->nama_submenu; ?></p>
                </a>
              </li>
            <?php endforeach; ?>
            </ul>
          
          </li>
        <?php }else{ ?>
          <li class="nav-item">
            <a href="<?=$main->link;?>" <?=$this->uri->segment(1) == $main->link ? 'class="nav-link active"' : 'class="nav-link"' ?>>
              <i class="nav-icon fas <?=$main->icon?>"></i>
              <p>
                <?php echo $main->nama_menu; ?>
              </p>
            </a>
          </li>
          <?php } } ?>
            <li class="nav-item">
              <a href="<?=base_url('login/logout')?>" class="nav-link">
              <i class="nav-icon fas  fa-sign-out-alt text-bold"></i>
                <p>Sign out</p>
              </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


<script type="text/javascript">

function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57))

    return false;
  return true;
}
        

</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
