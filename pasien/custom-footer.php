<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../footer.php'; ?>
<script>
  $('.username').html('<?= $_SESSION['pasien_nama'] ?>'.toUpperCase());
  $('#id').val('<?= $_SESSION['pasien_id'] ?>');
</script>
</body>
</html>