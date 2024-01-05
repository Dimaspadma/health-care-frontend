<?php include_once './custom-header.php'; ?>

<div class="row">
  <div class="col-5">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Periksa</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="tambah-periksa.php" method="post">
          <input type="hidden" name="id_pasien">
          <input type="hidden" name="id_jadwal_periksa">
          <!-- Date and time -->
          <div class="form-group">
            <label>Date and time:</label>
              <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime" id="datetime" name="tgl_periksa" data-toggle="datetimepicker" autocomplete="off"/>
                  <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div>
          <!-- /.form group -->
          <div class="form-group">
            <label for="listPoli">Poli</label>
            <select class="custom-select rounded-0" id="listPoli">
              <!-- Auto generate -->
            </select>
          </div>
          <!-- /.form group -->
          <div class="form-group">
            <label for="listJadwal">Jadwal</label>
            <select class="custom-select rounded-0" id="listJadwal">
              <!-- Auto generate -->
            </select>
          </div>
          <div class="form-group">
            <label>Textarea</label>
            <textarea class="form-control" rows="3" placeholder="Keluhan ..." name="keluhan"></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary" name="tambah-periksa">Submit</button>
          </div>
        </form>
      <!-- /.card-body -->
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <!-- /.card -->

  <div class="col-7">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">History Periksa</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <!-- Date and time -->
        <div class="form-group">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Tgl Periksa</th>
              <th>No Antrian</th>
              <th>Poli</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Dokter</th>
            </tr>
            </thead>
            <tbody id="body-table">
              <!-- Auto generated -->
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <!-- /.card -->

</div>
  
<?php include_once './custom-footer.php' ?>
<script>
  //Date and time picker
  $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

  // Jquery on document ready
  $(document).ready(function() {

    $('input[name="id_pasien"]').val($('#id').val());

    var first = true;
    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
    url: 'https://express.dimaspadma.my.id/poli',
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      result.forEach(poli => {
        // console.log(poli)
        $('#listPoli').append(`
          <option value="${poli.id}">${poli.nama_poli}</option>
        `);

        if (first) {
          showListJadwal(poli.id);
          first = false;
        }
      });
    },
    error: function(err) {
      console.log(err)
    }
    });

    // #listPoli on value changing then show list jadwal
    $('#listPoli').change(function() {
      // get id poli
      const id = $(this).val();
      // clear list jadwal
      $('#listJadwal').empty();
      // show list jadwal
      showListJadwal(id);
    });

    // #listJadwal on value changing then set value to input[name="id_jadwal_periksa"]
    $('#listJadwal').on('click', function() {
      const id = $(this).val();
      $('input[name="id_jadwal_periksa"]').val(id);
    });

  });

  var fisrtJadwal = false;
  const showListJadwal = (id) => {
    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.get(`https://express.dimaspadma.my.id/jadwal-periksa/poli/${id}`, function(result) {
      result.forEach(jadwal => {
        // console.log(jadwal)
        $('#listJadwal').append(`
          <option value="${jadwal.id}">${jadwal.hari} ${jadwal.jam_mulai} - ${jadwal.jam_selesai}</option>
        `);

        if (!fisrtJadwal) {
          $('input[name="id_jadwal_periksa"]').val(jadwal.id);
          fisrtJadwal = true;
        }
      });
    });

    const pasienId = $('#id').val();
    $.get(`https://express.dimaspadma.my.id/periksa/pasien/${pasienId}`, function(result) {

      // Clear first
      $('#body-table').empty();

      result.forEach(jadwal => {
        $('#body-table').append(`
          <tr class="text-bg-success">
            <td>${jadwal.tgl_periksa}</td>
            <td>${jadwal.no_antrian}</td>
            <td>${jadwal.jadwalPeriksa.poli.nama_poli}</td>
            <td>${jadwal.jadwalPeriksa.hari}</td>
            <td>${jadwal.jadwalPeriksa.jam_mulai} - ${jadwal.jadwalPeriksa.jam_selesai}</td>
            <td>${jadwal.jadwalPeriksa.dokter.nama}</td>
          </tr>
        `);
      });
    });

  }
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
    window.history.replaceState({}, document.title, "/" + "puskesmas/pasien/periksa.php");
  </script>
  EOL;
}
?>