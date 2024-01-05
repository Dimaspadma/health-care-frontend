<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once '../footer.php'; ?>
<script>
  $('.username').html('<?= $_SESSION['admin_username'] ?>'.toUpperCase());
</script>
</body>
</html>