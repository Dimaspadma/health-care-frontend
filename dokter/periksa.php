<?php
// check if request method is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['tambah-periksa'])) {
    // var_dump($_POST);

    $id_periksa = htmlspecialchars($_POST['idPeriksa']);
    $catatan = htmlspecialchars($_POST['catatan']);
    $biaya_periksa = htmlspecialchars($_POST['harga']);

    // Set data
    $data_detail_periksa = [
      'id_periksa' => (int) $id_periksa,
      'catatan' => $catatan,
      'biaya_periksa' => (int) $biaya_periksa,  
    ];

    // Curl POST request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://express.dimaspadma.my.id/periksa/sudah");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_detail_periksa));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);

    $output = curl_exec($ch);
    // var_dump($output);

    curl_close($ch);
    
    $response = json_decode($output);

    // Error handling
    if (isset($response->error)){
      header("Location: daftar-periksa.php?status=error&message=$response->message");
      exit();
    }

    // Success handling
    // request obat
    if(isset($_POST['obat'])){
      $allObat = $_POST['obat'];

      // for llop all obat
      foreach($allObat as $obat){
        $data_obat = [
          'id_periksa' => (int) $id_periksa,
          'id_obat' => (int) $obat,
          'jumlah' => 1,
        ];

        // Curl POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://express.dimaspadma.my.id/membeli");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_obat));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json'
        ]);

        $output = curl_exec($ch);
        // var_dump($output);

        curl_close($ch);
        
      }
    }

    header("Location: daftar-periksa.php?status=success&message=$response->message");
    exit();
  }
}
?>