<?php 
ob_start();

  if (isset($_POST['hapus-poli'])) {

    $id = $_POST['idPoli'];

    $data = [
      'id' => (int) $id,
    ];

    // curl request delete obat
    $ch = curl_init('https://express.dimaspadma.my.id/poli/'.$id);

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
    if (isset($response->error)) {
      header("Location: daftar-poli.php?status=fail&message=poli Gagal Dihapus");
      exit();
    }

    header("Location: daftar-poli.php?status=success&message=poli Berhasil Dihapus");
    exit();
  }

ob_end_flush();
?>