<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Poli</h3>
    </div>

    <div class="row mt-3 ml-3">
        <div class="col-12">
          <button id="btn-tambah" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Poli</button>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Nama Poli</th>
          <th>Keterangan</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody id="body-table">
        
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Jadwal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="tambah-poli.php" method="post">
        <input type="hidden" name="idPoli">
        <div class="modal-body">
          <div class="input-group mb-3">
            <input type="text" name="nama_poli" class="form-control" placeholder="Nama Poli">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-capsules"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-wallet"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" name="tambah-poli" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="modal-hapus">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Apakah Yakin dihapus?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="hapus-poli.php" method="post">
        <input type="hidden" name="idPoli">
        <div class="modal-footer justify-content-between">
          <input type="hidden" name="id" value="">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
          <button type="submit" name="hapus-poli" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
  
<?php include_once './custom-footer.php' ?>
<script>

  // Jquery on document ready
  $(document).ready(function() {

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
      url: 'https://express.dimaspadma.my.id/poli',
      type: 'GET',
      dataType: 'json',
      success: function(result) {
        result.data.forEach(data => {
          $('#body-table').append(`
            <tr>
              <td>${data.nama_poli}</td>
              <td>${data.keterangan}</td>
              <td>
                <button class="btn btn-warning btn-edit" data-toggle="modal" data-target="#modal-tambah" data-id="${data.id}">Edit</button>
                <button class="btn btn-danger btn-hapus" data-toggle="modal" data-target="#modal-hapus" data-id="${data.id}">Hapus</button>
              </td>
            </tr>
          `);
        });

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });

          $('.btn-hapus').on('click', function() {
            var id = $(this).data('id');
            $('input[name="id"]').val(id);
          });

          $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            
            editForm(id);
          });

        }
      });

    const editForm = (id) => {
      $.ajax({
        url: 'https://express.dimaspadma.my.id/poli/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          console.log(result);

          // Change form url to edit
          $('form').attr('action', 'edit-poli.php');
          
          // Set form data
          $('input[name="idPoli"]').val(result.data.id);
          $('input[name="nama_poli"]').val(result.data.nama_poli);
          $('textarea[name="keterangan"]').val(result.data.keterangan);

          $('#modal-tambah').find('.modal-title').text('Edit Obat');
          $('#modal-tambah').find('button[name="tambah-jadwal"]').text('Edit');

        }
      });
    }

  });

</script>

<?php 
if(isset($_GET['message'])){
  $message = $_GET['message'];
} else {
  $message = '';
}

if(isset($_GET['status'])){
  if($_GET['status'] == 'success'){
    echo <<<EOL
    <script>
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Berhasil',
        body: '$message'
      })
    </script>
    EOL;
  } else if($_GET['status'] == 'error'){
    echo <<<EOL
    <script>
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Gagal',
        body: '$message'
      })
    </script>
    EOL;
  }

  // Remove status from url via javascript
  echo <<<EOL
  <script>
    window.history.replaceState({}, document.title, "/" + "puskesmas/admin/daftar-jadwal.php");
  </script>
  EOL;
}
?>