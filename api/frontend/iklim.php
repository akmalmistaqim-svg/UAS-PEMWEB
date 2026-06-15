<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apiKey = "8100aa782b00c8674a151309454e0901";
$url = "https://webapi.bps.go.id/v1/api/view/domain/3500/model/statictable/lang/ind/id/2303/key/{$apiKey}";

// Ganti file_get_contents → cURL
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

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Cek error cURL
if ($response === false || $curlError) {
    echo json_encode(["error" => "cURL gagal: " . $curlError]);
    exit;
}

// Cek HTTP status
if ($httpCode !== 200) {
    echo json_encode(["error" => "API BPS error, HTTP $httpCode"]);
    exit;
}

$data = json_decode($response, true);

// Cek struktur data
if (!isset($data['data']['table'])) {
    echo json_encode([
        "error" => "Tabel tidak ditemukan",
        "debug" => $data
    ]);
    exit;
}

$tabel = html_entity_decode($data['data']['table']);
$judul = $data['data']['title'] ?? "Data Statistik";

echo json_encode([
    "status" => "OK",
    "judul"  => $judul,
    "tabel"  => $tabel
]);
?>