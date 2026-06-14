<?php
include $_SERVER['DOCUMENT_ROOT'] . '/api/backend/auth_helper.php';

$user_data = verify_auth();
if (!$user_data) {
    header("Location: /api/frontend/loginpage.php");
    exit();
}

$nama_lengkap = $user_data['nama_lengkap'] ?? 'Petani';
$nama_display = explode(' ', $nama_lengkap)[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACC - Agro Clima Care Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        :root {
            --primary-green: #4CAF50;
            --primary-hover: #43a047;
            --light-green: #eaf4ed;
            --bg-light: #f4f9f5;
            --text-dark: #333333;
            --text-muted: #666666;
            --danger-red: #e53e3e;
            --white: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-light); color: var(--text-dark); overflow-x: hidden; }

        /* NAVBAR */
        .navbar {
            background-color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .logo-text h1 { font-size: 20px; color: var(--primary-green); font-weight: 700; line-height: 1; }
        .logo-text span { font-size: 11px; color: var(--text-muted); letter-spacing: 0.5px; }
        .nav-menu { display: flex; align-items: center; gap: 20px; list-style: none; }
        .nav-item a {
            text-decoration: none; color: var(--text-dark);
            font-size: 13px; font-weight: 600; text-transform: uppercase; transition: color 0.3s;
        }
        .nav-item a:hover, .nav-item.active a { color: var(--primary-green); }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .user-name { font-size: 14px; color: var(--text-dark); }
        .btn-logout {
            background-color: var(--danger-red); color: var(--white);
            border: none; padding: 8px 18px; border-radius: 6px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            text-transform: uppercase; transition: background 0.3s;
            text-decoration: none; display: inline-block;
        }
        .btn-logout:hover { background-color: #c53030; color: var(--white); }
        .hamburger { display: none; cursor: pointer; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 25px; height: 3px; background-color: var(--primary-green); border-radius: 3px; transition: 0.3s; }

        /* HERO */
        .hero {
            margin-top: 75px;
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            padding: 50px 5%;
            background: linear-gradient(rgba(244,249,245,0.70), rgba(244,249,245,0.70)),
                        url('/fotopetani.jpg.jpeg') no-repeat center center/cover;
        }
        .hero-content { max-width: 650px; }
        .hero-title { font-size: 36px; color: var(--primary-green); font-weight: 700; line-height: 1.2; margin-bottom: 15px; }
        .hero-description { font-size: 15px; color: var(--text-muted); margin-bottom: 30px; line-height: 1.6; }
        .btn-cta {
            background-color: var(--primary-green); color: var(--white);
            border: none; padding: 12px 25px; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 10px;
            cursor: pointer; text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(76,175,80,0.2);
            transition: transform 0.3s, background-color 0.3s;
            text-decoration: none;
        }
        .btn-cta:hover { background-color: var(--primary-hover); transform: translateY(-2px); color: var(--white); }

        /* FEATURES */
        .features {
            padding: 40px 5% 60px 5%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }
        .card { background-color: var(--white); padding: 35px 25px; border-radius: 12px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card-icon { font-size: 40px; color: var(--primary-green); margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-text { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

        /* ===================== SECTION PREDIKSI CUACA ===================== */
        .section-cuaca {
            background: #ffffff;
            padding: 70px 5%;
        }
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }
        .section-title p {
            font-size: 14px;
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto;
        }
        .cuaca-card {
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 30px;
            max-width: 700px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .cuaca-form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .cuaca-select, .cuaca-input-tanggal {
            flex: 1;
            min-width: 180px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            background: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }
        .cuaca-select:focus, .cuaca-input-tanggal:focus { border-color: var(--primary-green); }
        .cuaca-btn {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.2s, transform 0.1s;
        }
        .cuaca-btn:hover { opacity: 0.9; }
        .cuaca-btn:active { transform: scale(0.97); }
        .cuaca-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .cuaca-error { color: #e53e3e; font-size: 13px; margin-bottom: 12px; display: none; }
        .cuaca-error.tampil { display: block; }
        .cuaca-status { text-align: center; padding: 30px 0; color: #94a3b8; font-size: 14px; }
        .cuaca-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 3px solid #e2e8f0; border-top-color: var(--primary-green);
            border-radius: 50%; animation: spin 0.7s linear infinite;
            vertical-align: middle; margin-right: 8px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Hasil Prediksi */
        .cuaca-hasil { display: none; }
        .cuaca-hasil.tampil { display: block; }
        .cuaca-header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .cuaca-lokasi-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-green);
            background: var(--light-green);
            border-radius: 999px;
            padding: 4px 14px;
        }
        .cuaca-tanggal-label {
            font-size: 13px;
            color: var(--text-muted);
        }
        .cuaca-main-card {
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: white;
        }
        .cuaca-main-card.panas { background: linear-gradient(135deg, #fb923c, #f59e0b); }
        .cuaca-main-card.hujan { background: linear-gradient(135deg, #64748b, #475569); }
        .cuaca-big-row {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 12px;
        }
        .cuaca-emoji-big { font-size: 56px; }
        .cuaca-suhu-big { font-size: 52px; font-weight: 700; line-height: 1; }
        .cuaca-kondisi-text { font-size: 15px; opacity: 0.9; text-transform: capitalize; margin-top: 4px; }
        .cuaca-stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .cuaca-stat {
            background: rgba(255,255,255,0.18);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }
        .cuaca-stat-label { font-size: 11px; opacity: 0.8; margin-bottom: 4px; }
        .cuaca-stat-val { font-size: 15px; font-weight: 700; }
        .cuaca-btn-ulang {
            width: 100%;
            background: transparent;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            margin-top: 12px;
            transition: border-color 0.2s, color 0.2s;
        }
        .cuaca-btn-ulang:hover { border-color: var(--primary-green); color: var(--primary-green); }

        /* ===================== SECTION GRAFIK ===================== */
        .section-grafik {
            background: #f8fafc;
            padding: 70px 5%;
        }
        .grafik-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 28px;
            max-width: 800px;
            margin: 0 auto;
        }
        .grafik-search-wrap {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .grafik-select {
            flex: 1;
            min-width: 200px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
        }
        .grafik-select:focus { border-color: var(--primary-green); background: #fff; }
        .grafik-btn-cari {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.2s, transform 0.1s;
        }
        .grafik-btn-cari:hover { opacity: 0.9; }
        .grafik-btn-cari:active { transform: scale(0.97); }
        .grafik-btn-cari:disabled { opacity: 0.6; cursor: not-allowed; }
        .grafik-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .grafik-tab {
            background: transparent;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 18px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            color: var(--text-muted);
            font-weight: 500;
            transition: all 0.15s;
        }
        .grafik-tab.aktif { background: var(--primary-green); color: white; border-color: var(--primary-green); }
        .grafik-tab:hover:not(.aktif) { background: var(--light-green); border-color: var(--primary-green); color: var(--primary-green); }
        .grafik-metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .grafik-metric {
            background: var(--light-green);
            border: 1px solid #c8e6c9;
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
        }
        .grafik-metric-icon { font-size: 18px; margin-bottom: 4px; }
        .grafik-metric-label { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
        .grafik-metric-value { font-size: 18px; font-weight: 700; color: var(--primary-green); }
        .grafik-canvas-wrap { position: relative; width: 100%; height: 300px; }
        .grafik-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 14px;
            font-size: 12px;
            color: var(--text-muted);
        }
        .grafik-legend-item { display: flex; align-items: center; gap: 6px; }
        .grafik-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }
        .grafik-status { text-align: center; padding: 40px 0; color: #94a3b8; font-size: 14px; }
        .grafik-error { color: #e53e3e; }
        .grafik-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 3px solid #e2e8f0; border-top-color: var(--primary-green);
            border-radius: 50%; animation: spin 0.7s linear infinite;
            vertical-align: middle; margin-right: 8px;
        }
        .grafik-kota-label {
            font-size: 12px;
            color: var(--primary-green);
            background: var(--light-green);
            border-radius: 999px;
            padding: 3px 14px;
            display: inline-block;
            margin-bottom: 14px;
            font-weight: 600;
        }

        /* ===================== SECTION DATA IKLIM ===================== */
        .section-iklim {
            background: #ffffff;
            padding: 70px 5%;
        }
        .iklim-wrap {
            max-width: 900px;
            margin: 0 auto;
        }
        .iklim-loading { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }
        .iklim-error-box {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: #e53e3e;
            font-size: 14px;
        }
        .iklim-judul {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 20px;
            padding: 14px 20px;
            background: var(--light-green);
            border-radius: 10px;
            border-left: 4px solid var(--primary-green);
        }
        .iklim-table-wrap {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .iklim-table-wrap table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .iklim-table-wrap table th {
            background: #1b4332;
            color: white;
            padding: 12px 16px;
            text-align: center;
            font-weight: 600;
        }
        .iklim-table-wrap table td {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dark);
        }
        .iklim-table-wrap table tr:last-child td { border-bottom: none; }
        .iklim-table-wrap table tr:nth-child(even) td { background: #f8fafc; }
        .iklim-table-wrap table tr:hover td { background: var(--light-green); transition: background 0.2s; }
        .iklim-sumber {
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 14px;
        }

        /* FOOTER */
        footer { background-color: var(--white); text-align: center; padding: 25px; font-size: 13px; color: var(--text-muted); border-top: 1px solid rgba(0,0,0,0.05); }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .features { grid-template-columns: repeat(2, 1fr); margin-top: -20px; }
            .grafik-metrics { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .hamburger { display: flex; order: 2; }
            .user-profile { order: 3; }
            .user-name { display: none; }
            .nav-menu {
                position: fixed; top: 70px; left: -100%;
                flex-direction: column; background-color: var(--white);
                width: 100%; text-align: center; transition: 0.4s;
                box-shadow: 0 10px 15px rgba(0,0,0,0.05);
                padding: 30px 0; gap: 25px;
            }
            .nav-menu.active { left: 0; }
            .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
            .hamburger.active span:nth-child(2) { opacity: 0; }
            .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }
            .hero { padding-top: 80px; text-align: center; min-height: 60vh; }
            .hero-content { margin: 0 auto; }
            .hero-title { font-size: 28px; }
            .features { grid-template-columns: 1fr; margin-top: 20px; gap: 20px; }
            .cuaca-stats-row { grid-template-columns: repeat(3, 1fr); }
            .grafik-metrics { grid-template-columns: repeat(2, 1fr); }
            .grafik-canvas-wrap { height: 240px; }
            .section-cuaca, .section-grafik, .section-iklim { padding: 50px 5%; }
        }
        @media (max-width: 480px) {
            .cuaca-stats-row { grid-template-columns: repeat(3, 1fr); gap: 6px; }
            .grafik-metrics { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo-container">
        <img src="/LogoWeb.png" alt="ACC Logo" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        <div class="logo-text">
            <h1>ACC</h1>
            <span>Agro Clima Care</span>
        </div>
    </div>

    <ul class="nav-menu" id="navMenu">
        <li class="nav-item active"><a href="/api/frontend/dashboard.php">Beranda</a></li>
        <li class="nav-item"><a href="/api/frontend/cekpenyakit.php">Identifikasi Penyakit</a></li>
        <li class="nav-item"><a href="/api/frontend/infopenyakit.php">Info Penyakit</a></li>
        <li class="nav-item"><a href="/api/frontend/hasildiagnosa.php">Hasil Diagnosa</a></li>
    </ul>

    <div class="user-profile">
        <span class="user-name">Halo, <strong><?php echo htmlspecialchars($nama_display); ?></strong></span>
        <a href="/api/frontend/logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="hamburger" id="hamburgerBtn">
        <span></span><span></span><span></span>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-content">
        <h2 class="hero-title">DETEKSI DINI & KENDALIKAN PENYAKIT TANAMAN ANDA</h2>
        <p class="hero-description">Platform Digital Pintar untuk Diagnosis Akurat, Penanganan Efektif, dan Peningkatan Hasil Panen Petani Indonesia.</p>
        <a href="/api/frontend/cekpenyakit.php" class="btn-cta">
            <i class="fa-solid fa-camera"></i> Mulai Identifikasi Sekarang
        </a>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features">
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-leaf"></i></div>
        <h3 class="card-title">Identifikasi Penyakit</h3>
        <p class="card-text">Unggah foto tanaman Anda untuk diagnosis cepat</p>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-book-open-reader"></i></div>
        <h3 class="card-title">Info Penyakit</h3>
        <p class="card-text">Temukan informasi tentang penyakit tanaman, gejala, dan cara penanganan</p>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-cloud-sun-rain"></i></div>
        <h3 class="card-title">Cek Prediksi Cuaca</h3>
        <p class="card-text">Cek prediksi cuaca lokal di Wilayah Jawa Timur</p>
    </div>
</section>

<!-- ===== SECTION 1: PREDIKSI CUACA ===== -->
<section class="section-cuaca">
    <div class="section-title">
        <h2>🌤️ Prediksi Cuaca Jawa Timur</h2>
        <p>Pilih kota dan tanggal untuk melihat prediksi cuaca harian yang akurat</p>
    </div>
    <div class="cuaca-card">
        <div class="cuaca-form-row">
            <select id="cuacaSelectKota" class="cuaca-select">
                <option value="">-- Pilih Kota --</option>
                <option value="-7.2575,112.7521,Surabaya">Surabaya</option>
                <option value="-7.9797,112.6304,Malang">Malang</option>
                <option value="-7.8167,111.9028,Kediri">Kediri</option>
                <option value="-7.6298,111.5239,Madiun">Madiun</option>
                <option value="-8.0954,112.1649,Blitar">Blitar</option>
                <option value="-7.4742,112.4309,Mojokerto">Mojokerto</option>
                <option value="-7.6451,112.9175,Pasuruan">Pasuruan</option>
                <option value="-7.7543,113.2159,Probolinggo">Probolinggo</option>
                <option value="-7.8697,112.5261,Batu">Batu</option>
                <option value="-8.1721,113.6953,Jember">Jember</option>
                <option value="-8.2192,114.3691,Banyuwangi">Banyuwangi</option>
                <option value="-7.9057,113.8230,Bondowoso">Bondowoso</option>
                <option value="-7.7058,114.0142,Situbondo">Situbondo</option>
                <option value="-8.0882,113.2159,Lumajang">Lumajang</option>
                <option value="-7.3506,112.7211,Sidoarjo">Sidoarjo</option>
                <option value="-7.1599,112.6339,Gresik">Gresik</option>
                <option value="-7.1174,112.4119,Lamongan">Lamongan</option>
                <option value="-6.8997,111.9009,Tuban">Tuban</option>
                <option value="-7.1507,111.8815,Bojonegoro">Bojonegoro</option>
                <option value="-7.4067,111.4609,Ngawi">Ngawi</option>
                <option value="-7.6394,111.3242,Magetan">Magetan</option>
                <option value="-7.8650,111.4632,Ponorogo">Ponorogo</option>
                <option value="-8.2003,111.1047,Pacitan">Pacitan</option>
                <option value="-8.0553,111.6233,Trenggalek">Trenggalek</option>
                <option value="-8.0667,111.9028,Tulungagung">Tulungagung</option>
                <option value="-7.5500,112.2167,Jombang">Jombang</option>
                <option value="-7.6015,111.9041,Nganjuk">Nganjuk</option>
                <option value="-7.0456,113.8653,Bangkalan">Bangkalan</option>
                <option value="-7.1833,113.2833,Sampang">Sampang</option>
                <option value="-7.1575,113.4642,Pamekasan">Pamekasan</option>
                <option value="-6.9833,113.8500,Sumenep">Sumenep</option>
            </select>
            <input type="date" id="cuacaInputTanggal" class="cuaca-input-tanggal">
            <button class="cuaca-btn" id="cuacaBtnCek" onclick="cekCuacaDashboard()">
                <i class="fa-solid fa-magnifying-glass"></i> Cek
            </button>
        </div>
        <div class="cuaca-error" id="cuacaError">⚠️ Pilih kota dan tanggal terlebih dahulu.</div>
        <div id="cuacaKonten">
            <div class="cuaca-status">☁️ Pilih kota dan tanggal untuk melihat prediksi cuaca</div>
        </div>
    </div>
</section>

<!-- ===== SECTION 2: GRAFIK CUACA ===== -->
<section class="section-grafik">
    <div class="section-title">
        <h2>📊 Grafik Cuaca Jawa Timur</h2>
        <p>Pilih kota untuk melihat grafik cuaca harian, mingguan, dan bulanan</p>
    </div>
    <div class="grafik-card">
        <div class="grafik-search-wrap">
            <select id="grafikSelectKota" class="grafik-select">
                <option value="">-- Pilih Kota --</option>
                <option value="-7.2575,112.7521,Surabaya">Surabaya</option>
                <option value="-7.9797,112.6304,Malang">Malang</option>
                <option value="-7.8167,111.9028,Kediri">Kediri</option>
                <option value="-7.6298,111.5239,Madiun">Madiun</option>
                <option value="-8.0954,112.1649,Blitar">Blitar</option>
                <option value="-7.4742,112.4309,Mojokerto">Mojokerto</option>
                <option value="-7.6451,112.9175,Pasuruan">Pasuruan</option>
                <option value="-7.7543,113.2159,Probolinggo">Probolinggo</option>
                <option value="-7.8697,112.5261,Batu">Batu</option>
                <option value="-8.1721,113.6953,Jember">Jember</option>
                <option value="-8.2192,114.3691,Banyuwangi">Banyuwangi</option>
                <option value="-7.9057,113.8230,Bondowoso">Bondowoso</option>
                <option value="-7.7058,114.0142,Situbondo">Situbondo</option>
                <option value="-8.0882,113.2159,Lumajang">Lumajang</option>
                <option value="-7.3506,112.7211,Sidoarjo">Sidoarjo</option>
                <option value="-7.1599,112.6339,Gresik">Gresik</option>
                <option value="-7.1174,112.4119,Lamongan">Lamongan</option>
                <option value="-6.8997,111.9009,Tuban">Tuban</option>
                <option value="-7.1507,111.8815,Bojonegoro">Bojonegoro</option>
                <option value="-7.4067,111.4609,Ngawi">Ngawi</option>
                <option value="-7.6394,111.3242,Magetan">Magetan</option>
                <option value="-7.8650,111.4632,Ponorogo">Ponorogo</option>
                <option value="-8.2003,111.1047,Pacitan">Pacitan</option>
                <option value="-8.0553,111.6233,Trenggalek">Trenggalek</option>
                <option value="-8.0667,111.9028,Tulungagung">Tulungagung</option>
                <option value="-7.5500,112.2167,Jombang">Jombang</option>
                <option value="-7.6015,111.9041,Nganjuk">Nganjuk</option>
                <option value="-7.0456,113.8653,Bangkalan">Bangkalan</option>
                <option value="-7.1833,113.2833,Sampang">Sampang</option>
                <option value="-7.1575,113.4642,Pamekasan">Pamekasan</option>
                <option value="-6.9833,113.8500,Sumenep">Sumenep</option>
            </select>
            <button class="grafik-btn-cari" id="grafikBtnCari" onclick="grafikTampilkan()">
                📊 Tampilkan
            </button>
        </div>
        <div id="grafikKonten">
            <div class="grafik-status">☁️ Pilih kota untuk menampilkan grafik cuaca</div>
        </div>
    </div>
</section>

<!-- ===== SECTION 3: DATA IKLIM BPS ===== -->
<section class="section-iklim">
    <div class="section-title">
        <h2>🌡️ Data Iklim Provinsi Jawa Timur</h2>
        <p>Data pengamatan unsur iklim dari stasiun BMKG Jawa Timur</p>
    </div>
    <div class="iklim-wrap">
        <div id="iklimKonten">
            <div class="iklim-loading">
                <span class="cuaca-spinner"></span> Memuat data iklim...
            </div>
        </div>
        <p class="iklim-sumber">Sumber: Badan Pusat Statistik (BPS) Provinsi Jawa Timur · BMKG</p>
    </div>
</section>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
</footer>

<script>
// ===== NAVBAR =====
const hamburgerBtn = document.getElementById('hamburgerBtn');
const navMenu = document.getElementById('navMenu');
hamburgerBtn.addEventListener('click', () => {
    hamburgerBtn.classList.toggle('active');
    navMenu.classList.toggle('active');
});
document.querySelectorAll('.nav-item a').forEach(link => {
    link.addEventListener('click', () => {
        hamburgerBtn.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// Set tanggal default hari ini
(function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('cuacaInputTanggal').value = today;
    document.getElementById('cuacaInputTanggal').max = new Date(Date.now() + 6*24*60*60*1000).toISOString().split('T')[0];
    document.getElementById('cuacaInputTanggal').min = today;
})();

// ===== PREDIKSI CUACA =====
var namaHariArr = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
var namaBulanArr = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

function formatTanggalIndo(str) {
    var d = new Date(str);
    return namaHariArr[d.getDay()] + ', ' + d.getDate() + ' ' + namaBulanArr[d.getMonth()] + ' ' + d.getFullYear();
}

async function cekCuacaDashboard() {
    var select = document.getElementById('cuacaSelectKota');
    var tanggal = document.getElementById('cuacaInputTanggal').value;
    var err = document.getElementById('cuacaError');

    if (!select.value || !tanggal) {
        err.classList.add('tampil');
        return;
    }
    err.classList.remove('tampil');

    var parts = select.value.split(',');
    var lat = parts[0], lon = parts[1], kota = parts[2];

    var btn = document.getElementById('cuacaBtnCek');
    btn.disabled = true;
    btn.innerHTML = '<span class="cuaca-spinner"></span>';

    document.getElementById('cuacaKonten').innerHTML =
        '<div class="cuaca-status"><span class="cuaca-spinner"></span> Mengambil data cuaca ' + kota + '...</div>';

    try {
        var res = await fetch(
            'https://api.open-meteo.com/v1/forecast?' +
            'latitude=' + lat + '&longitude=' + lon +
            '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean,windspeed_10m_max,weathercode' +
            '&timezone=Asia%2FJakarta&forecast_days=7'
        );
        var data = await res.json();
        var daily = data.daily;

        // Cari index tanggal
        var idx = daily.time.indexOf(tanggal);
        if (idx === -1) idx = 0;

        var suhuMax = Math.round(daily.temperature_2m_max[idx]);
        var suhuMin = Math.round(daily.temperature_2m_min[idx]);
        var hujan = Math.round(daily.precipitation_sum[idx] * 10) / 10;
        var rh = Math.round(daily.relative_humidity_2m_mean[idx]);
        var angin = Math.round(daily.windspeed_10m_max[idx]);
        var wcode = daily.weathercode[idx];

        // Tentukan emoji & kondisi dari weathercode
        var emoji, kondisi, cardClass = '';
        if (wcode === 0) { emoji = '☀️'; kondisi = 'Cerah'; }
        else if (wcode <= 2) { emoji = '🌤️'; kondisi = 'Cerah Berawan'; }
        else if (wcode <= 3) { emoji = '☁️'; kondisi = 'Berawan'; }
        else if (wcode <= 48) { emoji = '🌫️'; kondisi = 'Berkabut'; }
        else if (wcode <= 57) { emoji = '🌦️'; kondisi = 'Gerimis'; }
        else if (wcode <= 67) { emoji = '🌧️'; kondisi = 'Hujan'; cardClass = 'hujan'; }
        else if (wcode <= 77) { emoji = '🌨️'; kondisi = 'Hujan Es'; cardClass = 'hujan'; }
        else if (wcode <= 82) { emoji = '🌧️'; kondisi = 'Hujan Lebat'; cardClass = 'hujan'; }
        else if (wcode <= 99) { emoji = '⛈️'; kondisi = 'Badai Petir'; cardClass = 'hujan'; }
        else { emoji = '🌤️'; kondisi = 'Cuaca Campuran'; }

        if (!cardClass && suhuMax >= 33) cardClass = 'panas';

        document.getElementById('cuacaKonten').innerHTML = `
            <div class="cuaca-header-info">
                <span class="cuaca-lokasi-label">📍 ${kota}, Jawa Timur</span>
                <span class="cuaca-tanggal-label">${formatTanggalIndo(tanggal)}</span>
            </div>
            <div class="cuaca-main-card ${cardClass}">
                <div class="cuaca-big-row">
                    <span class="cuaca-emoji-big">${emoji}</span>
                    <div>
                        <div class="cuaca-suhu-big">${suhuMax}°</div>
                        <div class="cuaca-kondisi-text">${kondisi} · min ${suhuMin}°C</div>
                    </div>
                </div>
                <div class="cuaca-stats-row">
                    <div class="cuaca-stat">
                        <div class="cuaca-stat-label">💧 Kelembaban</div>
                        <div class="cuaca-stat-val">${rh}%</div>
                    </div>
                    <div class="cuaca-stat">
                        <div class="cuaca-stat-label">🌧️ Curah Hujan</div>
                        <div class="cuaca-stat-val">${hujan} mm</div>
                    </div>
                    <div class="cuaca-stat">
                        <div class="cuaca-stat-label">💨 Angin</div>
                        <div class="cuaca-stat-val">${angin} km/j</div>
                    </div>
                </div>
            </div>
            <button class="cuaca-btn-ulang" onclick="resetCuaca()">↩ Cek Kota Lain</button>
        `;
    } catch(e) {
        document.getElementById('cuacaKonten').innerHTML =
            '<div class="cuaca-status" style="color:#e53e3e;">❌ Gagal mengambil data: ' + e.message + '</div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Cek';
    }
}

function resetCuaca() {
    document.getElementById('cuacaKonten').innerHTML =
        '<div class="cuaca-status">☁️ Pilih kota dan tanggal untuk melihat prediksi cuaca</div>';
    document.getElementById('cuacaSelectKota').value = '';
}

// ===== GRAFIK CUACA =====
(function() {
    let grafikChart = null;
    let grafikTabAktif = 'harian';
    let grafikDataCache = null;
    let grafikNamaKota = '';

    window.grafikTampilkan = async function() {
        var select = document.getElementById('grafikSelectKota');
        var val = select.value;
        if (!val) {
            document.getElementById('grafikKonten').innerHTML =
                '<div class="grafik-status grafik-error">⚠️ Pilih kota terlebih dahulu.</div>';
            return;
        }
        var [lat, lon, namaKota] = val.split(',');
        grafikNamaKota = namaKota;

        var btn = document.getElementById('grafikBtnCari');
        btn.disabled = true;
        btn.textContent = 'Memuat...';

        document.getElementById('grafikKonten').innerHTML =
            '<div class="grafik-status"><span class="grafik-spinner"></span> Mengambil data cuaca <b>' + namaKota + '</b>...</div>';

        try {
            var today = new Date();
            var endDate = today.toISOString().split('T')[0];
            var start28 = new Date(today); start28.setDate(today.getDate() - 28);
            var startDate28 = start28.toISOString().split('T')[0];
            var startBulanan = new Date(today.getFullYear() - 1, today.getMonth() + 1, 1).toISOString().split('T')[0];

            var [forecastRes, hist28Res, histBulananRes] = await Promise.all([
                fetch('https://api.open-meteo.com/v1/forecast?latitude=' + lat + '&longitude=' + lon +
                    '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean' +
                    '&timezone=Asia%2FJakarta&forecast_days=7'),
                fetch('https://archive-api.open-meteo.com/v1/archive?latitude=' + lat + '&longitude=' + lon +
                    '&start_date=' + startDate28 + '&end_date=' + endDate +
                    '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean&timezone=Asia%2FJakarta'),
                fetch('https://archive-api.open-meteo.com/v1/archive?latitude=' + lat + '&longitude=' + lon +
                    '&start_date=' + startBulanan + '&end_date=' + endDate +
                    '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean&timezone=Asia%2FJakarta')
            ]);
            var [forecastData, hist28Data, histBulananData] = await Promise.all([forecastRes.json(), hist28Res.json(), histBulananRes.json()]);

            grafikDataCache = {
                harian: olahHarian(forecastData.daily),
                mingguan: olahMingguan(hist28Data.daily),
                bulanan: olahBulanan(histBulananData.daily)
            };
            grafikTabAktif = 'harian';
            renderGrafikKonten();
        } catch(err) {
            document.getElementById('grafikKonten').innerHTML =
                '<div class="grafik-status grafik-error">❌ Gagal mengambil data: ' + err.message + '</div>';
        } finally {
            btn.disabled = false;
            btn.textContent = '📊 Tampilkan';
        }
    };

    function olahHarian(daily) {
        var nb = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        var labels = daily.time.slice(0,7).map(t => {
            var d = new Date(t);
            return ['Min','Sen','Sel','Rab','Kam','Jum','Sab'][d.getDay()] + ' ' + d.getDate() + ' ' + nb[d.getMonth()];
        });
        return { labels, suhuMax: daily.temperature_2m_max.slice(0,7).map(v=>Math.round(v)), suhuMin: daily.temperature_2m_min.slice(0,7).map(v=>Math.round(v)), hujan: daily.precipitation_sum.slice(0,7).map(v=>Math.round(v*10)/10), kelembaban: daily.relative_humidity_2m_mean.slice(0,7).map(v=>Math.round(v)) };
    }
    function olahMingguan(daily) {
        var minggu=[[],[],[],[]];
        for(var i=0;i<Math.min(28,daily.time.length);i++) minggu[Math.floor(i/7)].push(i);
        var suhuMax=[],suhuMin=[],hujan=[],kelembaban=[],labels=[];
        minggu.forEach((idx,w) => {
            if(!idx.length) return;
            labels.push('Minggu '+(w+1));
            suhuMax.push(Math.round(avg(idx.map(i=>daily.temperature_2m_max[i]))));
            suhuMin.push(Math.round(avg(idx.map(i=>daily.temperature_2m_min[i]))));
            hujan.push(Math.round(idx.reduce((s,i)=>s+(daily.precipitation_sum[i]||0),0)));
            kelembaban.push(Math.round(avg(idx.map(i=>daily.relative_humidity_2m_mean[i]))));
        });
        return {labels,suhuMax,suhuMin,hujan,kelembaban};
    }
    function olahBulanan(daily) {
        if(!daily||!daily.time) return {labels:[],suhuMax:[],suhuMin:[],hujan:[],kelembaban:[]};
        var bulanMap={};
        var nb=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        daily.time.forEach((t,i) => {
            var d=new Date(t), key=d.getFullYear()+'-'+d.getMonth();
            if(!bulanMap[key]) bulanMap[key]={nama:nb[d.getMonth()],tMax:[],tMin:[],hujan:[],rh:[]};
            bulanMap[key].tMax.push(daily.temperature_2m_max[i]);
            bulanMap[key].tMin.push(daily.temperature_2m_min[i]);
            bulanMap[key].hujan.push(daily.precipitation_sum[i]||0);
            bulanMap[key].rh.push(daily.relative_humidity_2m_mean[i]);
        });
        var keys=Object.keys(bulanMap).slice(-12);
        return {labels:keys.map(k=>bulanMap[k].nama),suhuMax:keys.map(k=>Math.round(avg(bulanMap[k].tMax))),suhuMin:keys.map(k=>Math.round(avg(bulanMap[k].tMin))),hujan:keys.map(k=>Math.round(bulanMap[k].hujan.reduce((a,b)=>a+b,0))),kelembaban:keys.map(k=>Math.round(avg(bulanMap[k].rh)))};
    }
    function avg(arr) { var v=arr.filter(x=>x!==null&&x!==undefined); return v.length?v.reduce((a,b)=>a+b,0)/v.length:0; }

    function metricHTML(max,min,hujan,rh) {
        return `<div class="grafik-metric"><div class="grafik-metric-icon">🌡️</div><div class="grafik-metric-label">Suhu Maks</div><div class="grafik-metric-value">${max}°C</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">🌡️</div><div class="grafik-metric-label">Suhu Min</div><div class="grafik-metric-value">${min}°C</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">🌧️</div><div class="grafik-metric-label">Total Hujan</div><div class="grafik-metric-value">${hujan} mm</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">💧</div><div class="grafik-metric-label">Kelembaban</div><div class="grafik-metric-value">${rh}%</div></div>`;
    }

    function renderGrafikKonten() {
        var d = grafikDataCache[grafikTabAktif];
        var max = Math.round(avg(d.suhuMax)), min = Math.round(avg(d.suhuMin));
        var hujan = d.hujan.reduce((a,b)=>a+b,0).toFixed(1), rh = Math.round(avg(d.kelembaban));
        document.getElementById('grafikKonten').innerHTML = `
            <span class="grafik-kota-label">📍 ${grafikNamaKota}, Jawa Timur</span>
            <div class="grafik-metrics" id="grafikMetrics">${metricHTML(max,min,hujan,rh)}</div>
            <div class="grafik-tabs">
                <button class="grafik-tab ${grafikTabAktif==='harian'?'aktif':''}" onclick="grafikGantiTab('harian',this)">Harian</button>
                <button class="grafik-tab ${grafikTabAktif==='mingguan'?'aktif':''}" onclick="grafikGantiTab('mingguan',this)">Mingguan</button>
                <button class="grafik-tab ${grafikTabAktif==='bulanan'?'aktif':''}" onclick="grafikGantiTab('bulanan',this)">Bulanan</button>
            </div>
            <div class="grafik-canvas-wrap">
                <canvas id="grafikCanvas" role="img" aria-label="Grafik cuaca ${grafikNamaKota}"></canvas>
            </div>
            <div class="grafik-legend">
                <span class="grafik-legend-item"><span class="grafik-legend-dot" style="background:#4CAF50;"></span> Suhu Maks (°C)</span>
                <span class="grafik-legend-item"><span class="grafik-legend-dot" style="background:#81c784;opacity:.8;"></span> Suhu Min (°C)</span>
                <span class="grafik-legend-item"><span class="grafik-legend-dot" style="background:#0ea5e9;"></span> Curah Hujan (mm)</span>
                <span class="grafik-legend-item"><span class="grafik-legend-dot" style="background:#f59e0b;"></span> Kelembaban (%)</span>
            </div>
            <p style="font-size:11px;color:#94a3b8;margin-top:14px;text-align:center;">Sumber: Open-Meteo · Data forecast & historis · Diperbarui otomatis</p>
        `;
        renderChart();
    }

    window.grafikGantiTab = function(tab, el) {
        grafikTabAktif = tab;
        document.querySelectorAll('.grafik-tab').forEach(b=>b.classList.remove('aktif'));
        el.classList.add('aktif');
        var d = grafikDataCache[tab];
        var max=Math.round(avg(d.suhuMax)), min=Math.round(avg(d.suhuMin));
        var hujan=d.hujan.reduce((a,b)=>a+b,0).toFixed(1), rh=Math.round(avg(d.kelembaban));
        document.getElementById('grafikMetrics').innerHTML = metricHTML(max,min,hujan,rh);
        renderChart();
    };

    function renderChart() {
        var d = grafikDataCache[grafikTabAktif];
        var ctx = document.getElementById('grafikCanvas');
        if (!ctx) return;
        if (grafikChart) { grafikChart.destroy(); grafikChart = null; }
        grafikChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    { label:'Suhu Maks (°C)', data:d.suhuMax, type:'line', borderColor:'#4CAF50', backgroundColor:'rgba(76,175,80,0.1)', pointBackgroundColor:'#4CAF50', borderWidth:2, pointRadius:4, tension:0.35, yAxisID:'yTemp', fill:false, order:1 },
                    { label:'Suhu Min (°C)', data:d.suhuMin, type:'line', borderColor:'#81c784', backgroundColor:'rgba(129,199,132,0.08)', pointBackgroundColor:'#81c784', borderWidth:1.5, pointRadius:3, borderDash:[4,3], tension:0.35, yAxisID:'yTemp', fill:false, order:1 },
                    { label:'Curah Hujan (mm)', data:d.hujan, type:'bar', backgroundColor:'rgba(14,165,233,0.6)', borderColor:'#0ea5e9', borderWidth:0, borderRadius:4, yAxisID:'yHujan', order:2 },
                    { label:'Kelembaban (%)', data:d.kelembaban, type:'line', borderColor:'#f59e0b', backgroundColor:'transparent', pointBackgroundColor:'#f59e0b', borderWidth:1.5, borderDash:[3,3], pointRadius:3, tension:0.3, yAxisID:'yTemp', fill:false, order:0 }
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                interaction:{mode:'index',intersect:false},
                plugins:{legend:{display:false},tooltip:{callbacks:{label:function(ctx){var l=ctx.dataset.label||'';if(l.includes('Hujan')) return ' '+l+': '+ctx.parsed.y+' mm';if(l.includes('Kelembaban')) return ' '+l+': '+ctx.parsed.y+'%';return ' '+l+': '+ctx.parsed.y+'°C';}}}},
                scales:{
                    yTemp:{type:'linear',position:'left',min:0,max:100,ticks:{font:{size:11},color:'#94a3b8',callback:v=>v<=50?v+'°':v+'%'},grid:{color:'rgba(148,163,184,0.12)'}},
                    yHujan:{type:'linear',position:'right',min:0,ticks:{font:{size:11},color:'#0ea5e9',callback:v=>v+' mm'},grid:{drawOnChartArea:false}},
                    x:{ticks:{font:{size:11},color:'#94a3b8',autoSkip:false,maxRotation:45},grid:{color:'rgba(148,163,184,0.08)'}}
                }
            }
        });
    }
})();

// ===== DATA IKLIM BPS =====
(async function() {
    try {
        var res = await fetch('/api/Iklim.php');
        var data = await res.json();
        if (data.error) throw new Error(data.error);

        document.getElementById('iklimKonten').innerHTML = `
            <div class="iklim-judul">${data.judul}</div>
            <div class="iklim-table-wrap">${data.tabel}</div>
        `;
    } catch(e) {
        document.getElementById('iklimKonten').innerHTML = `
            <div class="iklim-error-box">
                ⚠️ Gagal memuat data iklim. <br>
                <small style="color:#94a3b8;">${e.message}</small>
            </div>
        `;
    }
})();
</script>

</body>
</html>