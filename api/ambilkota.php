<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apiKey = "8100aa782b00c8674a151309454e0901";
$url = "https://webapi.bps.go.id/v1/api/domain/type/all/prov/35/key/{$apiKey}";

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

if ($response === false || $curlError) {
    echo json_encode(["error" => "cURL gagal: " . $curlError]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode(["error" => "API BPS error, HTTP $httpCode"]);
    exit;
}

$data = json_decode($response, true);
$list = $data['data'][1] ?? [];

// Filter hanya Jawa Timur (kode domain mulai dari 35)
$jatim = array_values(array_filter($list, function($item) {
    return strpos($item['domain_id'], '35') === 0;
}));

echo json_encode($jatim);
?>