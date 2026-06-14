<?php
// =====================================================
// FILE KONEKSI DATABASE - koneksi.php
// TiDB Cloud (Serverless)
// =====================================================

$servername = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$username   = "4GSamzSq2Qq2Wou.root";
$password   = "J0KseAu9rLSMtgpl"; // Ganti dengan password TiDB asli kamu
$database   = "acc";
$port       = 4000;

// =====================================================
// MEMBUAT KONEKSI DENGAN MySQLi
// =====================================================
$koneksi = new mysqli($servername, $username, $password, $database, $port);

// =====================================================
// CEK KONEKSI
// =====================================================
if ($koneksi->connect_error) {
    die("Koneksi Database Gagal: " . $koneksi->connect_error);
}

// Set charset UTF-8 untuk bahasa Indonesia
$koneksi->set_charset("utf8");

?>