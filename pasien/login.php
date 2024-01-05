<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Nama">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="no_ktp" class="form-control" placeholder="No KTP">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <!-- <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div> -->
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button name="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p> -->
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<div class="row text-center mt-5">
  <div class="alert alert-danger" role="alert" style="display: none;" >
    Login Gagal
  </div>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
  //input with name="nama" in jquery on focus
  $("input[name='nama']").focus(function() {
    //hide alert
    $('.alert').hide();
  });
</script>
</body>
</html>

<?php

session_start();

if (isset($_SESSION['pasien'])) {
  header("Location: index.php");
  exit();
}

// check if request method is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['submit'])){

    // var_dump($_POST);

    // check if email and password are not empty
    if (isset($_POST['nama']) && isset($_POST['no_ktp'])) {

      $nama = $_POST['nama'];
      $no_ktp = $_POST['no_ktp'];

      // Use curl to performt POST request to "https://express.dimaspadma.my.id with body json { nama, no_ktp }
      $ch = curl_init("https://express.dimaspadma.my.id/pasien/login");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'nama' => $nama,
        'no_ktp' => $no_ktp
      ]));
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
      ]);
      $response = curl_exec($ch);
      curl_close($ch);

      // Decode response
      $response = json_decode($response);

      // Check if curl request is success
      if ($response) {

        // alert failed login
        echo <<<EOL
        <script>
          $('.alert').show();
        </script>
        EOL;
      } else {
          // Set session pasien
          $_SESSION['pasien'] = "SUCCESS";

          // Redirect to index.php
          header("Location: index.php");
          exit();
      }

    }
  }
}

?>