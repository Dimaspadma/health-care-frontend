<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header bg-primary">
      <h3 class="card-title">Jadwal Periksa</h3>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Hari</th>
          <th>Jam</th>
          <th>Poli</th>
          <!-- <th>Aksi</th> -->
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
              ${jamActive(jadwal.jam_mulai, jadwal.jam_selesai) ? '<span class="badge badge-success">Aktif</span>' : ''}
            </td>
            <td>${jadwal.poli.nama_poli}</td>
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
</script>