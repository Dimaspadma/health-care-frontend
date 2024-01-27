<?php
ob_start();

// check if request method is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if(isset($_POST['tambah-jadwal'])){
    // var_dump($_POST);
    // exit();

    $hari = htmlspecialchars($_POST['hari']);
    $jam_mulai = htmlspecialchars($_POST['jamMulai']);
    $jam_selesai = htmlspecialchars($_POST['jamSelesai']);
    $id_dokter = htmlspecialchars($_POST['idDokter']);
    $id_poli = htmlspecialchars($_POST['idPoli']);
    $id_jadwal = htmlspecialchars($_POST['idJadwal']);
    $status = htmlspecialchars($_POST['status']);

    $data = [
      'hari' => $hari,
      'jam_mulai' => $jam_mulai,
      'jam_selesai' => $jam_selesai,
      'id_dokter' => (int) $id_dokter,
      'id_poli' => (int) $id_poli,
      'status_periksa' => $status,
    ];

    // var_dump($data);

    // Curl POST request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://express.dimaspadma.my.id/jadwal-periksa/$id_jadwal");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
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
      header("Location: jadwal.php?status=error&message=$response->message");
      exit();
    }

    // Success handling
    header("Location: jadwal.php?status=success&message=$response->message");
    exit();
  }
}

ob_end_flush();
?>