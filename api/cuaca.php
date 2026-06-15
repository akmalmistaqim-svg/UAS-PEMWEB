<?php
// Gunakan __DIR__ agar path-nya absolut dan aman di Vercel
include __DIR__ . '/../backend/auth_helper.php';

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ACC - Prediksi Cuaca</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        body { background-color: var(--bg-light); color: var(--text-dark); }

        /* NAVBAR */
        .navbar {
            background-color: var(--white);
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed; width: 100%; top: 0; z-index: 1000;
        }
        .logo-container { display: flex; align-items: center; gap: 10px; }
        .logo-text h1 { font-size: 20px; color: var(--primary-green); font-weight: 700; line-height: 1; }
        .logo-text span { font-size: 11px; color: var(--text-muted); letter-spacing: 0.5px; }
        .nav-menu { display: flex; align-items: center; gap: 20px; list-style: none; }
        .nav-item a { text-decoration: none; color: var(--text-dark); font-size: 13px; font-weight: 600; text-transform: uppercase; transition: color 0.3s; }
        .nav-item a:hover, .nav-item.active a { color: var(--primary-green); }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .user-name { font-size: 14px; color: var(--text-dark); }
        .btn-logout { background-color: var(--danger-red); color: var(--white); border: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-transform: uppercase; transition: background 0.3s; text-decoration: none; display: inline-block; }
        .btn-logout:hover { background-color: #c53030; }
        .hamburger { display: none; cursor: pointer; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 25px; height: 3px; background-color: var(--primary-green); border-radius: 3px; transition: 0.3s; }

        /* HERO */
        .hero {
            margin-top: 75px;
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #7dd3fc 100%);
            padding: 60px 5%;
            text-align: center;
            color: white;
        }
        .hero h1 { font-size: 32px; font-weight: 700; margin-bottom: 10px; }
        .hero p { font-size: 14px; opacity: 0.9; }

        /* FORM */
        .section-form { padding: 50px 5%; }
        .form-card {
            background: var(--white); border-radius: 16px;
            border: 1px solid #e2e8f0; padding: 35px;
            max-width: 600px; margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .form-card h2 { font-size: 16px; font-weight: 600; color: var(--text-dark); margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
        .form-group select, .form-group input {
            width: 100%; padding: 10px 14px; font-size: 14px;
            font-family: 'Poppins', sans-serif; color: var(--text-dark);
            border: 1.5px solid #cbd5e1; border-radius: 10px;
            background: var(--white); outline: none; transition: border-color 0.2s;
        }
        .form-group select:focus, .form-group input:focus { border-color: var(--primary-green); }
        .btn-cek {
            width: 100%; background: linear-gradient(135deg, var(--primary-green), var(--primary-hover));
            color: white; border: none; border-radius: 10px;
            padding: 12px; font-size: 14px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-cek:hover { opacity: 0.9; }
        .btn-cek:disabled { opacity: 0.6; cursor: not-allowed; }
        .pesan-error { color: var(--danger-red); font-size: 13px; text-align: center; margin-top: 10px; display: none; }
        .pesan-error.tampil { display: block; }

        /* HASIL */
        .section-hasil { padding: 0 5% 60px 5%; display: none; }
        .section-hasil.tampil { display: block; }
        .hasil-wrap { max-width: 600px; margin: 0 auto; }

        .card-utama {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: white; border-radius: 16px; padding: 28px; margin-bottom: 16px;
        }
        .card-utama.panas { background: linear-gradient(135deg, #fb923c, #f59e0b); }
        .card-utama.hujan { background: linear-gradient(135deg, #64748b, #475569); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .card-lokasi { font-size: 13px; font-weight: 600; opacity: 0.9; }
        .card-tanggal { font-size: 12px; opacity: 0.75; margin-top: 2px; }
        .card-emoji { font-size: 52px; }
        .card-suhu { font-size: 56px; font-weight: 700; line-height: 1; }
        .card-kondisi { font-size: 15px; opacity: 0.9; text-transform: capitalize; margin-top: 4px; }

        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 16px; }
        .stat-card { background: var(--white); border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
        .stat-val { font-size: 20px; font-weight: 700; color: var(--text-dark); }

        /* REKOMENDASI */
        .rek-card { background: var(--white); border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; margin-bottom: 16px; }
        .rek-tabs { display: flex; border-bottom: 1.5px solid #e2e8f0; }
        .rek-tab { flex: 1; padding: 12px; font-size: 13px; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer; border: none; background: transparent; color: var(--text-muted); border-bottom: 2px solid transparent; transition: all 0.15s; }
        .rek-tab.aktif { color: var(--primary-green); border-bottom-color: var(--primary-green); background: var(--white); }
        .rek-konten { padding: 20px; }
        .rek-banner { background: #e8f4fd; border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: center; margin-bottom: 16px; }
        .rek-banner-emoji { font-size: 28px; }
        .rek-banner-judul { font-size: 13px; font-weight: 600; color: #1e40af; }
        .rek-banner-detail { font-size: 12px; color: #3b82f6; margin-top: 2px; }
        .rek-kartu-wrap { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }
        .rek-aktivitas-label { font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 1.5px; margin-bottom: 10px; }
        .rek-aktivitas-wrap { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: var(--white); }
        .btn-ulang { width: 100%; background: transparent; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 11px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: all 0.2s; }
        .btn-ulang:hover { border-color: var(--primary-green); color: var(--primary-green); }

        /* FOOTER */
        footer { background: var(--white); text-align: center; padding: 25px; font-size: 13px; color: var(--text-muted); border-top: 1px solid rgba(0,0,0,0.05); }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hamburger { display: flex; order: 2; }
            .user-profile { order: 3; }
            .user-name { display: none; }
            .nav-menu { position: fixed; top: 70px; left: -100%; flex-direction: column; background-color: var(--white); width: 100%; text-align: center; transition: 0.4s; box-shadow: 0 10px 15px rgba(0,0,0,0.05); padding: 30px 0; gap: 25px; }
            .nav-menu.active { left: 0; }
            .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
            .hamburger.active span:nth-child(2) { opacity: 0; }
            .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }
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
        <li class="nav-item"><a href="/api/frontend/dashboard.php">Beranda</a></li>
        <li class="nav-item"><a href="/api/frontend/cekpenyakit.php">Identifikasi Penyakit</a></li>
        <li class="nav-item"><a href="/api/frontend/infopenyakit.php">Info Penyakit</a></li>
        <li class="nav-item"><a href="/api/frontend/hasildiagnosa.php">Hasil Diagnosa</a></li>
        <li class="nav-item active"><a href="/api/frontend/cuaca.php">Cuaca</a></li>
    </ul>
    <div class="user-profile">
        <span class="user-name">Halo, <strong><?php echo htmlspecialchars($nama_display); ?></strong></span>
        <a href="/api/frontend/logout.php" class="btn-logout">Logout</a>
    </div>
    <div class="hamburger" id="hamburgerBtn">
        <span></span><span></span><span></span>
    </div>
</nav>

<div class="hero">
    <h1>🌤️ Prediksi Cuaca Jawa Timur</h1>
    <p>Masukkan daerah dan tanggal untuk melihat hasil prediksi</p>
</div>

<div class="section-form">
    <div class="form-card">
        <h2>Isi Data di sini</h2>
        <div class="form-group">
            <label>📍 Daerah / Kota</label>
            <select id="inputDaerah">
                <option value="">-- Pilih Daerah --</option>
            </select>
        </div>
        <div class="form-group">
            <label>📅 Tanggal Prediksi</label>
            <input type="hidden" id="inputTanggal" />
            <input id="inputTanggal_display" type="text" placeholder="DD/MM/YYYY" readonly />
        </div>
        <button class="btn-cek" onclick="cekPrediksi()">Lihat Prediksi</button>
        <p id="pesanError" class="pesan-error">⚠️ Mohon isi daerah dan tanggal terlebih dahulu.</p>
    </div>
</div>

<div id="hasilPrediksi" class="section-hasil">
    <div class="hasil-wrap">
        <div id="cardUtama" class="card-utama">
            <div class="card-header">
                <div>
                    <p class="card-lokasi" id="hasilLokasi">📍 -</p>
                    <p class="card-tanggal" id="hasilTanggal">-</p>
                </div>
                <span id="hasilEmoji" class="card-emoji">⛅</span>
            </div>
            <div style="display:flex;align-items:flex-end;gap:12px;">
                <span id="hasilSuhu" class="card-suhu">-°</span>
                <div style="margin-bottom:6px;">
                    <p id="hasilKondisi" class="card-kondisi">-</p>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-label">💧 Kelembaban</p>
                <p class="stat-val" id="hasilLembab">-</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">💨 Kec. Angin</p>
                <p class="stat-val" id="hasilAngin">-</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">🌧️ Peluang Hujan</p>
                <p class="stat-val" id="hasilHujan">-</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">🌡️ Tekanan Udara</p>
                <p class="stat-val" id="hasilUV">-</p>
            </div>
        </div>

        <div class="rek-card">
            <div class="rek-tabs">
                <button class="rek-tab aktif" id="tabMinggu" onclick="setTabRek('minggu')">7 Hari ke Depan</button>
                <button class="rek-tab" id="tabBulan" onclick="setTabRek('bulan')">Bulan Depan</button>
            </div>
            <div id="rekMinggu" class="rek-konten">
                <div class="rek-banner">
                    <span id="rekEmojiBanner" class="rek-banner-emoji">⛅</span>
                    <div>
                        <p id="rekJudulBanner" class="rek-banner-judul">-</p>
                        <p id="rekDetailBanner" class="rek-banner-detail">-</p>
                    </div>
                </div>
                <div id="rekKartuMinggu" class="rek-kartu-wrap"></div>
                <p class="rek-aktivitas-label">AKTIVITAS PETANI MINGGU INI</p>
                <div id="rekAktivitasMinggu" class="rek-aktivitas-wrap"></div>
            </div>
            <div id="rekBulan" class="rek-konten" style="display:none;">
                <div class="rek-banner">
                    <span class="rek-banner-emoji">🌤️</span>
                    <div>
                        <p id="rekJudulBulan" class="rek-banner-judul">-</p>
                        <p id="rekDetailBulan" class="rek-banner-detail">-</p>
                    </div>
                </div>
                <div id="rekKartuBulan" class="rek-kartu-wrap"></div>
                <p class="rek-aktivitas-label">AKTIVITAS PETANI BULAN DEPAN</p>
                <div id="rekAktivitasBulan" class="rek-aktivitas-wrap"></div>
            </div>
        </div>

        <button class="btn-ulang" onclick="cekLagi()">🔄 Cek Cuaca Lagi</button>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/api/frontend/cuaca.js"></script>

</body>
</html>