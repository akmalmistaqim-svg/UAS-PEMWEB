<?php
header('Content-Type: application/json');

$apiKey = "8100aa782b00c8674a151309454e0901";

$url = "https://webapi.bps.go.id/v1/api/view/domain/3500/model/statictable/lang/ind/id/2303/key/{$apiKey}";

$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data"]);
    exit;
}

$data = json_decode($response, true);

// ambil langsung (tanpa foreach)
if (!isset($data['data']['table'])) {
    echo json_encode([
        "error" => "Tabel tidak ditemukan",
        "debug" => $data
    ]);
    exit;
}

// ambil tabel & judul
$tabel = html_entity_decode($data['data']['table']);
$judul = $data['data']['title'] ?? "Data Statistik";

// kirim ke frontend
echo json_encode([
    "status" => "OK",
    "judul"  => $judul,
    "tabel"  => $tabel
]);
?>