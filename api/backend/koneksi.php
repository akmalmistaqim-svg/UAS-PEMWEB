<?php
$servername = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$username   = "4GSamzSq2Qq2Wou.root";
$password   = "J0KseAu9rLSMtgpl";
$database   = "acc";
$port       = 4000;

$koneksi = new mysqli();
$koneksi->real_connect(
    $servername,
    $username,
    $password,
    $database,
    $port,
    null,
    MYSQLI_CLIENT_SSL
);

if ($koneksi->connect_error) {
    die("Koneksi Database Gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8");
?>