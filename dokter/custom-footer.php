<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../footer.php'; ?>
<script>
  // Change username value
  $('.username').html('<?= $_SESSION['dokter_nama'] ?>'.toUpperCase());
  $('#id').val('<?= $_SESSION['dokter_id'] ?>');

</script>
</body>
</html>