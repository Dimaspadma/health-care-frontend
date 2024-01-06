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

<!-- Modal periksa -->
<div class="modal fade" id="modal-periksa">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Periksa Pasien</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="periksa.php" method="post">
        <input type="hidden" name="idPasien" value="">
        <input type="hidden" name="idPeriksa" value="">
        <div class="modal-body">
          <div class="input-group mb-3">
            <input type="text" name="namaPasien" class="form-control" placeholder="Nama Pasien" readonly>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-crutch"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Keluhan ..." name="keluhan" readonly></textarea>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Catatan ..." name="catatan"></textarea>
          </div>
          <div class="form-group">
            <button type="button" id="tambah-obat" class="btn btn-success">Tambah Obat</button>
          </div>
          <div id="list-obat" class="input-group mb-3">
            <!-- Generate obat field -->
          </div>
          <div class="input-group mb-3">
            <input type="text" name="harga_format" class="form-control" placeholder="Harga" readonly>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-wallet"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3" hidden>
            <input type="number" name="harga" class="form-control" placeholder="Harga">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" name="tambah-periksa" class="btn btn-primary">Tambah</button>
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

    let pembayaran = 150000;

    // format input harga to currency
    function changePembayaran(money){
      $('input[name="harga_format"]').val(formatRupiah(money.toString(), 'Rp. '));
      $('input[name="harga"]').val(money);
    }

    // event tambah obat
    let count = 1;
    let pembayaranSementara = {};
    $('#tambah-obat').click(function(){
      $('#list-obat').append(`
        <div id="obat-${count}" class="input-group mb-3">
          <select class="form-control" name="obat[]">
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-pills"></span>
            </div>
            <button type="button" class="btn btn-danger btn-sm btn-hapus-obat" data-obatid="${count}">Hapus</button>
          </div>
        </div>
      `);

      // get data obat
      let first = true;
      $.ajax({
        url: 'https://express.dimaspadma.my.id/obat',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          // console.log(result.data)
          result.data.forEach(obat => {
            $('#list-obat select').append(`
              <option value="${obat.id}">${obat.nama_obat}</option>
            `);

            // add pembayaran only first
            if (first) {
              addPembayaran(count, obat.id);
              first = false;
            }
          });

          count++;
        }
      });
    });

    // event hapus obat
    $(document).on('click', '.btn-hapus-obat', function(){
      const id = $(this).data('obatid');
      
      pembayaran -= pembayaranSementara[id];
      changePembayaran(pembayaran);

      $(`#obat-${id}`).remove();
    });

    // event change obat
    $(document).on('change', 'select[name="obat[]"]', function(){
      const id = $(this).val();

      const selectId = $(this).parent().attr('id').split('-')[1];

      pembayaran -= pembayaranSementara[selectId];
      addPembayaran(selectId, id);
    });

    function addPembayaran(selectId, id){
      $.ajax({
        url: `https://express.dimaspadma.my.id/obat/${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          // console.log(result.data)
          pembayaranSementara[selectId] = result.data.harga;
          pembayaran += pembayaranSementara[selectId];
          changePembayaran(pembayaran);
        }
      });
    }

    // get id dokter 
    const id = $('#id').val();

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
      url: `https://express.dimaspadma.my.id/periksa/dokter/${id}?check=belum`,
      type: 'GET',
      dataType: 'json',
      success: function(result) {
        // console.log(result.data)
        loadTable(result.data);
      }
    });

    function loadTable(result){
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
              <button class="btn btn-success btn-sm btn-periksa" data-toggle="modal" data-target="#modal-periksa" data-id="${jadwal.id}">Periksa</button>
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

        $('.btn-periksa').click(function(){
          loadModal($(this).data('id'));
        });

        changePembayaran(pembayaran);
    }

    function loadModal(id){
      // Load data pasien
      $.ajax({
        url: `https://express.dimaspadma.my.id/periksa/detail/${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(result) {
          console.log('modal', result.data)
          $('#modal-periksa input[name="idPeriksa"]').val(result.data.id);
          $('#modal-periksa input[name="idPasien"]').val(result.data.pasien.id);
          $('#modal-periksa input[name="namaPasien"]').val(result.data.pasien.nama);
          $('#modal-periksa textarea[name="keluhan"]').val(result.data.keluhan || "Tidak ada");
        }
      });
    }

    // convert string of jam_mulai and jam_selesai to hour and minute return number
    function jamGetHour(jam){
      const jam_selesai = jam.split(':');
      const jam_selesai_jam = jam_selesai[0];
      return Number(jam_selesai_jam);
    }

    function jamGetMinute(jam){
      const jam_selesai = jam.split(':');
      const jam_selesai_menit = jam_selesai[1];
      return Number(jam_selesai_menit);
    }

    function jamActive(jam_mulai, jam_akhir){
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

    function formatRupiah(angka, prefix){
      // format input harga to currency
      var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : rupiah ? 'Rp. ' + rupiah : '';
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
    window.history.replaceState({}, document.title, "/" + "puskesmas/dokter/daftar-periksa.php");
  </script>
  EOL;
}
?>