<?php 
  if (isset($_POST['hapus-obat'])) {
    $id = $_POST['id'];

    $data = [
      'id' => (int) $id,
    ];

    // curl request delete obat
    $ch = curl_init('https://express.dimaspadma.my.id/obat/'.$id);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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
      header("Location: daftar-obat.php?status=fail&message=Obat Gagal Dihapus");
      exit();

    } else {
      header("Location: daftar-obat.php?status=success&message=Obat Berhasil Dihapus");
      exit();
    }
  }
?>