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

async function cekCuacaDashboard() {
    var select  = document.getElementById('cuacaSelectKota');
    var tanggal = document.getElementById('cuacaInputTanggal').value;
    var err     = document.getElementById('cuacaError');

    if (!select.value || !tanggal) { err.classList.add('tampil'); return; }
    err.classList.remove('tampil');

    var parts = select.value.split(',');
    var lat = parts[0], lon = parts[1], kota = parts[2];

    var btn = document.getElementById('cuacaBtnCek');
    btn.disabled = true;
    btn.innerHTML = '<span class="cuaca-spinner"></span>';

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
</script>