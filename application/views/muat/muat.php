<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">

                        <button type="button" class="btn btn-sm btn-outline-primary  add" onclick="add()" title="Tambah Data"><i class="fas fa-plus"></i> Tambah</button>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tbl_muat" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-info">
                                    <th>No</th>
                                    <th>Uploader</th>
                                    <th>Tanggal</th>
                                    <th>Nama Angkutan</th>
                                    <th>No Mobil</th>
                                    <th>Nama Barang</th>
                                    <th>Jml Barang</th>
                                    <th>Customer</th>
                                    <th>No DO</th>
                                    <th><i class="fas fa-truck"></i></th>
                                    <th><i class="fas fa-key"></i></th>
                                    <th><i class="fas fa-book"></i></th>
                                    <th><i class="fas fa-box"></i></th>
                                    <th><i class="fas fa-box"></i></th>
                                    <th><i class="fas fa-box"></i></th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>


<script type="text/javascript">
    var save_method; //for save method string
    var table;

    $(document).ready(function() {
        $('.select2').select2();
        setTimeout(function() {
            $('input[name="barcode"]').focus()
        }, 3000);
        //datatables
        table = $("#tbl_muat").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sEmptyTable": "Data muat Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('muat/ajax_list') ?>",
                "type": "POST"
            },

        });

        //set input/textarea/select event when change value, remove class error and remove text help block 
        $("input").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
            $(this).removeClass('is-invalid');
        });
        $("textarea").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
            $(this).removeClass('is-invalid');
        });
        $("select").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().next().empty();
            $(this).removeClass('is-invalid');
        });

    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });


    //delete
    function hapus(id) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('muat/delete'); ?>",
                    type: "POST",
                    data: "id=" + id,
                    cache: false,
                    dataType: 'json',
                    success: function(respone) {
                        if (respone.status == true) {
                            reload_table();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Data Berhasil Dihapus.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Delete Error!!.'
                            });
                        }
                    }
                });
            }
        })
    }



    function add() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal({
            backdrop: 'static',
            keyboard: false
        }); // show bootstrap modal
        $('.modal-title').text('Add Muat'); // Set Title to Bootstrap modal title
    }

    function edit(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('muat/edit') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                $('[name="id"]').val(data.id_muat);
                $('[name="tanggal"]').val(data.tanggal);
                $('[name="id_angkutan"]').val(data.id_angkutan).select2();
                $('[name="no_mobil"]').val(data.no_mobil);
                $('[name="jumlah_barang"]').val(data.jumlah_barang);
                $('[name="no_do"]').val(data.no_do);
                $('[name="id_barang"]').val(data.id_barang).select2();
                $('[name="id_customer"]').val(data.id_customer).select2();

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Muat'); // Set title to Bootstrap modal title

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        if (save_method == 'add') {
            url = "<?php echo site_url('muat/insert') ?>";
        } else {
            url = "<?php echo site_url('muat/update') ?>";
        }
        var formdata = new FormData($('#form')[0]);
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {

                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                    Toast.fire({
                        icon: 'success',
                        title: 'Success!!.'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.pesan,
                        icon: 'error',
                        showConfirmButton: true,
                    });
                    /* for (var i = 0; i < data.inputerror.length; i++) 
                     {
                         $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                         $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback');
                     }*/
                }
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 

            }
        });
    }
    var loadFile = function(event) {
        var image = document.getElementById('vbarcode');
        image.src = URL.createObjectURL(event.target.files[0]);
    };

    function hanyaAngka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;
        return true;
    }
</script>



<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog" tabindex="-1" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">

            <div class="modal-header">
                <h3 class="modal-title">muat Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Tanggal</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="date" class="form-control" autofocus name="tanggal" id="tanggal">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Angkutan</label>
                                    <div class="col-sm-8 kosong">
                                        <select class="form-control select2" name="id_angkutan">
                                            <?php foreach ($angkutan as $key => $val) : ?>
                                                <option value="<?= $val->id_angkutan ?>"><?php echo $val->nama_angkutan; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Customer</label>
                                    <div class="col-sm-8 kosong">
                                        <select class="form-control select2" name="id_customer">
                                            <?php foreach ($customer as $key => $v) : ?>
                                                <option value="<?= $v->id_customer ?>"><?php echo $v->nama_customer; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">No Mobil</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" name="no_mobil" id="no_mobil" placeholder="No Mobil">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Barang</label>
                                    <div class="col-sm-8 kosong">

                                        <select class="form-control select2" name="id_barang">
                                            <?php foreach ($barang as $key => $val) : ?>
                                                <option value="<?= $val->id_barang ?>"><?php echo $val->nama_barang; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Jumlah Barang</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" name="jumlah_barang" id="jumlah_barang" placeholder="Jumlah Barang">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">No DO</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" name="no_do" id="no_do" placeholder="No DO">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto Mobil</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_mobil" id="foto_mobil" placeholder="Foto Mobil">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto Bak</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_bak" id="foto_bak">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto DO</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_do" id="foto_do">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto Barang1</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_barang1" id="foto_barang1">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto Barang2</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_barang2" id="foto_barang2">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Foto Barang3</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="file" class="form-control" name="foto_barang3" id="foto_barang3">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->