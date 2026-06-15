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
        .btn-logout:hover { background-color: #c53030; color: var(--white); }
        .hamburger { display: none; cursor: pointer; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 25px; height: 3px; background-color: var(--primary-green); border-radius: 3px; transition: 0.3s; }

        /* HERO */
        .hero {
            margin-top: 75px;
            position: relative; min-height: 70vh;
            display: flex; align-items: center; padding: 50px 5%;
            background: linear-gradient(rgba(244,249,245,0.70), rgba(244,249,245,0.70)),
                        url('/fotopetani.jpg.jpeg') no-repeat center center/cover;
        }
        .hero-content { max-width: 650px; }
        .hero-title { font-size: 36px; color: var(--primary-green); font-weight: 700; line-height: 1.2; margin-bottom: 15px; }
        .hero-description { font-size: 15px; color: var(--text-muted); margin-bottom: 30px; line-height: 1.6; }
        .btn-cta { background-color: var(--primary-green); color: var(--white); border: none; padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; text-transform: uppercase; box-shadow: 0 4px 12px rgba(76,175,80,0.2); transition: transform 0.3s, background-color 0.3s; text-decoration: none; }
        .btn-cta:hover { background-color: var(--primary-hover); transform: translateY(-2px); color: var(--white); }

        /* FEATURES */
        .features { padding: 40px 5% 60px 5%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: -50px; position: relative; z-index: 10; }
        .card { background-color: var(--white); padding: 35px 25px; border-radius: 12px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card-icon { font-size: 40px; color: var(--primary-green); margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-text { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

        /* SECTION TITLES */
        .section-title { text-align: center; margin-bottom: 40px; }
        .section-title h2 { font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .section-title p { font-size: 14px; color: var(--text-muted); max-width: 500px; margin: 0 auto; }

        /* SECTION PREDIKSI CUACA */
        .section-cuaca { background: #ffffff; padding: 70px 5%; }
        .cuaca-card { background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 30px; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .cuaca-form-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .cuaca-select, .cuaca-input-tanggal { flex: 1; min-width: 180px; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 14px; font-family: 'Poppins', sans-serif; color: #1e293b; background: #ffffff; outline: none; transition: border-color 0.2s; }
        .cuaca-select:focus, .cuaca-input-tanggal:focus { border-color: var(--primary-green); }
        .cuaca-btn { background: linear-gradient(135deg, var(--primary-green), var(--primary-hover)); color: white; border: none; border-radius: 10px; padding: 10px 22px; font-size: 14px; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer; white-space: nowrap; transition: opacity 0.2s, transform 0.1s; }
        .cuaca-btn:hover { opacity: 0.9; }
        .cuaca-btn:active { transform: scale(0.97); }
        .cuaca-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .cuaca-error { color: #e53e3e; font-size: 13px; margin-bottom: 12px; display: none; }
        .cuaca-error.tampil { display: block; }
        .cuaca-status { text-align: center; padding: 30px 0; color: #94a3b8; font-size: 14px; }
        .cuaca-spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid #e2e8f0; border-top-color: var(--primary-green); border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 8px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .cuaca-hasil { display: none; }
        .cuaca-hasil.tampil { display: block; }
        .cuaca-header-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px; }
        .cuaca-lokasi-label { font-size: 13px; font-weight: 600; color: var(--primary-green); background: var(--light-green); border-radius: 999px; padding: 4px 14px; }
        .cuaca-tanggal-label { font-size: 13px; color: var(--text-muted); }
        .cuaca-main-card { border-radius: 14px; padding: 24px; margin-bottom: 16px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: white; }
        .cuaca-main-card.panas { background: linear-gradient(135deg, #fb923c, #f59e0b); }
        .cuaca-main-card.hujan { background: linear-gradient(135deg, #64748b, #475569); }
        .cuaca-big-row { display: flex; align-items: center; gap: 20px; margin-bottom: 12px; }
        .cuaca-emoji-big { font-size: 56px; }
        .cuaca-suhu-big { font-size: 52px; font-weight: 700; line-height: 1; }
        .cuaca-kondisi-text { font-size: 15px; opacity: 0.9; text-transform: capitalize; margin-top: 4px; }
        .cuaca-stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .cuaca-stat { background: rgba(255,255,255,0.18); border-radius: 10px; padding: 10px; text-align: center; }
        .cuaca-stat-label { font-size: 11px; opacity: 0.8; margin-bottom: 4px; }
        .cuaca-stat-val { font-size: 15px; font-weight: 700; }
        .cuaca-btn-ulang { width: 100%; background: transparent; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; margin-top: 12px; transition: border-color 0.2s, color 0.2s; }
        .cuaca-btn-ulang:hover { border-color: var(--primary-green); color: var(--primary-green); }

        /* SECTION GRAFIK */
        .section-grafik { background: #f8fafc; padding: 70px 5%; }
        .grafik-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 28px; max-width: 800px; margin: 0 auto; }
        .grafik-search-wrap { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .grafik-select { flex: 1; min-width: 200px; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 14px; font-family: 'Poppins', sans-serif; color: #1e293b; background: #f8fafc; outline: none; }
        .grafik-select:focus { border-color: var(--primary-green); background: #fff; }
        .grafik-btn-cari { background: linear-gradient(135deg, var(--primary-green), var(--primary-hover)); color: white; border: none; border-radius: 10px; padding: 10px 22px; font-size: 14px; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer; white-space: nowrap; transition: opacity 0.2s, transform 0.1s; }
        .grafik-btn-cari:hover { opacity: 0.9; }
        .grafik-btn-cari:active { transform: scale(0.97); }
        .grafik-btn-cari:disabled { opacity: 0.6; cursor: not-allowed; }
        .grafik-tabs { display: flex; gap: 8px; margin-bottom: 18px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 12px; }
        .grafik-tab { background: transparent; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 6px 18px; font-size: 13px; font-family: 'Poppins', sans-serif; cursor: pointer; color: var(--text-muted); font-weight: 500; transition: all 0.15s; }
        .grafik-tab.aktif { background: var(--primary-green); color: white; border-color: var(--primary-green); }
        .grafik-tab:hover:not(.aktif) { background: var(--light-green); border-color: var(--primary-green); color: var(--primary-green); }
        .grafik-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .grafik-metric { background: var(--light-green); border: 1px solid #c8e6c9; border-radius: 12px; padding: 14px 10px; text-align: center; }
        .grafik-metric-icon { font-size: 18px; margin-bottom: 4px; }
        .grafik-metric-label { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
        .grafik-metric-value { font-size: 18px; font-weight: 700; color: var(--primary-green); }
        .grafik-canvas-wrap { position: relative; width: 100%; height: 300px; }
        .grafik-legend { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 14px; font-size: 12px; color: var(--text-muted); }
        .grafik-legend-item { display: flex; align-items: center; gap: 6px; }
        .grafik-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }
        .grafik-status { text-align: center; padding: 40px 0; color: #94a3b8; font-size: 14px; }
        .grafik-error { color: #e53e3e; }
        .grafik-spinner { display: inline-block; width: 20px; height: 20px; border: 3px solid #e2e8f0; border-top-color: var(--primary-green); border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 8px; }
        .grafik-kota-label { font-size: 12px; color: var(--primary-green); background: var(--light-green); border-radius: 999px; padding: 3px 14px; display: inline-block; margin-bottom: 14px; font-weight: 600; }

        /* SECTION IKLIM */
        .section-iklim { background: #ffffff; padding: 70px 5%; }
        .iklim-wrap { max-width: 900px; margin: 0 auto; }
        .iklim-loading { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }
        .iklim-error-box { background: #fff5f5; border: 1px solid #fed7d7; border-radius: 12px; padding: 20px; text-align: center; color: #e53e3e; font-size: 14px; }
        .iklim-judul { font-size: 14px; font-weight: 600; color: var(--text-dark); text-align: center; margin-bottom: 20px; padding: 14px 20px; background: var(--light-green); border-radius: 10px; border-left: 4px solid var(--primary-green); }
        .iklim-table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
        .iklim-table-wrap table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .iklim-table-wrap table th { background: #1b4332; color: white; padding: 12px 16px; text-align: center; font-weight: 600; }
        .iklim-table-wrap table td { padding: 10px 16px; border-bottom: 1px solid #f1f5f9; color: var(--text-dark); }
        .iklim-table-wrap table tr:last-child td { border-bottom: none; }
        .iklim-table-wrap table tr:nth-child(even) td { background: #f8fafc; }
        .iklim-table-wrap table tr:hover td { background: var(--light-green); transition: background 0.2s; }
        .iklim-sumber { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 14px; }

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
            .nav-menu { position: fixed; top: 70px; left: -100%; flex-direction: column; background-color: var(--white); width: 100%; text-align: center; transition: 0.4s; box-shadow: 0 10px 15px rgba(0,0,0,0.05); padding: 30px 0; gap: 25px; }
            .nav-menu.active { left: 0; }
            .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
            .hamburger.active span:nth-child(2) { opacity: 0; }
            .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px,-6px); }
            .hero { padding-top: 80px; text-align: center; min-height: 60vh; }
            .hero-content { margin: 0 auto; }
            .hero-title { font-size: 28px; }
            .features { grid-template-columns: 1fr; margin-top: 20px; gap: 20px; }
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

<?php include 'section_cuaca.php'; ?>
<?php include 'section_grafik.php'; ?>
<?php include 'iklim.php'; ?>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
</footer>

<script>
// NAVBAR
const hamburgerBtn = document.getElementById('hamburgerBtn');
const navMenu      = document.getElementById('navMenu');
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
</script>

</body>
</html>