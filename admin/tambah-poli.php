<?php 
ob_start();

  if (isset($_POST['tambah-poli'])) {
    
    $nama_poli = htmlspecialchars($_POST['nama_poli']);
    $keterangan = htmlspecialchars($_POST['keterangan']);

    $data = [
      'nama_poli' => $nama_poli,
      'keterangan' => $keterangan
    ];

    $ch = curl_init('https://express.dimaspadma.my.id/poli');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response);

    var_dump($response);

    if (isset($response->error)){
      header("Location: daftar-poli.php?status=fail&message=poli Gagal Ditambahkan");
      exit();
    }

    header("Location: daftar-poli.php?status=success&message=poli Berhasil Ditambahkan");
    exit();
  }

ob_end_flush();
?>