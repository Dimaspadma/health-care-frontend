<?php 
  if (isset($_POST['tambah-obat'])) {
    
    $id = htmlspecialchars($_POST['id']);
    $stok = htmlspecialchars($_POST['stok']);
    $tambah_stok = htmlspecialchars($_POST['tambah_stok']);

    $data = [
      'stok' => (int) $stok + (int) $tambah_stok
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

    if (isset($response->error)){
      header("Location: daftar-obat.php?status=fail&message=Stok Gagal ditambahkan");
      exit();
    }

    header("Location: daftar-obat.php?status=success&message=Stok Berhasil ditambahkan");
      exit();
  }
?>