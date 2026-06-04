<?php
// dashboard.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACC - Agro Clima Care Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
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

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-text h1 {
            font-size: 20px;
            color: var(--primary-green);
            font-weight: 700;
            line-height: 1;
        }

        .logo-text span {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 20px;
            list-style: none;
        }

        .nav-item a {
            text-decoration: none;
            color: var(--text-dark);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            transition: color 0.3s;
        }

        .nav-item a:hover, .nav-item.active a {
            color: var(--primary-green);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 14px;
            color: var(--text-dark);
        }

        .btn-logout {
            background-color: var(--danger-red);
            color: var(--white);
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.3s;
        }

        .btn-logout:hover, .btn-logout:active {
            background-color: #c53030;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
        }

        .hamburger span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: var(--primary-green);
            border-radius: 3px;
            transition: 0.3s;
        }

        /* --- HERO SECTION --- */
        .hero {
            margin-top: 75px;
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            padding: 50px 5%;
            background: linear-gradient(rgba(244, 249, 245, 0.70), rgba(244, 249, 245, 0.70)), url('../fotopetani.jpg.jpeg') no-repeat center center/cover;
        }

        .hero-content {
            max-width: 650px;
        }

        .hero-title {
            font-size: 36px;
            color: var(--primary-green);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .hero-description {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-cta {
            background-color: var(--primary-green);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
            transition: transform 0.3s, background-color 0.3s;
        }

        .btn-cta:hover, .btn-cta:active {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* --- FEATURES SECTION --- */
        .features {
            padding: 40px 5% 60px 5%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .card {
            background-color: var(--white);
            padding: 35px 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 40px;
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-text {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* --- FOOTER --- */
        footer {
            background-color: var(--white);
            text-align: center;
            padding: 25px;
            font-size: 13px;
            color: var(--text-muted);
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 992px) {
            .features {
                grid-template-columns: repeat(2, 1fr);
                margin-top: -20px;
            }
        }

        @media (max-width: 768px) {
            .hamburger { display: flex; order: 2; }
            .user-profile { order: 3; }
            .user-name { display: none; }
            .nav-menu {
                position: fixed;
                top: 70px; left: -100%;
                flex-direction: column;
                background-color: var(--white);
                width: 100%; text-align: center;
                transition: 0.4s;
                box-shadow: 0 10px 15px rgba(0,0,0,0.05);
                padding: 30px 0; gap: 25px;
            }
            .nav-menu.active { left: 0; }
            .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
            .hamburger.active span:nth-child(2) { opacity: 0; }
            .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }

            .hero { padding-top: 80px; text-align: center; min-height: 60vh; }
            .hero-content { margin: 0 auto; }
            .hero-title { font-size: 28px; }
            .features { grid-template-columns: 1fr; margin-top: 20px; gap: 20px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="../LogoWeb.png" alt="ACC Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <div class="logo-text">
                <h1>ACC</h1>
                <span>Agro Clima Care</span>
            </div>
        </div>

        <ul class="nav-menu" id="navMenu">
            <li class="nav-item active"><a href="dashboard.php">Beranda</a></li>
            <li class="nav-item"><a href="cekpenyakit.php">Identifikasi Penyakit</a></li>
            <li class="nav-item"><a href="infopenyakit.php">Info Penyakit</a></li>
            <li class="nav-item"><a href="hasildiagnosa.php">Hasil Diagnosa</a></li>
            
        </ul>

        <div class="user-profile">
            <span class="user-name">Halo, <strong>Petani</strong></span>
            <button class="btn-logout" onclick="window.location.href='loginpage.php'">Logout</button>
        </div>

        <div class="hamburger" id="hamburgerBtn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h2 class="hero-title">DETEKSI DINI & KENDALIKAN PENYAKIT TANAMAN ANDA</h2>
            <p class="hero-description">Platform Digital Pintar untuk Diagnosis Akurat, Penanganan Efektif, dan Peningkatan Hasil Panen Petani Indonesia.</p>
            <button class="btn-cta" onclick="window.location.href='cekpenyakit.php'">
                <i class="fa-solid fa-camera"></i> Mulai Identifikasi Sekarang
            </button>
        </div>
    </section>

    <section class="features">
        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-leaf"></i></div>
            <h3 class="card-title">Identifikasi Penyakit</h3>
            <p class="card-text">Unggah foto tanaman Anda untuk diagnosis cepat</p>
        </div>

        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-book-open-reader"></i></div>
            <h3 class="card-title">Info Penyakit</h3>
            <p class="card-text">Temukan informasi lainnya tentang penyakit tanaman, gejala, dan cara penanganan</p>
        </div>

        <div class="card">
            <div class="card-icon"><i class="fa-solid fa-cloud-sun-rain"></i></div>
            <h3 class="card-title">Cek Prediksi Cuaca</h3>
            <p class="card-text">Cek prediksi cuaca lokal di Wilayah Jawa Timur</p>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
    </footer>

    <script>
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
    </script>
</body>
</html>