<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header bg-primary">
      <h3 class="card-title">Jadwal Periksa</h3>
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
          <th>Hari</th>
          <th>Jam</th>
          <th>Status</th>
          <th>Poli</th>
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
        <input type="hidden" name="idDokter">
        <div class="modal-body">
          <div class="input-group mb-3">
            <input type="text" name="namaDokter" class="form-control" readonly>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-capsules"></span>
              </div>
            </div>
          </div>
          <div id="poli" class="input-group mb-3">
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
          <div id="option-status" class="input-group mb-3">
            <label>Status Jadwal</label><br>
            <div class="input-group-append">
              <div class="input-group-text">
                <input type="radio" name="status" id="status1" class="form-control" value="Y">
                <label for="status1">Aktif</label><br>
                <input type="radio" name="status" id="status2" class="form-control" value="T">
                <label for="status2">Tidak Aktif</label><br>
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
  
<?php include_once './custom-footer.php' ?>
<script>
  // Jquery on document ready
  $(document).ready(function() {

    // get id dokter 
    const id = $('#id').val();

    // Hide poli
    $('#poli').hide();

    $('#btn-tambah').on('click', function() {
      // Empty select option
      $('select[name="idPoli"]').empty();
      $('input[name="idDokter"').val(id);

      // Hide checkbox
      $('#option-status').hide();

      loadDokterById(id);
      loadPoli();
    });

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
      url: `https://express.dimaspadma.my.id/jadwal-periksa/dokter/${id}`,
      type: 'GET',
      dataType: 'json',
      success: function(result) {
        loadTable(result.data);
      },
    });

    function loadTable(result){
      // console.log(result);
      result.forEach(jadwal => {
        // console.log(jadwal)
        $('#body-table').append(`
          <tr class="text-bg-success">
            <td>${jadwal.hari}</td>
            <td>
              ${jadwal.jam_mulai} - ${jadwal.jam_selesai}
            </td>
            <td>${(jadwal.status_periksa == 'Y') ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>'}</td>
            <td>${jadwal.poli.nama_poli}</td>
            <td>
            <button class="btn btn-warning btn-edit" data-toggle="modal" data-target="#modal-tambah" data-id="${jadwal.id}">Edit</button>
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

      $('.btn-edit').on('click', function() {
        var idJadwal = $(this).data('id');

        // Empty select option
        $('input[name="idDokter"').val(id);
        $('select[name="idPoli"]').empty();

        loadDokterById(id);
        loadPoli();
        
        // wait for 1 second
        
        editForm(idJadwal);
      });

    }

    function loadDokterById(id){
      $.ajax({
        url: `https://express.dimaspadma.my.id/dokter/${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          // console.log(result.data.nama)
          $('input[name="namaDokter"]').val(result.data.nama)
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
          $('input[name="idDokter"').val(result.data.id_dokter);
          $('select[name="idPoli"]').val(result.data.id_poli);
          $('select[name="hari"]').val(result.data.hari);
          $('input[name="jamMulai"]').val(result.data.jam_mulai);
          $('input[name="jamSelesai"]').val(result.data.jam_selesai);

          $('#modal-tambah').find('.modal-title').text('Edit Obat');
          $('#modal-tambah').find('button[name="tambah-jadwal"]').text('Edit');

          // Show option status
          $('#option-status').show();
        }
      });
    }

  });

// convert string of jam_mulai and jam_selesai to hour and minute return number
const jamGetHour = (jam) => {
  const jam_selesai = jam.split(':');
  const jam_selesai_jam = jam_selesai[0];
  return Number(jam_selesai_jam);
}

const jamGetMinute = (jam) => {
  const jam_selesai = jam.split(':');
  const jam_selesai_menit = jam_selesai[1];
  return Number(jam_selesai_menit);
}

const jamActive = (jam_mulai, jam_akhir) => {
  const date = new Date();
  const jam = date.getHours();
  const menit = date.getMinutes();

  const jam_mulai_jam = jamGetHour(jam_mulai);
  const jam_mulai_menit = jamGetMinute(jam_mulai);

  const jam_akhir_jam = jamGetHour(jam_akhir);
  const jam_akhir_menit = jamGetMinute(jam_akhir);

  if (jam >= jam_mulai_jam && jam <= jam_akhir_jam) {
    if (jam == jam_mulai_jam) {
      if (menit >= jam_mulai_menit) {
        return true;
      } else {
        return false;
      }
    } else if (jam == jam_akhir_jam) {
      if (menit <= jam_akhir_menit) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  } else {
    return false;
  }
}
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
    window.history.replaceState({}, document.title, "/" + "dokter/jadwal.php");
  </script>
  EOL;
}
?>