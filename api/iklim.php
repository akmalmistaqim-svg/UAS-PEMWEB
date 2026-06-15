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
    <title>Prediksi Cuaca - ACC</title>
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
            --sky: #0ea5e9;
            --sky-light: #e0f2fe;
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
        .logo-container { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-text h1 { font-size: 20px; color: var(--primary-green); font-weight: 700; line-height: 1; }
        .logo-text span { font-size: 11px; color: var(--text-muted); letter-spacing: 0.5px; }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .user-name { font-size: 14px; color: var(--text-dark); }
        .btn-logout { background-color: var(--danger-red); color: var(--white); border: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-transform: uppercase; transition: background 0.3s; text-decoration: none; display: inline-block; }
        .btn-logout:hover { background-color: #c53030; color: var(--white); }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.2s; }
        .btn-back:hover { color: var(--primary-green); }

        /* HERO BANNER */
        .cuaca-hero {
            margin-top: 65px;
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #7dd3fc 100%);
            padding: 50px 5% 80px 5%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cuaca-hero::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            top: -120px; right: -80px;
        }
        .cuaca-hero::after {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            bottom: -80px; left: -40px;
        }
        .cuaca-hero-icon { font-size: 60px; margin-bottom: 14px; display: block; }
        .cuaca-hero h1 { font-size: 32px; font-weight: 700; color: white; margin-bottom: 10px; text-shadow: 0 2px 8px rgba(0,0,0,0.10); }
        .cuaca-hero p { font-size: 14px; color: rgba(255,255,255,0.88); }

        /* MAIN CONTENT */
        .cuaca-main { max-width: 720px; margin: -40px auto 60px auto; padding: 0 20px; position: relative; z-index: 10; }

        /* FORM CARD */
        .cuaca-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .cuaca-card-header {
            background: var(--light-green);
            padding: 20px 28px;
            border-bottom: 1px solid #d1fae5;
        }
        .cuaca-card-header h3 { font-size: 15px; font-weight: 700; color: var(--primary-green); margin-bottom: 2px; }
        .cuaca-card-header p { font-size: 12px; color: var(--text-muted); }

        .cuaca-card-body { padding: 28px; }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600; color: var(--text-dark);
            margin-bottom: 8px;
        }
        .form-label span { font-size: 16px; }

        .cuaca-select, .cuaca-input-tanggal {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }
        .cuaca-select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; background-size: 12px; padding-right: 40px; cursor: pointer; }
        .cuaca-select:focus, .cuaca-input-tanggal:focus { border-color: var(--sky); background: white; box-shadow: 0 0 0 3px rgba(14,165,233,0.10); }

        .cuaca-btn-cek {
            width: 100%;
            background: linear-gradient(135deg, var(--sky), #0284c7);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: opacity 0.2s, transform 0.1s;
            box-shadow: 0 4px 16px rgba(14,165,233,0.30);
            margin-top: 4px;
        }
        .cuaca-btn-cek:hover { opacity: 0.92; transform: translateY(-1px); }
        .cuaca-btn-cek:active { transform: scale(0.98); }
        .cuaca-btn-cek:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        .cuaca-error { color: #e53e3e; font-size: 13px; margin-bottom: 16px; display: none; background: #fff5f5; border-radius: 8px; padding: 10px 14px; border-left: 3px solid #e53e3e; }
        .cuaca-error.tampil { display: block; }

        /* HASIL */
        .cuaca-hasil-wrap { margin-top: 24px; }

        .cuaca-status {
            text-align: center;
            padding: 32px 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .cuaca-spinner {
            display: inline-block;
            width: 22px; height: 22px;
            border: 3px solid #e2e8f0;
            border-top-color: var(--sky);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .cuaca-header-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px; }
        .cuaca-lokasi-label { font-size: 13px; font-weight: 600; color: var(--sky); background: var(--sky-light); border-radius: 999px; padding: 5px 16px; }
        .cuaca-tanggal-label { font-size: 13px; color: var(--text-muted); }

        .cuaca-main-card { border-radius: 16px; padding: 26px; margin-bottom: 16px; background: linear-gradient(135deg, #38bdf8, #0ea5e9); color: white; }
        .cuaca-main-card.panas { background: linear-gradient(135deg, #fb923c, #f59e0b); }
        .cuaca-main-card.hujan { background: linear-gradient(135deg, #64748b, #475569); }
        .cuaca-big-row { display: flex; align-items: center; gap: 20px; margin-bottom: 16px; }
        .cuaca-emoji-big { font-size: 60px; }
        .cuaca-suhu-big { font-size: 56px; font-weight: 700; line-height: 1; }
        .cuaca-kondisi-text { font-size: 15px; opacity: 0.9; margin-top: 4px; }
        .cuaca-stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .cuaca-stat { background: rgba(255,255,255,0.18); border-radius: 10px; padding: 12px; text-align: center; }
        .cuaca-stat-label { font-size: 11px; opacity: 0.8; margin-bottom: 4px; }
        .cuaca-stat-val { font-size: 16px; font-weight: 700; }

        .cuaca-btn-ulang {
            width: 100%;
            background: transparent;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            margin-top: 12px;
            transition: border-color 0.2s, color 0.2s;
        }
        .cuaca-btn-ulang:hover { border-color: var(--sky); color: var(--sky); }

        /* FOOTER */
        footer { background: white; text-align: center; padding: 24px; font-size: 13px; color: var(--text-muted); border-top: 1px solid rgba(0,0,0,0.05); }

        @media (max-width: 600px) {
            .cuaca-hero h1 { font-size: 24px; }
            .cuaca-hero-icon { font-size: 46px; }
            .cuaca-card-body { padding: 20px; }
            .cuaca-main { margin-top: -30px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/api/frontend/dashboard.php" class="logo-container">
        <img src="/LogoWeb.png" alt="ACC Logo" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        <div class="logo-text">
            <h1>ACC</h1>
            <span>Agro Clima Care</span>
        </div>
    </a>

    <div class="user-profile">
        <a href="/api/frontend/dashboard.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <span class="user-name">Halo, <strong><?php echo htmlspecialchars($nama_display); ?></strong></span>
        <a href="/api/frontend/logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<!-- HERO -->
<div class="cuaca-hero">
    <span class="cuaca-hero-icon">🌤️</span>
    <h1>Prediksi Cuaca Jawa Timur</h1>
    <p>Pilih kota dan tanggal untuk melihat prediksi cuaca harian yang akurat</p>
</div>

<!-- MAIN FORM -->
<div class="cuaca-main">
    <div class="cuaca-card">
        <div class="cuaca-card-header">
            <h3>📍 Isi Data Pencarian</h3>
            <p>Pilih kota dan tanggal yang ingin dicek cuacanya</p>
        </div>
        <div class="cuaca-card-body">
            <div class="form-group">
                <label class="form-label"><span>📍</span> Daerah / Kota</label>
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
            </div>

            <div class="form-group">
                <label class="form-label"><span>📅</span> Tanggal Prediksi</label>
                <input type="date" id="cuacaInputTanggal" class="cuaca-input-tanggal">
            </div>

            <div class="cuaca-error" id="cuacaError">⚠️ Pilih kota dan tanggal terlebih dahulu.</div>

            <button class="cuaca-btn-cek" id="cuacaBtnCek" onclick="cekCuaca()">
                <i class="fa-solid fa-magnifying-glass"></i> Lihat Prediksi Cuaca
            </button>

            <!-- HASIL -->
            <div class="cuaca-hasil-wrap" id="cuacaKonten">
                <div class="cuaca-status">☁️ Pilih kota dan tanggal untuk melihat prediksi cuaca</div>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Website Agro Clima Care (ACC). Semua Hak Dilindungi.</p>
</footer>

<script>
// Set tanggal default hari ini
(function() {
    const today = new Date().toISOString().split('T')[0];
    const input = document.getElementById('cuacaInputTanggal');
    input.value = today;
    input.min = today;
    input.max = new Date(Date.now() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
})();

var namaHariArr  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
var namaBulanArr = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

function formatTanggalIndo(str) {
    var d = new Date(str);
    return namaHariArr[d.getDay()] + ', ' + d.getDate() + ' ' + namaBulanArr[d.getMonth()] + ' ' + d.getFullYear();
}

async function cekCuaca() {
    var select  = document.getElementById('cuacaSelectKota');
    var tanggal = document.getElementById('cuacaInputTanggal').value;
    var err     = document.getElementById('cuacaError');

    if (!select.value || !tanggal) { err.classList.add('tampil'); return; }
    err.classList.remove('tampil');

    var parts = select.value.split(',');
    var lat = parts[0], lon = parts[1], kota = parts[2];

    var btn = document.getElementById('cuacaBtnCek');
    btn.disabled = true;
    btn.innerHTML = '<span class="cuaca-spinner"></span> Mengambil data...';

    document.getElementById('cuacaKonten').innerHTML =
        '<div class="cuaca-status"><span class="cuaca-spinner"></span> Mengambil data cuaca ' + kota + '...</div>';

    try {
        var res  = await fetch(
            'https://api.open-meteo.com/v1/forecast?' +
            'latitude=' + lat + '&longitude=' + lon +
            '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean,windspeed_10m_max,weathercode' +
            '&timezone=Asia%2FJakarta&forecast_days=7'
        );
        var data  = await res.json();
        var daily = data.daily;

        var idx = daily.time.indexOf(tanggal);
        if (idx === -1) idx = 0;

        var suhuMax = Math.round(daily.temperature_2m_max[idx]);
        var suhuMin = Math.round(daily.temperature_2m_min[idx]);
        var hujan   = Math.round(daily.precipitation_sum[idx] * 10) / 10;
        var rh      = Math.round(daily.relative_humidity_2m_mean[idx]);
        var angin   = Math.round(daily.windspeed_10m_max[idx]);
        var wcode   = daily.weathercode[idx];

        var emoji, kondisi, cardClass = '';
        if      (wcode === 0) { emoji = '☀️';  kondisi = 'Cerah'; }
        else if (wcode <= 2)  { emoji = '🌤️'; kondisi = 'Cerah Berawan'; }
        else if (wcode <= 3)  { emoji = '☁️';  kondisi = 'Berawan'; }
        else if (wcode <= 48) { emoji = '🌫️'; kondisi = 'Berkabut'; }
        else if (wcode <= 57) { emoji = '🌦️'; kondisi = 'Gerimis'; }
        else if (wcode <= 67) { emoji = '🌧️'; kondisi = 'Hujan';       cardClass = 'hujan'; }
        else if (wcode <= 77) { emoji = '🌨️'; kondisi = 'Hujan Es';    cardClass = 'hujan'; }
        else if (wcode <= 82) { emoji = '🌧️'; kondisi = 'Hujan Lebat'; cardClass = 'hujan'; }
        else if (wcode <= 99) { emoji = '⛈️';  kondisi = 'Badai Petir'; cardClass = 'hujan'; }
        else                  { emoji = '🌤️'; kondisi = 'Cuaca Campuran'; }

        if (!cardClass && suhuMax >= 33) cardClass = 'panas';

        document.getElementById('cuacaKonten').innerHTML = `
            <div style="margin-top: 24px;">
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
            </div>
        `;
    } catch(e) {
        document.getElementById('cuacaKonten').innerHTML =
            '<div class="cuaca-status" style="color:#e53e3e;">❌ Gagal mengambil data: ' + e.message + '</div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Lihat Prediksi Cuaca';
    }
}

function resetCuaca() {
    document.getElementById('cuacaKonten').innerHTML =
        '<div class="cuaca-status">☁️ Pilih kota dan tanggal untuk melihat prediksi cuaca</div>';
    document.getElementById('cuacaSelectKota').value = '';
}
</script>

</body>
</html>