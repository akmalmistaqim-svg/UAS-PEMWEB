<?php
// hasildiagnosa.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa - ACC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            --border-color: #e2e8f0;
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        /* --- CONTENT AREA --- */
        .content-area {
            flex: 1;
            padding: 110px 5% 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-badge {
            background-color: #e2e8f0;
            color: var(--text-dark);
            padding: 8px 25px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
            display: inline-block;
        }

        /* --- REPORT CARD --- */
        .report-card {
            background-color: var(--white);
            width: 100%;
            max-width: 900px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .report-header {
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8fafc;
        }

        .report-date {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .status-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .report-body {
            padding: 30px;
            display: flex;
            gap: 40px;
        }

        .report-image-container {
            flex: 0 0 300px;
        }

        .report-image-container img {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .report-image-label {
            display: block;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
            font-weight: 500;
        }

        .report-details {
            flex: 1;
        }

        .disease-title {
            font-size: 24px;
            color: var(--primary-green);
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-section h4 {
            font-size: 15px;
            color: var(--text-dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-section h4 i {
            color: var(--primary-green);
        }

        .detail-section p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .suggestion-list {
            list-style: none;
        }

        .suggestion-list li {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
        }

        .suggestion-list li::before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-green);
            position: absolute;
            left: 0;
            top: 2px;
        }

        .report-actions {
            padding: 20px 30px;
            background-color: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary-green);
            border: 1px solid var(--primary-green);
        }

        .btn-outline:hover {
            background-color: var(--light-green);
        }

        .btn-primary {
            background-color: var(--primary-green);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* --- FOOTER --- */
        footer {
            background-color: var(--white);
            text-align: center;
            padding: 25px;
            font-size: 13px;
            color: var(--text-muted);
            border-top: 1px solid rgba(0,0,0,0.05);
            margin-top: auto;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 860px) {
            .report-body {
                flex-direction: column;
                gap: 25px;
            }
            .report-image-container {
                flex: none;
                max-width: 400px;
                margin: 0 auto;
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
            
            .content-area { padding-top: 100px; }
            .report-header { flex-direction: column; gap: 10px; align-items: flex-start; }
            .report-actions { justify-content: center; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="../LogoWeb.png" alt="ACC Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" onerror="this.style.display='none'">
            <div class="logo-text">
                <h1>ACC</h1>
                <span>Agro Clima Care</span>
            </div>
        </div>

        <ul class="nav-menu" id="navMenu">
            <li class="nav-item"><a href="dashboard.php">Beranda</a></li>
            <li class="nav-item"><a href="cekpenyakit.php">Identifikasi Penyakit</a></li>
            <li class="nav-item"><a href="infopenyakit.php">Info Penyakit</a></li>
            <li class="nav-item active"><a href="hasildiagnosa.php">Hasil Diagnosa</a></li>
            
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

    <div class="content-area">
        <div class="page-badge">HASIL LAPORAN ANDA</div>

        <div class="report-card">
            <div class="report-header">
                <div class="report-date">Dikirim pada: <strong>4 Juni 2026, 08:30 WIB</strong></div>
                <div class="status-badge">
                    <i class="fa-solid fa-circle-check"></i> Selesai Diperiksa Admin
                </div>
            </div>

            <div class="report-body">
                <div class="report-image-container">
                    <img src="https://images.unsplash.com/photo-1622383563227-04401ab4e5ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Foto Tanaman">
                    <span class="report-image-label">Foto unggahan Anda</span>
                </div>

                <div class="report-details">
                    <h3 class="disease-title">Bercak Daun (Cercospora)</h3>
                    
                    <div class="detail-section">
                        <h4><i class="fa-solid fa-microscope"></i> Catatan Admin / Analisis</h4>
                        <p>Berdasarkan foto yang dikirimkan, terlihat adanya bercak-bercak coklat melingkar dengan pusat berwarna abu-abu pada permukaan daun. Ini merupakan gejala khas dari serangan jamur <em>Cercospora sp.</em> Kondisi ini sering dipicu oleh kelembapan udara yang sangat tinggi.</p>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fa-solid fa-kit-medical"></i> Saran Penanganan</h4>
                        <ul class="suggestion-list">
                            <li>Segera pangkas dan musnahkan daun-daun yang sudah terinfeksi parah untuk mencegah penyebaran spora jamur ke daun yang sehat.</li>
                            <li>Perbaiki sirkulasi udara di sekitar area tanam dengan mengurangi kelembapan, kurangi penyiraman pada sore/malam hari.</li>
                            <li>Aplikasikan fungisida berbahan aktif <em>Mancozeb</em> atau <em>Difenokonazol</em> sesuai dengan dosis yang tertera pada kemasan produk.</li>
                            <li>Lakukan rotasi tanaman pada musim tanam berikutnya untuk memutus siklus hidup patogen di dalam tanah.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="report-actions">
                <button class="btn btn-outline" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </button>
                <button class="btn btn-primary" onclick="window.location.href='cekpenyakit.php'">
                    <i class="fa-solid fa-camera"></i> Identifikasi Lagi
                </button>
            </div>
        </div>
    </div>

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