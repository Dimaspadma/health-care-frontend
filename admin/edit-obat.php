<?php 
  if (isset($_POST['tambah-obat'])) {
    $id = htmlspecialchars($_POST['id']);
    $nama_obat = htmlspecialchars($_POST['nama_obat']);
    $kemasan = htmlspecialchars($_POST['kemasan']);
    $harga = htmlspecialchars($_POST['harga']);

    $data = [
      'nama_obat' => $nama_obat,
      'kemasan' => $kemasan,
      'harga' => (int) $harga,
    ];

    // curl request delete obat
    $ch = curl_init('https://express.dimaspadma.my.id/obat/'.$id);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
    if ($response->status == "error") {
      header("Location: daftar-obat.php?status=fail&message=Obat Gagal Diubah");
      exit();

    } else {
      header("Location: daftar-obat.php?status=success&message=Obat Berhasil Diubah");
      exit();
    }
  }
?>