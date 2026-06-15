<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apiKey = "f7652f135c9a5c247ef630ab130d60e8";
$kota   = isset($_GET['kota']) ? trim($_GET['kota']) : '';

if (empty($kota)) {
    echo json_encode(["error" => "Kota tidak boleh kosong"]);
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($kota) . ",ID&appid={$apiKey}&units=metric&lang=id";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => [
        'Accept: application/json',
        'User-Agent: Mozilla/5.0 (compatible; AgroClimaCare/1.0)'
    ]
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $curlError) {
    echo json_encode(["error" => "cURL gagal: " . $curlError]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode(["error" => "Gagal ambil data cuaca, HTTP $httpCode"]);
    exit;
}

$data = json_decode($response, true);

if (!isset($data['cod']) || $data['cod'] != "200") {
    echo json_encode(["error" => "Kota tidak ditemukan"]);
    exit;
}

echo json_encode($data);
?>