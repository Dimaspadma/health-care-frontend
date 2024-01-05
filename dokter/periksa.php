<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Periksa Pasien</h3>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Tgl Periksa</th>
          <th>No Antrian</th>
          <th>Poli</th>
          <th>Hari</th>
          <th>Jam</th>
          <th>Pasien</th>
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
  
<?php include_once './custom-footer.php' ?>
<script>

  // Jquery on document ready
  $(document).ready(function() {

    // get id dokter 
    const id = $('#id').val();

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
    url: `https://express.dimaspadma.my.id/periksa/dokter/${id}`,
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      result.forEach(jadwal => {
        // console.log(jadwal)
        $('#body-table').append(`
          <tr class="text-bg-success">
            <td>${jadwal.tgl_periksa}</td>
            <td>${jadwal.no_antrian}</td>
            <td>${jadwal.jadwalPeriksa.poli.nama_poli}</td>
            <td>${jadwal.jadwalPeriksa.hari}</td>
            <td>
              ${jadwal.jadwalPeriksa.jam_mulai} - ${jadwal.jadwalPeriksa.jam_selesai}
              ${jamActive(jadwal.jadwalPeriksa.jam_mulai, jadwal.jadwalPeriksa.jam_selesai) ? '<span class="badge badge-success">Aktif</span>' : ''}
            </td>
            <td>${jadwal.pasien.nama}</td>
            <td>
              <a href="detail-periksa.php?id=${jadwal.id_periksa}" class="btn btn-success btn-sm">Periksa</a>
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
    window.history.replaceState({}, document.title, "/" + "puskesmas/admin/daftar-obat.php");
  </script>
  EOL;
}
?>