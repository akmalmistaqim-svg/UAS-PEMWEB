// ===== INIT =====
document.addEventListener('DOMContentLoaded', function () {

    // Flatpickr
    flatpickr("#inputTanggal_display", {
        dateFormat: "d/m/Y",
        disableMobile: true,
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                var d = selectedDates[0];
                var yyyy = d.getFullYear();
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var dd = String(d.getDate()).padStart(2, '0');
                document.getElementById('inputTanggal').value = yyyy + '-' + mm + '-' + dd;
            }
        }
    });

    // Ambil daftar kota
        fetch('/api/ambilkota.php')
         .then(res => res.json())
            .then(data => {
            var select = document.getElementById('inputDaerah');
              data.forEach(function (item) {
            if (item.domain_name === 'Jawa Timur') return;
            var option = document.createElement('option');
            option.value = item.domain_name.replace('Kabupaten ', '').replace('Kota ', '');
            option.textContent = item.domain_name;
            select.appendChild(option);
        });
    })
    .catch(err => {
        console.error('Gagal ambil daftar kota:', err);
    });

    // Hamburger
    var hamburgerBtn = document.getElementById('hamburgerBtn');
    var navMenu = document.getElementById('navMenu');
    hamburgerBtn.addEventListener('click', function () {
        hamburgerBtn.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
    document.querySelectorAll('.nav-item a').forEach(function (link) {
        link.addEventListener('click', function () {
            hamburgerBtn.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
});

// ===== FORMAT TANGGAL =====
var namaHari  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
var namaBulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

function formatTanggal(tanggalStr) {
    var d = new Date(tanggalStr);
    return namaHari[d.getDay()] + ", " + d.getDate() + " " + namaBulan[d.getMonth()] + " " + d.getFullYear();
}

// ===== CEK PREDIKSI =====
function cekPrediksi() {
    var daerah  = document.getElementById('inputDaerah').value;
    var tanggal = document.getElementById('inputTanggal').value;
    var error   = document.getElementById('pesanError');

    if (daerah === '' || tanggal === '') {
        error.classList.add('tampil');
        return;
    }
    error.classList.remove('tampil');

    fetch('/api/ambilcuaca.php?kota=' + encodeURIComponent(daerah))
        .then(res => res.json())
        .then(cuaca => {
            if (cuaca.error) { alert('Gagal ambil data cuaca: ' + cuaca.error); return; }

            var list = cuaca.list;
            var idx  = 0;
            if (tanggal && list) {
                for (var i = 0; i < list.length; i++) {
                    if (list[i].dt_txt.startsWith(tanggal)) { idx = i; break; }
                }
            }
            if (!list || !list[idx]) idx = list.length - 1;

            var item     = list[idx];
            var suhu     = Math.round(item.main.temp);
            var lembab   = item.main.humidity;
            var angin    = Math.round(item.wind.speed * 3.6);
            var kondisi  = item.weather[0].description;
            var hujan    = item.clouds.all;
            var iconCode = item.weather[0].icon;

            var emoji = "⛅";
            if (iconCode.includes('01')) emoji = "☀️";
            else if (iconCode.includes('02') || iconCode.includes('03')) emoji = "🌤️";
            else if (iconCode.includes('04')) emoji = "☁️";
            else if (iconCode.includes('09') || iconCode.includes('10')) emoji = "🌧️";
            else if (iconCode.includes('11')) emoji = "⛈️";

            document.getElementById('hasilLokasi').textContent  = '📍 ' + daerah + ', Jawa Timur';
            document.getElementById('hasilTanggal').textContent = formatTanggal(tanggal);
            document.getElementById('hasilEmoji').textContent   = emoji;
            document.getElementById('hasilSuhu').textContent    = suhu + '°';
            document.getElementById('hasilKondisi').textContent = kondisi;
            document.getElementById('hasilLembab').textContent  = lembab + '%';
            document.getElementById('hasilAngin').textContent   = angin + ' km/j';
            document.getElementById('hasilHujan').textContent   = hujan + '%';
            document.getElementById('hasilUV').textContent      = item.main.pressure + ' hPa';

            var card = document.getElementById('cardUtama');
            card.className = 'card-utama';
            if (hujan >= 70) card.classList.add('hujan');
            else if (suhu >= 33) card.classList.add('panas');

            tampilkanRekomendasiPetani(suhu, lembab, hujan, angin, kondisi, emoji);

            document.getElementById('hasilPrediksi').classList.add('tampil');
            document.getElementById('hasilPrediksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(err => { alert('Terjadi kesalahan: ' + err); });
}

// ===== CEK LAGI =====
function cekLagi() {
    document.getElementById('hasilPrediksi').classList.remove('tampil');
    document.getElementById('inputDaerah').value          = '';
    document.getElementById('inputTanggal').value         = '';
    document.getElementById('inputTanggal_display').value = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== TAB REKOMENDASI =====
function setTabRek(tab) {
    document.getElementById('rekMinggu').style.display = tab === 'minggu' ? 'block' : 'none';
    document.getElementById('rekBulan').style.display  = tab === 'bulan'  ? 'block' : 'none';
    document.getElementById('tabMinggu').className = 'rek-tab' + (tab === 'minggu' ? ' aktif' : '');
    document.getElementById('tabBulan').className  = 'rek-tab' + (tab === 'bulan'  ? ' aktif' : '');
}

// ===== HELPER KARTU & AKTIVITAS =====
function buatKartu(bg, border, icon, warnaBadge, warnaTextBadge, labelBadge, warnaJudul, judul, warnaIsi, isi) {
    return '<div style="background:' + bg + ';border:0.5px solid ' + border + ';border-radius:12px;padding:14px 16px;display:flex;gap:14px;align-items:flex-start;">' +
        '<i class="' + icon + '" style="font-size:22px;color:' + warnaIsi + ';margin-top:2px;flex-shrink:0;"></i>' +
        '<div><span style="display:inline-block;font-size:11px;font-weight:600;padding:3px 10px;border-radius:999px;margin-bottom:6px;background:' + warnaBadge + ';color:' + warnaTextBadge + ';">' + labelBadge + '</span>' +
        '<p style="font-size:14px;font-weight:600;color:' + warnaJudul + ';margin:0 0 4px;">' + judul + '</p>' +
        '<p style="font-size:13px;color:' + warnaIsi + ';margin:0;line-height:1.65;">' + isi + '</p></div></div>';
}

function buatAktivitas(warnaDot, judul, isi) {
    return '<div style="display:flex;align-items:flex-start;gap:10px;padding:10px 16px;">' +
        '<span style="width:8px;height:8px;border-radius:50%;background:' + warnaDot + ';flex-shrink:0;margin-top:5px;"></span>' +
        '<div><p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 2px;">' + judul + '</p>' +
        '<p style="font-size:12px;color:#6b7280;margin:0;line-height:1.6;">' + isi + '</p></div></div>';
}

// ===== REKOMENDASI PETANI =====
function tampilkanRekomendasiPetani(suhu, lembab, hujan, angin, kondisi, emoji) {

    // Banner minggu
    document.getElementById('rekEmojiBanner').textContent  = emoji;
    document.getElementById('rekJudulBanner').textContent  = 'Kondisi minggu ini: ' + kondisi;
    document.getElementById('rekDetailBanner').textContent = suhu + '°C · Kelembaban ' + lembab + '% · Peluang hujan ' + hujan + '% · Angin ' + angin + ' km/j';

    // Banner bulan
    var judulBulan  = hujan >= 70 ? 'Prediksi bulan depan: Musim hujan, waspadai banjir lahan'
                    : suhu >= 33  ? 'Prediksi bulan depan: Kemarau panas, irigasi jadi kunci'
                    : 'Prediksi bulan depan: Awal musim kemarau ringan';
    var detailBulan = hujan >= 70 ? 'Hujan masih sering · Drainase harus siap'
                    : suhu >= 33  ? 'Suhu tinggi · Kelembaban turun · Siram lebih sering'
                    : 'Lebih cerah · Suhu naik · Potensi hujan di akhir bulan';
    document.getElementById('rekJudulBulan').textContent  = judulBulan;
    document.getElementById('rekDetailBulan').textContent = detailBulan;

    // Kartu minggu
    var kartuMinggu = [];
    if (lembab >= 80) {
        kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-droplet','#c0dd97','#27500a','Penyiraman','#27500a','Kurangi frekuensi siram','#3b6d11',
            'Kelembaban ' + lembab + '%, tanah masih cukup lembap. Siram hanya jika tanah kering saat disentuh.'));
    } else if (lembab <= 50 || suhu >= 33) {
        kartuMinggu.push(buatKartu('#faeeda','#fac775','fa-solid fa-droplet','#fac775','#412402','Penyiraman','#412402','Siram lebih sering dari biasanya','#633806',
            'Suhu ' + suhu + '°C dan kelembaban rendah membuat tanah cepat kering. Siram pagi dan sore hari.'));
    } else {
        kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-droplet','#c0dd97','#27500a','Penyiraman','#27500a','Siram normal seperti biasa','#3b6d11',
            'Kondisi kelembaban ' + lembab + '% cukup ideal. Siram pagi pukul 06.00–07.00 atau sore 16.00–17.00.'));
    }

    if (angin <= 10 && suhu < 33 && hujan < 50) {
        kartuMinggu.push(buatKartu('#faeeda','#fac775','fa-solid fa-seedling','#fac775','#412402','Pemupukan','#412402','Waktu tepat untuk pupuk daun','#633806',
            'Suhu ' + suhu + '°C dan angin pelan (' + angin + ' km/j) cocok untuk penyemprotan pupuk daun.'));
    } else if (hujan >= 70) {
        kartuMinggu.push(buatKartu('#fcebeb','#f7c1c1','fa-solid fa-seedling','#f7c1c1','#501313','Pemupukan','#501313','Tunda pemupukan minggu ini','#791f1f',
            'Peluang hujan ' + hujan + '% terlalu tinggi. Pupuk akan terbawa air hujan sebelum terserap tanaman.'));
    }

    if (lembab >= 75) {
        kartuMinggu.push(buatKartu('#fcebeb','#f7c1c1','fa-solid fa-bug','#f7c1c1','#501313','Peringatan Hama','#501313','Risiko jamur & wereng meningkat','#791f1f',
            'Kelembaban ' + lembab + '% mempercepat tumbuhnya jamur daun dan perkembangan wereng.'));
    }

    if (hujan >= 70) {
        kartuMinggu.push(buatKartu('#e6f1fb','#b5d4f4','fa-solid fa-leaf','#b5d4f4','#042c53','Penanaman','#042c53','Tunda penanaman bibit baru','#0c447c',
            'Hujan lebat bisa merusak bibit muda. Manfaatkan waktu ini untuk menyiapkan lahan.'));
    } else if (suhu >= 26 && suhu <= 32 && lembab >= 60 && hujan < 50) {
        kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-leaf','#c0dd97','#27500a','Penanaman','#27500a','Kondisi mendukung untuk menanam','#3b6d11',
            'Suhu ' + suhu + '°C dan kelembaban ' + lembab + '% ideal untuk pertumbuhan bibit.'));
    } else {
        kartuMinggu.push(buatKartu('#faeeda','#fac775','fa-solid fa-leaf','#fac775','#412402','Penanaman','#412402','Cukup kondusif, perhatikan suhu','#633806',
            'Kondisi cukup mendukung namun perhatikan suhu ' + suhu + '°C. Tanam di pagi hari.'));
    }
    document.getElementById('rekKartuMinggu').innerHTML = kartuMinggu.join('');

    // Aktivitas minggu
    var aktMinggu = [];
    if (hujan >= 70) {
        aktMinggu.push(buatAktivitas('#a32d2d', 'Senin–Selasa: Pantau drainase lahan', 'Hujan lebat diprediksi. Pastikan saluran air tidak tersumbat agar lahan tidak tergenang.'));
    } else if (angin <= 10 && suhu < 33) {
        aktMinggu.push(buatAktivitas('#639922', 'Senin–Selasa: Semprot pupuk daun', 'Angin pelan ' + angin + ' km/j dan suhu ' + suhu + '°C ideal untuk pemupukan daun.'));
    } else if (suhu >= 33) {
        aktMinggu.push(buatAktivitas('#ba7517', 'Senin–Selasa: Siram pagi & pasang mulsa', 'Suhu ' + suhu + '°C cukup panas. Pasang mulsa untuk menjaga kelembaban tanah.'));
    } else {
        aktMinggu.push(buatAktivitas('#639922', 'Senin–Selasa: Perawatan rutin tanaman', 'Kondisi cuaca normal. Lakukan penyiraman pagi dan periksa kondisi tanaman.'));
    }

    if (lembab >= 75) {
        aktMinggu.push(buatAktivitas('#639922', 'Rabu: Penyiangan gulma', 'Kelembaban ' + lembab + '% membuat tanah lembap sehingga gulma mudah dicabut.'));
    } else if (hujan >= 70) {
        aktMinggu.push(buatAktivitas('#a32d2d', 'Rabu: Periksa kondisi tanaman', 'Setelah hujan, periksa apakah ada tanaman roboh atau akar terendam.'));
    } else {
        aktMinggu.push(buatAktivitas('#639922', 'Rabu: Penyiangan & penggemburan tanah', 'Tanah cukup kering, cocok untuk penggemburan sekaligus penyiangan gulma.'));
    }

    if (lembab >= 75 || hujan >= 50) {
        aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi hama & jamur', 'Kelembaban ' + lembab + '% meningkatkan risiko jamur dan wereng. Periksa bagian bawah daun.'));
    } else if (suhu >= 33) {
        aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi hama & stres panas', 'Suhu tinggi ' + suhu + '°C bisa memicu stres pada tanaman. Periksa daun yang mengering.'));
    } else {
        aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi rutin hama & penyakit', 'Periksa bagian bawah daun untuk kutu, ulat, atau bercak.'));
    }

    if (hujan >= 70) {
        aktMinggu.push(buatAktivitas('#a32d2d', 'Jumat: Kurangi atau tunda penyiraman', 'Hujan sudah cukup membasahi tanah. Cek kelembaban tanah sebelum memutuskan siram.'));
    } else if (lembab >= 80) {
        aktMinggu.push(buatAktivitas('#ba7517', 'Jumat: Cek tanah, siram jika perlu', 'Cek kondisi tanah dulu — jika masih lembap, tunda siram.'));
    } else {
        aktMinggu.push(buatAktivitas('#ba7517', 'Jumat: Siram tambahan sore hari', 'Kelembaban rendah ' + lembab + '%. Siram sore pukul 16.00–17.30.'));
    }

    if (hujan >= 70) {
        aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Perkuat drainase & pematang', 'Bersihkan saluran air dan perkuat pematang sawah agar tidak kebanjiran.'));
    } else if (suhu >= 33) {
        aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Siapkan sistem irigasi', 'Kemarau panas diprediksi berlanjut. Cek pompa dan selang irigasi.'));
    } else {
        aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Olah & siapkan lahan', 'Bajak tanah, tambahkan kompos. Bersihkan saluran drainase sebagai antisipasi perubahan cuaca.'));
    }
    document.getElementById('rekAktivitasMinggu').innerHTML = aktMinggu.join('');

    // Kartu bulan
    var kartuBulan = [];
    if (hujan >= 70) {
        kartuBulan.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-leaf','#c0dd97','#27500a','Tanam','#27500a','Pilih tanaman tahan air','#3b6d11',
            'Kondisi basah cocok untuk padi sawah. Pastikan drainase lahan berjalan baik sebelum tanam.'));
        kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','fa-solid fa-cloud-rain','#f7c1c1','#501313','Antisipasi','#501313','Perkuat drainase & pematang sawah','#791f1f',
            'Hujan masih sering. Bersihkan saluran air dan perkuat pematang agar lahan tidak tergenang.'));
        kartuBulan.push(buatKartu('#faeeda','#fac775','fa-solid fa-droplet','#fac775','#412402','Irigasi','#412402','Kurangi irigasi, manfaatkan air hujan','#633806',
            'Air hujan sudah cukup. Kurangi penggunaan pompa agar tidak berlebih.'));
    } else if (suhu >= 33) {
        kartuBulan.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-leaf','#c0dd97','#27500a','Tanam','#27500a','Pilih tanaman tahan panas','#3b6d11',
            'Kemarau panas cocok untuk jagung, cabai, dan kacang tanah. Siapkan benih dari sekarang.'));
        kartuBulan.push(buatKartu('#faeeda','#fac775','fa-solid fa-droplet','#fac775','#412402','Irigasi','#412402','Siram lebih sering, 1–2 hari sekali','#633806',
            'Kemarau membuat tanah cepat kering. Pastikan pompa dan selang irigasi berfungsi baik.'));
        kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','fa-solid fa-sun','#f7c1c1','#501313','Perhatian','#501313','Pasang mulsa untuk kurangi penguapan','#791f1f',
            'Suhu tinggi mempercepat penguapan. Gunakan mulsa jerami di sekitar tanaman.'));
    } else {
        kartuBulan.push(buatKartu('#eaf3de','#c0dd97','fa-solid fa-leaf','#c0dd97','#27500a','Tanam','#27500a','Cocok menanam jagung & cabai','#3b6d11',
            'Awal bulan depan ideal untuk jagung dan cabai. Siapkan benih dari sekarang.'));
        kartuBulan.push(buatKartu('#faeeda','#fac775','fa-solid fa-droplet','#fac775','#412402','Irigasi','#412402','Siapkan sistem irigasi dari sekarang','#633806',
            'Kemarau ringan membuat tanah lebih cepat kering. Jadwalkan penyiraman 2 hari sekali.'));
        kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','fa-solid fa-cloud-rain','#f7c1c1','#501313','Antisipasi','#501313','Waspadai hujan tiba-tiba di akhir bulan','#791f1f',
            'Potensi hujan meningkat di akhir bulan. Bersihkan saluran drainase dari sekarang.'));
    }
    document.getElementById('rekKartuBulan').innerHTML = kartuBulan.join('');

    // Aktivitas bulan
    var aktBulan = [];
    aktBulan.push(buatAktivitas('#639922', 'Akhir bulan ini: Olah lahan & pupuk dasar',
        'Bajak tanah dan campurkan pupuk kandang. Beri waktu 5–7 hari agar tanah siap menerima bibit.'));
    if (hujan >= 70) {
        aktBulan.push(buatAktivitas('#639922', 'Awal bulan depan: Tanam padi sawah',
            'Kondisi basah cocok untuk padi. Pastikan drainase sawah terkontrol.'));
    } else {
        aktBulan.push(buatAktivitas('#639922', 'Awal bulan depan: Tanam jagung atau cabai',
            'Tanam pagi hari saat suhu belum tinggi. Beri jarak tanam yang cukup.'));
    }
    aktBulan.push(buatAktivitas('#ba7517', 'Minggu ke-2: Siram rutin & pupuk susulan',
        'Berikan pupuk NPK susulan di minggu ke-2 untuk mendorong pertumbuhan vegetatif.'));
    aktBulan.push(buatAktivitas('#ba7517', 'Minggu ke-3: Inspeksi hama & penyakit',
        'Pantau wereng, ulat grayak, dan bercak daun. Siapkan pestisida organik jika ditemukan gejala.'));
    aktBulan.push(buatAktivitas('#185fa5', 'Akhir bulan: Bersihkan drainase & pantau cuaca',
        'Pastikan saluran air tidak tersumbat agar tanaman tidak terendam saat hujan deras.'));
    document.getElementById('rekAktivitasBulan').innerHTML = aktBulan.join('');
}