<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3>Ganti Password</h3>
          </div>
          <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="card-body">
                    <div class="form-group row ">
                      <label for="p_lama" class="col-sm-5 col-form-label">Password Lama</label>
                      <div class="col-sm-9 kosong">
                        <input type="password" class="form-control" name="p_lama" id="p_lama" placeholder="Password Lama" value="">
                      </div>
                    </div>
                    <div class="form-group row ">
                      <label for="password" class="col-sm-5 col-form-label">Password Baru</label>
                      <div class="col-sm-9 kosong">
                        <input type="password" class="form-control " name="p_baru" id="p_baru" placeholder="Password Baru">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
              <a href="<?php echo site_url('dashboard'); ?>" type="button" class="btn btn-danger" >Cancel</a>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
</section>

<script type="text/javascript">
  var loadFile = function(event) {
    var image = document.getElementById('v_image');
    image.src = URL.createObjectURL(event.target.files[0]);
  };
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
    var 
    url = "<?php echo site_url('user/ganti_password')?>";
    
    var formdata = new FormData($('#form')[0]);
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
              var url = "<?php echo base_url('login/logout') ?>";
              window.location.href = url;
              Toast.fire({
                icon: 'success',
                title: 'Success!!.'
              });

            }
            else
            {
             toastr.error(
              data.pesan
              );
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert(textStatus);
            // alert('Error adding / update data');
            Toast.fire({
              icon: 'error',
              title: 'Error!!.'
            });
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

          }
        });
  }
</script>