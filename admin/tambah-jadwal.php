<?php
ob_start();

// check if request method is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['tambah-jadwal'])) {
    // var_dump($_POST);

    $id_dokter = htmlspecialchars($_POST['idDokter']);
    $id_poli = htmlspecialchars($_POST['idPoli']);
    $hari = htmlspecialchars($_POST['hari']);
    $jam_mulai = htmlspecialchars($_POST['jamMulai']);
    $jam_selesai = htmlspecialchars($_POST['jamSelesai']);

    // Set data
    $data = [
      'id_dokter' => (int) $id_dokter,
      'id_poli' => (int) $id_poli,
      'hari' => $hari,
      'jam_mulai' => $jam_mulai,
      'jam_selesai' => $jam_selesai,
    ];

    // Curl POST request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://express.dimaspadma.my.id/jadwal-periksa");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);

    $output = curl_exec($ch);
    // var_dump($output);
    // exit();

    curl_close($ch);
    
    $response = json_decode($output);

    // Error handling
    if (isset($response->error)){
      header("Location: daftar-jadwal.php?status=error&message=$response->message");
      exit();
    }

    // Success handling
    header("Location: daftar-jadwal.php?status=success&message=$response->message");
    exit();
  }
}

ob_end_flush();
?>