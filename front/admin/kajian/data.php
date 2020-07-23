<style type="text/css">
    @media (min-width: 768px) {
      .modal-xl {
        width: 80%;
        max-width:1200px;
      }
    }
</style>
<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Data Kajian</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button>
    </div>
  </div>
  <div class="card-body">
    <button class="btn btn-success" onclick="add_data()"><i class="fa fa-plus"></i> Tambah Kajian</button>
    <div class="table-responsive" style="margin-top: 15px;">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-md-12">Filter Ustadz</label>
                    <div class="col-md-12">
                        <select name="fustadz_id" id="fustadz" class="form-control">
                            <option value="">-Pilih Ustadz-</option>
                            <?php foreach ($ustadz as $u) : ?>
                                <option value="<?= $u['id'] ?>"><?= $u['nama'] ?></option>                                        
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-md-12">Filter Kategori</label>
                    <div class="col-md-12">
                        <select name="fustadz_id" id="fkategori" class="form-control">
                            <option value="">-Pilih Kategori-</option>
                            <?php foreach ($kategori as $k) : ?>
                                <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>                                        
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="pesan" style="margin: 10px 5px;"></div>
        <table id="table" class="table table-striped table-bordered bulk_action">
            <thead>
            <tr>
                <th width="10">NO</th>
                <th>Judul Kajian</th>
                <th>Dilihat</th>
                <th width="150">Action</th>
            </tr>
            </thead>

            <tbody>
            
            </tbody>
        </table>
    </div>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    
  </div>
<!-- /.card-footer-->
</div>
<!-- /.card -->

<script type="text/javascript">
 
var save_method; //for save method string
var table;
 
$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('admin/kajian/ajax_list')?>",
            "type": "POST",
            "data": function ( data ) {
                data.ustadz_id = $('#fustadz').val();
                data.kategori_id = $('#fkategori').val();
            }
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
 
    });

    $('#fustadz').change(function(){
        table.ajax.reload();  //just reload table
    });

    $('#fkategori').change(function(){
        table.ajax.reload();  //just reload table
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});
 
 
 
function add_data()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.textarea').summernote('code', '');
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}
 
function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
 
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('admin/kajian/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
 
            $('[name="id"]').val(data.id);
            $('[name="judul"]').val(data.judul);
            $('.textarea').summernote('code', data.deskripsi);
            $('[name="tags"]').val(data.tags);
            $('[name="url"]').val(data.url);
            $('[name="kategori_id"]').val(data.kategori_id);
            $('[name="ustadz_id"]').val(data.ustadz_id);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Ubah Data'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
 
function save()
{
    $('#btnSave').text('Menyimpan...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
 
    if(save_method == 'add') {
        url = "<?php echo site_url('admin/kajian/ajax_add')?>";
    } else {
        url = "<?php echo site_url('admin/kajian/ajax_update')?>";
    }
 
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
 
          if(data.status) //if success close modal and reload ajax table
          {
              $('#modal_form').modal('hide');
              reload_table();
              document.getElementById('pesan').innerHTML = data.pesan;
              setTimeout(function(){ $('#pesan').empty(); }, 3000);
          }
          else
          {
              for (var i = 0; i < data.inputerror.length; i++) 
              {
                  $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                  $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
              }
          }
 
          $('#btnSave').text('Simpan'); //change button text
          $('#btnSave').attr('disabled',false); //set button enable 
 
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error adding / update data');
          $('#btnSave').text('Simpan'); //change button text
          $('#btnSave').attr('disabled',false); //set button enable 
 
        }
    });
}
 
function delete_data(id)
{
    if(confirm('Hapus data ini?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('admin/kajian/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
                document.getElementById('pesan').innerHTML = data.pesan;
                setTimeout(function(){ $('#pesan').empty(); }, 3000);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
 
    }
}
 
</script>

<!-- Modal -->
<div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form class="form-horizontal" method="POST" id="form">
          <div class="modal-body">
            <input type="hidden" value="" name="id"/>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-md-12">Judul Kajian</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="judul"> 
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Deskripsi</label>
                        <div class="col-md-12">
                            <textarea name="deskripsi" id="deskripsi" class="textarea"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-md-12">URL Youtube</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="url"> 
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kategori</label>
                        <div class="col-md-12">
                            <select name="kategori_id" class="form-control">
                                <option value="">-Pilih Kategori-</option>
                                <?php foreach ($kategori as $k) : ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>                                        
                                <?php endforeach; ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Ustadz</label>
                        <div class="col-md-12">
                            <select name="ustadz_id" class="form-control">
                                <option value="">-Pilih Ustadz-</option>
                                <?php foreach ($ustadz as $u) : ?>
                                    <option value="<?= $u['id'] ?>"><?= $u['nama'] ?></option>                                        
                                <?php endforeach; ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tags</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="tags"> <!-- data-role="tagsinput" -->
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
          </div>
      </form>
    </div>
  </div>
</div>