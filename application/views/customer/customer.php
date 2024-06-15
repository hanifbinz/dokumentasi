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
                        <table id="tbl_customer" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-info">
                                    <th>No</th>
                                    <th>Id Customer</th>
                                    <th>Nama Customer</th>
                                    <th>Alamat Customer</th>
                                    <th>Email Customer</th>

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
        table = $("#tbl_customer").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sEmptyTable": "Data customer Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('customer/ajax_list') ?>",
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
                    url: "<?php echo site_url('customer/delete'); ?>",
                    type: "POST",
                    data: "id_customer=" + id,
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
        $('[name="id_customer"]').val('').attr('readonly', false);
        $('#modal_form').modal({
            backdrop: 'static',
            keyboard: false
        }); // show bootstrap modal
        $('.modal-title').text('Add customer'); // Set Title to Bootstrap modal title
    }

    function edit(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('customer/edit') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                // $('[name="id"]').val(data.id);
                $('[name="id_customer"]').val(data.id_customer).attr('readonly', true);
                $('[name="nama_customer"]').val(data.nama_customer);
                $('[name="email_customer"]').val(data.email_customer);
                $('[name="alamat_customer"]').val(data.alamat_customer);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit customer'); // Set title to Bootstrap modal title

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
            url = "<?php echo site_url('customer/insert') ?>";
        } else {
            url = "<?php echo site_url('customer/update') ?>";
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
                    validasi_form(data);
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
    <div class="modal-dialog">
        <div class="modal-content ">

            <div class="modal-header">
                <h3 class="modal-title">customer Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Id Customer</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" autofocus name="id_customer" id="id_customer" placeholder="ID customer">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Nama Customer</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" name="nama_customer" id="nama_customer" placeholder="Nama Customer">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Email customer</label>
                                    <div class="col-sm-8 kosong">
                                        <input type="text" class="form-control" name="email_customer" id="email_customer" placeholder="Email Customer">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="nama" class="col-sm-4 col-form-label">Alamat</label>
                                    <div class="col-sm-8 kosong">
                                        <textarea class="form-control" name="alamat_customer"></textarea>
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