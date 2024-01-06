<?php 
  if (isset($_POST['tambah-obat'])) {
    
    $nama_obat = htmlspecialchars($_POST['nama_obat']);
    $kemasan = htmlspecialchars($_POST['kemasan']);
    $harga = htmlspecialchars($_POST['harga']);
    $stok = htmlspecialchars($_POST['stok']);

    $data = [
      'nama_obat' => $nama_obat,
      'kemasan' => $kemasan,
      'harga' => (int) $harga,
      'stok' => (int) $stok
    ];

    $ch = curl_init('https://express.dimaspadma.my.id/obat');

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
      header("Location: daftar-obat.php?status=fail&message=Obat Gagal Ditambahkan");
      exit();
    }

    header("Location: daftar-obat.php?status=success&message=Obat Berhasil Ditambahkan");
      exit();
  }
?>