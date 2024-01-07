<?php 
ob_start();

  if (isset($_POST['tambah-poli'])) {
    $id = htmlspecialchars($_POST['idPoli']);
    $nama_poli = htmlspecialchars($_POST['nama_poli']);
    $keterangan = htmlspecialchars($_POST['keterangan']);

    $data = [
      'nama_poli' => $nama_poli,
      'keterangan' => $keterangan
    ];

    // curl request delete obat
    $ch = curl_init('https://express.dimaspadma.my.id/poli/'.$id);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response);

    // var_dump($response);

    // Check if response
    // Check if curl request is success
    if (isset($response->error)) {
      header("Location: daftar-poli.php?status=fail&message=poli Gagal Diubah");
      exit();
    }

    header("Location: daftar-poli.php?status=success&message=poli Berhasil Diubah");
    exit();
  }

ob_end_flush();
?>