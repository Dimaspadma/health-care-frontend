<?php include_once './custom-header.php'; ?>

<div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Obat</h3>
    </div>

    <div class="row mt-3 ml-3">
        <div class="col-12">
          <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah Obat</a>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <table id="example2" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>Nama</th>
          <th>Kemasan</th>
          <th>Harga</th>
          <th>Stok</th>
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
        <h4 class="modal-title">Tambah Obat</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="tambah-obat.php" method="post">
        <input type="hidden" name="id" value="">
        <div class="modal-body">
          <div class="input-group mb-3">
            <input type="text" name="nama_obat" class="form-control" placeholder="Nama obat">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-crutch"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="kemasan" class="form-control" placeholder="Kemasan">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-capsules"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="harga_format" class="form-control" placeholder="Harga">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-wallet"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3" hidden>
            <input type="number" name="harga" class="form-control" placeholder="Harga">
          </div>
          <div id="div-stok" class="input-group mb-3">
            <input type="number" name="stok" class="form-control" placeholder="Stok">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-plus"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" name="tambah-obat" class="btn btn-primary">Tambah</button>
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
      <form action="hapus-obat.php" method="post">
        <div class="modal-footer justify-content-between">
          <input type="hidden" name="id" value="">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
          <button type="submit" name="hapus-obat" class="btn btn-danger">Hapus</button>
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

    const formatRupiah = (angka, prefix) => {
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

    const rupiahToNumber = (angka) => {
      // convert currency to number
      return parseInt(angka.replace(/,.*|[^0-9]/g, ''), 10);
    }

    // ajax get data from https://express.dimaspadma.my.id/jadwal-periksa
    $.ajax({
    url: 'https://express.dimaspadma.my.id/obat',
    type: 'GET',
    dataType: 'json',
    success: function(result) {
      result.forEach(obat => {
        $('#body-table').append(`
          <tr>
            <td>${obat.nama_obat}</td>
            <td>${obat.kemasan}</td>
            <td>${formatRupiah(obat.harga.toString(), 'Rp. ')}</td>
            <td>${obat.stok}</td>
            <td>
              <button class="btn btn-warning btn-edit" data-toggle="modal" data-target="#modal-tambah" data-id="${obat.id}">Edit</button>
              <button class="btn btn-danger btn-hapus" data-toggle="modal" data-target="#modal-hapus" data-id="${obat.id}">Hapus</button>
              <button class="btn btn-success btn-stok" data-toggle="modal" data-target="#modal-tambah" data-id="${obat.id}">Tambah Stok</button>
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
          $('input[name="id"]').val(id);
          editForm(id);
        });

        $('.btn-stok').on('click', function() {
          var id = $(this).data('id');
          $('input[name="id"]').val(id);
          editStok(id);
        });
      }
    });

    // format input harga to currency
    $('input[name="harga_format"]').on('keyup', function() {
      var value = $(this).val();
      $(this).val(formatRupiah(value, 'Rp. '));
      $('input[name="harga"]').val(rupiahToNumber(value));
    });

    const editForm = (id) => {
      $.ajax({
        url: 'https://express.dimaspadma.my.id/obat/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(result) {

          // Change form url to edit
          $('form').attr('action', 'edit-obat.php');
          
          $('input[name="id"]').val(result.id);
          $('input[name="nama_obat"]').val(result.nama_obat);
          $('input[name="kemasan"]').val(result.kemasan);
          $('input[name="harga_format"]').val(formatRupiah(result.harga.toString(), 'Rp. '));
          $('input[name="harga"]').val(result.harga);
          $('input[name="stok"]').val(result.stok);

          // Make field stok read only
          $('input[name="stok"]').attr('readonly', true);

          $('#modal-tambah').find('.modal-title').text('Edit Obat');
          $('#modal-tambah').find('button[name="tambah-obat"]').text('Edit');

        }
      });
    }

    const editStok = (id) => {
      $.ajax({
        url: 'https://express.dimaspadma.my.id/obat/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(result) {

          // Change form url to edit
          $('form').attr('action', 'tambah-stok.php');
          
          $('input[name="id"]').val(result.id);
          $('input[name="nama_obat"]').val(result.nama_obat);
          $('input[name="kemasan"]').val(result.kemasan);
          $('input[name="harga_format"]').val(formatRupiah(result.harga.toString(), 'Rp. '));
          $('input[name="harga"]').val(result.harga);
          $('input[name="stok"]').val(result.stok);

          // Create new field tambah stok below stok
          $('#div-stok').after(`
            <div class="input-group mb-3">
              <input type="number" name="tambah_stok" class="form-control" placeholder="Tambah Stok">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-plus"></span>
                </div>
              </div>
            </div>
          `);

          // Make field nama_obat, kemasan, harga, stok read only
          $('input[name="nama_obat"]').attr('readonly', true);
          $('input[name="kemasan"]').attr('readonly', true);
          $('input[name="harga_format"]').attr('readonly', true);
          $('input[name="stok"]').attr('readonly', true);

          $('#modal-tambah').find('.modal-title').text('Tambah Stok');
          $('#modal-tambah').find('button[name="tambah-obat"]').text('Tambah');
        }
      });
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