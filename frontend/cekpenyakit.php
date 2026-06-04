<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identifikasi Penyakit - ACC</title>
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

        /* --- CAMERA SECTION --- */
        .content-area {
            flex: 1;
            padding: 100px 5% 50px; /* Offset dari navbar fixed */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .camera-card {
            background-color: var(--white);
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            text-align: center;
        }

        .camera-card h2 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 800;
        }

        .camera-card p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 25px;
        }

        .video-container {
            width: 100%;
            aspect-ratio: 4/3;
            background-color: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 25px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px dashed #cbd5e1;
        }

        /* Style untuk video stream dan hasil foto (canvas) */
        #camera-stream, #camera-canvas {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none; /* Disembunyikan awalnya */
        }

        .placeholder-icon {
            font-size: 60px;
            color: #94a3b8;
        }

        /* --- BUTTON CONTROLS --- */
        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-green);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }

        .btn-primary:hover, .btn-primary:active {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* Tombol yang awalnya disembunyikan */
        #btn-snap, #btn-retake, #btn-send {
            display: none;
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
            
            .camera-card {
                padding: 20px;
            }
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
            <li class="nav-item"><a href="dashboard.html">Beranda</a></li>
            <li class="nav-item active"><a href="cekpenyakit.html">Identifikasi Penyakit</a></li>
            <li class="nav-item"><a href="infopenyakit.html">Info Penyakit</a></li>
            <li class="nav-item"><a href="#">Hasil Diagnosa</a></li>
            <li class="nav-item"><a href="#">Cek Cuaca</a></li>
        </ul>

        <div class="user-profile">
            <span class="user-name">Halo, <strong>Petani</strong></span>
            <button class="btn-logout" onclick="window.location.href='loginpage.html'">Logout</button>
        </div>

        <div class="hamburger" id="hamburgerBtn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <div class="content-area">
        <div class="camera-card">
            <h2>Deteksi Penyakit</h2>
            <p>Arahkan kamera ke bagian daun atau tanaman yang terindikasi sakit, lalu jepret foto dengan jelas.</p>

            <div class="video-container">
                <i class="fa-solid fa-camera placeholder-icon" id="placeholder-icon"></i>
                
                <video id="camera-stream" autoplay playsinline></video>
                
                <canvas id="camera-canvas"></canvas>
            </div>

            <div class="controls">
                <button class="btn btn-primary" id="btn-start" onclick="startCamera()">
                    <i class="fa-solid fa-camera"></i> Buka Kamera
                </button>

                <button class="btn btn-primary" id="btn-snap" onclick="snapPhoto()">
                    <i class="fa-solid fa-circle-dot"></i> Jepret Foto
                </button>

                <button class="btn btn-secondary" id="btn-retake" onclick="retakePhoto()">
                    <i class="fa-solid fa-rotate-right"></i> Ulangi
                </button>

                <button class="btn btn-primary" id="btn-send" onclick="sendToDatabase()">
                    <i class="fa-solid fa-paper-plane"></i> Kirim
                </button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
    </footer>

    <script>
        // Logika Hamburger Menu
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

        // ==========================================
        // LOGIKA KAMERA WEBRTC
        // ==========================================
        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('camera-canvas');
        const placeholder = document.getElementById('placeholder-icon');
        
        const btnStart = document.getElementById('btn-start');
        const btnSnap = document.getElementById('btn-snap');
        const btnRetake = document.getElementById('btn-retake');
        const btnSend = document.getElementById('btn-send');

        let streamData = null; // Menyimpan data stream kamera

        // 1. Membuka Kamera
        async function startCamera() {
            try {
                // Meminta akses kamera (diutamakan kamera belakang / environment untuk HP)
                const constraints = {
                    video: { facingMode: 'environment' }
                };

                streamData = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = streamData;

                // Mengatur tampilan UI
                placeholder.style.display = 'none';
                canvas.style.display = 'none';
                video.style.display = 'block';

                btnStart.style.display = 'none';
                btnRetake.style.display = 'none';
                btnSend.style.display = 'none';
                btnSnap.style.display = 'inline-flex';

            } catch (err) {
                alert("Gagal mengakses kamera. Pastikan browser Anda memiliki izin untuk menggunakan kamera laptop/smartphone.");
                console.error("Error Akses Kamera:", err);
            }
        }

        // 2. Mengambil Foto (Snapshot)
        function snapPhoto() {
            // Samakan ukuran canvas dengan resolusi asli video stream
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Gambar frame video saat ini ke dalam canvas
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Tampilkan hasil canvas, sembunyikan live video
            video.style.display = 'none';
            canvas.style.display = 'block';

            // Ubah tombol
            btnSnap.style.display = 'none';
            btnRetake.style.display = 'inline-flex';
            btnSend.style.display = 'inline-flex';
        }

        // 3. Mengulang Pengambilan Foto
        function retakePhoto() {
            canvas.style.display = 'none';
            video.style.display = 'block';

            btnRetake.style.display = 'none';
            btnSend.style.display = 'none';
            btnSnap.style.display = 'inline-flex';
        }

        // 4. Simulasi Kirim Foto ke Database
        function sendToDatabase() {
            // Karena tidak perlu database asli, kita gunakan alert pop-up
            alert("Berhasil! Foto telah dikirim ke database untuk dianalisis.");
            
            // Setelah dikirim, matikan stream kamera agar resource hemat
            if(streamData) {
                streamData.getTracks().forEach(track => track.stop());
            }

            // Kembalikan UI ke kondisi semula
            video.style.display = 'none';
            canvas.style.display = 'none';
            placeholder.style.display = 'block';

            btnSnap.style.display = 'none';
            btnRetake.style.display = 'none';
            btnSend.style.display = 'none';
            btnStart.style.display = 'inline-flex';
        }
    </script>
</body>
</html>