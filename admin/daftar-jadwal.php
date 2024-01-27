<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Jadwal</h3>
    </div>

    <div class="row mt-3 ml-3">
        <div class="col-12">
          <button id="btn-tambah" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Jadwal</button>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Dokter</th>
          <th>Hari</th>
          <th>Jam Mulai</th>
          <th>Jam Selesai</th>
          <th>Poli</th>
          <th>Status</th>
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
      <form action="tambah-jadwal.php" method="post">
        <input type="hidden" name="idJadwal">
        <div class="modal-body">
          <div class="input-group mb-3">
            <select name="idDokter">
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-crutch"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <select name="idPoli"></select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-crutch"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <select name="hari">
              <option value="Senin">Senin</option>
              <option value="Selasa">Selasa</option>
              <option value="Rabu">Rabu</option>
              <option value="Kamis">Kamis</option>
              <option value="Jum'at">Jum'at</option>
              <option value="Sabtu">Sabtu</option>
              <option value="Minggu">Minggu</option>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-crutch"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="time" name="jamMulai" class="form-control" placeholder="Jam Mulai">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-capsules"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="time" name="jamSelesai" class="form-control" placeholder="Jam Selesai">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-wallet"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" name="tambah-jadwal" class="btn btn-primary">Tambah</button>
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
      <form action="hapus-jadwal.php" method="post">
        <div class="modal-footer justify-content-between">
          <input type="hidden" name="id" value="">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
          <button type="submit" name="hapus-jadwal" class="btn btn-danger">Hapus</button>
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

    $('#btn-tambah').on('click', function() {

      // Empty select option
      $('select[name="idDokter"]').empty();
      $('select[name="idPoli"]').empty();

      loadDokter();
      loadPoli();
    });

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
      url: 'https://express.dimaspadma.my.id/jadwal-periksa',
      type: 'GET',
      dataType: 'json',
      success: function(result) {
        console.log(result)
        result.data.forEach(data => {
          $('#body-table').append(`
            <tr>
              <td>${data.dokter.nama}</td>
              <td>${data.hari}</td>
              <td>${data.jam_mulai}</td>
              <td>${data.jam_selesai}</td>
              <td>${data.poli.nama_poli}</td>
              <td>${data.status_periksa ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>'}</td>
              <td>
                <button class="btn btn-warning btn-edit" data-toggle="modal" data-target="#modal-tambah" data-id="${data.id}">Edit</button>
                ${data.periksa.length > 0 ? '' : '<button class="btn btn-danger btn-hapus" data-toggle="modal" data-target="#modal-hapus" data-id="'+data.id+'">Hapus</button>'}
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

            // Empty select option
            $('select[name="idDokter"]').empty();
            $('select[name="idPoli"]').empty();

            loadDokter();
            loadPoli();
            
            // wait for 1 second
            
            editForm(id);
          });

        }
      });

    const editForm = (id) => {
      $.ajax({
        url: 'https://express.dimaspadma.my.id/jadwal-periksa/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          console.log(result);

          // Change form url to edit
          $('form').attr('action', 'edit-jadwal.php');
          
          // Set form data
          $('input[name="idJadwal"]').val(result.data.id);
          $('select[name="idDokter"]').val(result.data.id_dokter);
          $('select[name="idPoli"]').val(result.data.id_poli);
          $('select[name="hari"]').val(result.data.hari);
          $('input[name="jamMulai"]').val(result.data.jam_mulai);
          $('input[name="jamSelesai"]').val(result.data.jam_selesai);

          $('#modal-tambah').find('.modal-title').text('Edit Obat');
          $('#modal-tambah').find('button[name="tambah-jadwal"]').text('Edit');

        }
      });
    }

    function loadDokter(){
      $.ajax({
        url: 'https://express.dimaspadma.my.id/dokter',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          result.data.forEach(data => {
            $('select[name="idDokter"]').append(`
              <option value="${data.id}">${data.nama}</option>
            `);
          });
        }
      });
    }

    function loadPoli(){
      $.ajax({
        url: 'https://express.dimaspadma.my.id/poli',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          result.data.forEach(data => {
            $('select[name="idPoli"]').append(`
              <option value="${data.id}">${data.nama_poli}</option>
            `);
          });
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
    window.history.replaceState({}, document.title, "/" + "admin/daftar-jadwal.php");
  </script>
  EOL;
}
?>