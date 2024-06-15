
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Aplikasi</h1>
          </div>
          <div class="col-sm-6">
            <div class="text-right">
              <!-- <a href="<?=base_url('aplikasi/download');?>" type="button" class="btn btn-sm btn-outline-info"  title="Download" target="_blank"><i class="fas fa-download"></i> Download</a> -->
              <a href="<?=base_url('aplikasi/backupdb');?>" type="button" class="btn btn-sm btn-outline-warning"  title="Backup" ><i class="fas fa-hdd"></i> Backup Database</a>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<section class="content">
  <div class="container-fluid">
    <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" value="<?=$aplikasi->id?>" name="id"/> 
    <div class="row">
      <div class="col-md-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img  id="v_image" width="100px" height="100px" src="<?php echo base_url('assets/foto/logo/').$aplikasi->logo?>">
              <input type="file" class="form-control btn-file" onchange="loadFile(event)" name="imagefile" id="imagefile" placeholder="Image" value="UPLOAD" >
            </div>
          </div>
        </div>
        <div class="card-footer">
        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary" style="width: 100%;">Submit</button>
      </div>
      </div>
      <div class="col-sm-9">
        <div class="form-group row ">
          <label for="nama_aplikasi" class="col-sm-3 col-form-label">Nama Aplikasi</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="nama_aplikasi" id="nama_aplikasi" placeholder="Nama Aplikasi" value="<?=$aplikasi->nama_aplikasi?>">
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="nama_owner" class="col-sm-3 col-form-label">Nama Owner</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control"  name="nama_owner" id="nama_owner" placeholder="Nama Owner" value="<?=$aplikasi->nama_owner?>" >
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
          <div class="col-sm-9 kosong">
            <textarea  class="form-control" name="alamat" id="alamat" placeholder="Alamat"><?php echo $aplikasi->alamat; ?></textarea> 
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="tlp" class="col-sm-3 col-form-label">No Telp</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="tlp" id="tlp" placeholder="Telpone" value="<?=$aplikasi->tlp?>">
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="title" class="col-sm-3 col-form-label">Title</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?=$aplikasi->title?>">
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="versi" class="col-sm-3 col-form-label">Versi</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="versi" id="versi" placeholder="Title" value="<?=$aplikasi->versi?>" >
            <span class="help-block"></span>
          </div>
        </div>
        <div class="form-group row ">
          <label for="copy_right" class="col-sm-3 col-form-label">Copy Right</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="copy_right" id="copy_right" placeholder="Copy Right" value="<?=$aplikasi->copy_right?>">
            <span class="help-block"></span>
          </div>
        </div>

        <div class="form-group row ">
          <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
          <div class="col-sm-9 kosong">
            <input type="text" class="form-control" name="tahun" id="tahun" placeholder="Nama Aplikasi" value="<?=$aplikasi->tahun?>" >
            <span class="help-block"></span>
          </div>
        </div>
      </div>
    </div>
  </form>
  </div>
</section>





<script type="text/javascript">
var save_method; //for save method string
var table;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});


function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url = "<?php echo site_url('aplikasi/update')?>";
    var formdata = new FormData($('#form')[0]);
    // ajax adding data to database
    $.ajax({
      url : url,
      type: "POST",
      data: formdata,
      dataType: "JSON",
      cache: false,
      contentType: false,
      processData: false,
      success: function(data)
      {

            if(data.status) //if success close modal and reload ajax table
            {
              location.reload();
              Swal.fire({
                icon: 'success',
                title: 'Success!!.',
                showConfirmButton: false,
                timer: 2000
              });
            }
            else
            {
              for (var i = 0; i < data.inputerror.length; i++) 
              {
                $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback');
              }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

          }
        });
  }
  var loadFile = function(event) {
    var image = document.getElementById('v_image');
    image.src = URL.createObjectURL(event.target.files[0]);
  };
</script>

