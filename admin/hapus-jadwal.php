<?php 
ob_start();

  if (isset($_POST['hapus-jadwal'])) {
    $id = $_POST['id'];

    $data = [
      'id' => (int) $id,
    ];

    // curl request delete jadwal
    $ch = curl_init('https://express.dimaspadma.my.id/jadwal-periksa/'.$id);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);    

    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response);

    // var_dump($response);
    // exit();

    // Check if response
    // Check if curl request is success
    if (isset($response->error)) {
      header("Location: daftar-jadwal.php?status=fail&message=jadwal Gagal Dihapus");
      exit();
    }

    header("Location: daftar-jadwal.php?status=success&message=jadwal Berhasil Dihapus");
    exit();
  }

ob_end_flush();
?>