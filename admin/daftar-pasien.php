<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Dokter</h3>
    </div>

    <!-- <div class="row mt-3 ml-3">
        <div class="col-12">
          <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Dokter</a>
        </div>
    </div> -->

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Nama</th>
          <th>Alamat</th>
          <th>No KTP</th>
          <th>No HP</th>
          <th>No RM</th>
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
  
<?php include_once './custom-footer.php' ?>
<script>

  // Jquery on document ready
  $(document).ready(function() {

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
    url: 'https://express.dimaspadma.my.id/pasien',
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      result.data.forEach(pasien => {
        console.log(pasien)
        $('#body-table').append(`
          <tr>
            <td>${pasien.nama}</td>
            <td>${pasien.alamat}</td>
            <td>${pasien.no_ktp}</td>
            <td>${pasien.no_hp}</td>
            <td>${pasien.no_rm}</td>
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
      }
    });

  });

</script>

<?php 

var_dump($_GET);

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
  } else if($_GET['status'] == 'fail'){
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
    window.history.replaceState({}, document.title, "/" + "/admin/daftar-obat.php");
  </script>
  EOL;
}
?>