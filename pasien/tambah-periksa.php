<?php 
ob_start();

  if (isset($_POST['tambah-periksa'])) {

    var_dump($_POST);
    
    $id_pasien = htmlspecialchars($_POST['id_pasien']);
    $id_jadwal = htmlspecialchars($_POST['id_jadwal_periksa']);
    $keluhan = htmlspecialchars($_POST['keluhan']);
    $tgl_periksa = htmlspecialchars($_POST['tgl_periksa']);

    $data = [
      'id_pasien' => (int) $id_pasien,
      'id_jadwal_periksa' => (int) $id_jadwal,
      'keluhan' => $keluhan,
      'tgl_periksa' => $tgl_periksa
    ];

    $ch = curl_init('https://express.dimaspadma.my.id/periksa');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response);

    var_dump($response);

    // Check if response
    // Check if curl request is success
    if (isset($response->error)) {

      header("Location: periksa.php?status=fail&message=Periksa Gagal Ditambahkan");
      exit();

    } else {
      header("Location: periksa.php?status=success&message=Periksa Berhasil Ditambahkan");
      exit();
    }

  }

ob_end_flush();
?>