<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Ubah Password</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button>
    </div>
  </div>
  <div class="card-body">
    <?php
        $info = $this->session->flashdata('info');
        if (!empty($info)) {
            echo $info;
        }
    ?>
    <form id="change-pass" action="<?= site_url('admin/auth/changePassword') ?>" method="POST">
        <input type="hidden" name="users_id" value="<?= $this->session->userdata('id') ?>">
        <div class="form-group">
            <label>Password Saat Ini</label>
            <input type="password" name="oldpass" class="form-control" id="oldpass" placeholder="Masukan password saat ini" required>
            <?php echo form_error('oldpass'); ?>
        </div>
        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="newpass" class="form-control" id="newpass" placeholder="Masukan password baru" required>
            <?php echo form_error('newpass'); ?>
        </div>
        <div class="form-group">
            <label>Ulangi Password</label>
            <input type="password" name="repass" class="form-control" id="repass" placeholder="Ketik ulang password baru" required>
            <?php echo form_error('repass'); ?>
        </div>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    <button type="reset" class="btn btn-default"><i class="ace-icon fa fa-undo bigger-110"></i> Ulangi</button>
    <button type="submit" name="submit" class="btn btn-primary"><i class="ace-icon fa fa-check bigger-110"></i> Simpan</button>
  </div>
<!-- /.card-footer-->
</form>
</div>
<!-- /.card -->

<script type="text/javascript">
    $(function() {
        $('#change-pass').validate({
            errorClass: "help-block",
            rules: {
                newpass: {
                    required: true,
                    confirmed: true
                },
                repass: {
                    equalTo: newpass
                }
            },
            highlight: function(e) {
                $(e).closest(".form-control").addClass("is-invalid")
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid")
            },
        });
    });
</script>