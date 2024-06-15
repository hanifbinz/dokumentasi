
<!-- Main content -->
<section class="content" id="load_detail">
  <div class="container-fluid row">
    <div class="col-md-6">
      <div class="card card-default">
        <div class="card-header bg-light">
          <h3 class="card-title"><i class="fa fa-list text-blue"></i> Laporan Bongkar</h3>
        </div>
        <div class="card-body">
          <form class="form" id="form_bongkar" action="<?php echo base_url('laporan/laporan_bongkar') ?>" method="post">
            <div class="form-group row">
              <input type="hidden" name="aksi" id="aksi_bongkar">
              <div class="col-md-12">
                <div class="input-group input-group-sm">
                  <label class="aria-label col-md-5">Range Tanggal</label>
                  <input type="text" class="form-control  " name="tgl" id="reservation1">
                </div>
              </div>
            </div>
            <div class="form-group row">
             <div class="col-md-12">
              <div class="input-group input-group-sm">
                <label class="aria-label col-md-5">Angkutan</label>
                <div class="col-md-7">
                  <select class="form-control select2 " name="id_angkutan"  >
                    <option selected="" value="" >Pilih</option>
                    <?php foreach ($angkutan as $key => $v): ?>
                      <option value="<?=$v->id_angkutan?>" ><?php echo $v->nama_angkutan; ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
           <div class="col-md-12">
            <div class="input-group input-group-sm">
              <label class="aria-label col-md-5">Nama Barang</label>
              <div class="col-md-7">
                <select class="form-control select2 " name="id_barang"  >
                  <option selected="" value="" >Pilih</option>
                  <?php foreach ($barang as $key => $v): ?>
                    <option value="<?=$v->id_barang?>" ><?php echo $v->nama_barang; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group input-group-sm">
          <button type="button" class="btn btn-primary btn-xs" onclick="tampil_bongkar()">Tampilkan</button>
          <button type="button" class="btn btn-primary btn-xs" onclick="cetak_bongkar()">Cetak</button>
          <button class="btn btn-success btn-xs" type="submit" >Export Excel <i class="fa fa-file-excel"></i></button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="col-md-6">
  <div class="card card-default">
    <div class="card-header bg-light">
      <h3 class="card-title"><i class="fa fa-list text-blue"></i> Laporan Muat</h3>
    </div>
    <div class="card-body">
      <form class="form" id="form_muat" action="<?php echo base_url('laporan/laporan_muat') ?>" method="post">
        <div class="form-group row">
          <input type="hidden" name="aksi" id="aksi_muat">
          <div class="col-md-12">
            <div class="input-group input-group-sm">
              <label class="aria-label col-md-5">Range Tanggal</label>
              <input type="text" class="form-control  " name="tgl" id="reservation">
            </div>
          </div>
        </div>

        <div class="form-group row">
         <div class="col-md-12">
          <div class="input-group input-group-sm">
            <label class="aria-label col-md-5">Angkutan</label>
            <div class="col-md-7">
              <select class="form-control select2 " name="id_angkutan"  >
                <option selected="" value="" >Pilih</option>
                <?php foreach ($angkutan as $key => $v): ?>
                  <option value="<?=$v->id_angkutan?>" ><?php echo $v->nama_angkutan; ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
       <div class="col-md-12">
        <div class="input-group input-group-sm">
          <label class="aria-label col-md-5">Nama Barang</label>
          <div class="col-md-7">
            <select class="form-control select2 " name="id_barang"  >
              <option selected="" value="" >Pilih</option>
              <?php foreach ($barang as $key => $v): ?>
                <option value="<?=$v->id_barang?>" ><?php echo $v->nama_barang; ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group row">
       <div class="col-md-12">
        <div class="input-group input-group-sm">
          <label class="aria-label col-md-5">Nama Customer</label>
          <div class="col-md-7">
            <select class="form-control select2 " name="id_customer"  >
              <option selected="" value="" >Pilih</option>
              <?php foreach ($customer as $key => $v): ?>
                <option value="<?=$v->id_customer?>" ><?php echo $v->nama_customer; ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group input-group-sm">
      <button type="button" class="btn btn-primary btn-xs" onclick="tampil_muat()">Tampilkan</button>
      <button type="button" class="btn btn-primary btn-xs" onclick="cetak_muat()">Cetak</button>
      <button class="btn btn-success btn-xs" onclick="exp_xls_bongkar()">Export Excel <i class="fa fa-file-excel"></i></button>
    </div>

  </form>

</div>
</div>
</div>
</div>
<!-- /.container-fluid -->
</section>


<section class="content">
  <div class="container-fluid">
    <div class="card card-default">
      <div class="card-body">
        <div id="tampil"></div>
      </div>
    </div>
  </div>
</section>

<script >

  function cetak_bongkar() {
    $('#aksi_bongkar').val('cetak');
    $.ajax({
      url : 'laporan/laporan_bongkar',
      data : $('#form_bongkar').serialize(),
      type : 'post',
      dataType : 'html',
      success : function (respon) {
        var doc = window.open();
        doc.document.write(respon);
        doc.print();
      }
    })
  }
  function cetak_bongkar() {
    $('#aksi_bongkar').val('xls');
    $.ajax({
      url : 'laporan/laporan_bongkar',
      data : $('#form_bongkar').serialize(),
      type : 'post',
      dataType : 'html',
      success : function (respon) {
        var doc = window.open();
        doc.document.write(respon);
        doc.print();
      }
    })
  }

    function tampil_bongkar() {
    $('#aksi_bongkar').val('');
    $.ajax({
      url : 'laporan/laporan_bongkar',
      data : $("#form_bongkar").serialize(),
      dataType: "html",
      type: "POST",
      success : function (response) {

       $('#tampil').html(response);
     }
   })
  }

    function cetak_muat() {
    $('#aksi_muat').val('cetak');
    $.ajax({
      url : 'laporan/laporan_muat',
      data : $('#form_muat').serialize(),
      type : 'post',
      dataType : 'html',
      success : function (respon) {
        var doc = window.open();
        doc.document.write(respon);
        doc.print();
      }
    })
  }
  function cetak_muat() {
    $('#aksi_muat').val('xls');
    $.ajax({
      url : 'laporan/laporan_muat',
      data : $('#form_muat').serialize(),
      type : 'post',
      dataType : 'html',
      success : function (respon) {
        var doc = window.open();
        doc.document.write(respon);
        doc.print();
      }
    })
  }

    function tampil_muat() {
    $('#aksi_muat').val('');
    $.ajax({
      url : 'laporan/laporan_muat',
      data : $("#form_muat").serialize(),
      dataType: "html",
      type: "POST",
      success : function (response) {

       $('#tampil').html(response);
     }
   })
  }

  $(function () {
   $('.select2').select2();

 })



 //Date range picker
  $('#reservation1').daterangepicker()
  $('#reservation').daterangepicker()

</script>

