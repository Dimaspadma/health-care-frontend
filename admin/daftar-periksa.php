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
          <th>Pasien</th>
          <th>Dokter</th>
          <th>Tgl Periksa</th>
          <th>Status</th>
          <th>Biaya</th>
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
    url: 'https://express.dimaspadma.my.id/periksa',
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      console.log(result.data)
      result.data.forEach(data => {
        
        $('#body-table').append(`
          <tr>
            <td>${data.pasien.nama}</td>
            <td>${data.jadwalPeriksa.dokter.nama}</td>
            <td>${data.tgl_periksa}</td>
            <td>
              ${data.detailPeriksa ? '<h5><span class="right badge badge-success">Sudah</span></h5>' : '<h5><span class="right badge badge-danger">Belum</span></h5>' }
            </td>
            <td>${data.detailPeriksa ? data.detailPeriksa.biaya_periksa : 0 }</td>
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