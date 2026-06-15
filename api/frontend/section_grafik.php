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

<script>
(function() {
    let grafikChart     = null;
    let grafikTabAktif  = 'harian';
    let grafikDataCache = null;
    let grafikNamaKota  = '';

    window.grafikTampilkan = async function() {
        var select = document.getElementById('grafikSelectKota');
        var val    = select.value;
        if (!val) {
            document.getElementById('grafikKonten').innerHTML =
                '<div class="grafik-status grafik-error">⚠️ Pilih kota terlebih dahulu.</div>';
            return;
        }
        var [lat, lon, namaKota] = val.split(',');
        grafikNamaKota = namaKota;

        var btn = document.getElementById('grafikBtnCari');
        btn.disabled    = true;
        btn.textContent = 'Memuat...';

        document.getElementById('grafikKonten').innerHTML =
            '<div class="grafik-status"><span class="grafik-spinner"></span> Mengambil data cuaca <b>' + namaKota + '</b>...</div>';

        try {
            var today    = new Date();
            var endDate  = today.toISOString().split('T')[0];

            var start28  = new Date(today); start28.setDate(today.getDate() - 28);
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
            var [forecastData, hist28Data, histBulananData] = await Promise.all([
                forecastRes.json(), hist28Res.json(), histBulananRes.json()
            ]);

            grafikDataCache = {
                harian   : olahHarian(forecastData.daily),
                mingguan : olahMingguan(hist28Data.daily),
                bulanan  : olahBulanan(histBulananData.daily)
            };
            grafikTabAktif = 'harian';
            renderGrafikKonten();
        } catch(err) {
            document.getElementById('grafikKonten').innerHTML =
                '<div class="grafik-status grafik-error">❌ Gagal mengambil data: ' + err.message + '</div>';
        } finally {
            btn.disabled    = false;
            btn.textContent = '📊 Tampilkan';
        }
    };

    function olahHarian(daily) {
        var nb = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        var labels = daily.time.slice(0,7).map(t => {
            var d = new Date(t);
            return ['Min','Sen','Sel','Rab','Kam','Jum','Sab'][d.getDay()] + ' ' + d.getDate() + ' ' + nb[d.getMonth()];
        });
        return {
            labels,
            suhuMax    : daily.temperature_2m_max.slice(0,7).map(v => Math.round(v)),
            suhuMin    : daily.temperature_2m_min.slice(0,7).map(v => Math.round(v)),
            hujan      : daily.precipitation_sum.slice(0,7).map(v => Math.round(v * 10) / 10),
            kelembaban : daily.relative_humidity_2m_mean.slice(0,7).map(v => Math.round(v))
        };
    }

    function olahMingguan(daily) {
        var minggu = [[],[],[],[]];
        for (var i = 0; i < Math.min(28, daily.time.length); i++) minggu[Math.floor(i / 7)].push(i);
        var suhuMax = [], suhuMin = [], hujan = [], kelembaban = [], labels = [];
        minggu.forEach((idx, w) => {
            if (!idx.length) return;
            labels.push('Minggu ' + (w + 1));
            suhuMax.push(Math.round(avg(idx.map(i => daily.temperature_2m_max[i]))));
            suhuMin.push(Math.round(avg(idx.map(i => daily.temperature_2m_min[i]))));
            hujan.push(Math.round(idx.reduce((s, i) => s + (daily.precipitation_sum[i] || 0), 0)));
            kelembaban.push(Math.round(avg(idx.map(i => daily.relative_humidity_2m_mean[i]))));
        });
        return { labels, suhuMax, suhuMin, hujan, kelembaban };
    }

    function olahBulanan(daily) {
        if (!daily || !daily.time) return { labels:[], suhuMax:[], suhuMin:[], hujan:[], kelembaban:[] };
        var bulanMap = {};
        var nb = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        daily.time.forEach((t, i) => {
            var d = new Date(t), key = d.getFullYear() + '-' + d.getMonth();
            if (!bulanMap[key]) bulanMap[key] = { nama: nb[d.getMonth()], tMax:[], tMin:[], hujan:[], rh:[] };
            bulanMap[key].tMax.push(daily.temperature_2m_max[i]);
            bulanMap[key].tMin.push(daily.temperature_2m_min[i]);
            bulanMap[key].hujan.push(daily.precipitation_sum[i] || 0);
            bulanMap[key].rh.push(daily.relative_humidity_2m_mean[i]);
        });
        var keys = Object.keys(bulanMap).slice(-12);
        return {
            labels     : keys.map(k => bulanMap[k].nama),
            suhuMax    : keys.map(k => Math.round(avg(bulanMap[k].tMax))),
            suhuMin    : keys.map(k => Math.round(avg(bulanMap[k].tMin))),
            hujan      : keys.map(k => Math.round(bulanMap[k].hujan.reduce((a,b) => a+b, 0))),
            kelembaban : keys.map(k => Math.round(avg(bulanMap[k].rh)))
        };
    }

    function avg(arr) {
        var v = arr.filter(x => x !== null && x !== undefined);
        return v.length ? v.reduce((a,b) => a+b, 0) / v.length : 0;
    }

    function metricHTML(max, min, hujan, rh) {
        return `
        <div class="grafik-metric"><div class="grafik-metric-icon">🌡️</div><div class="grafik-metric-label">Suhu Maks</div><div class="grafik-metric-value">${max}°C</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">🌡️</div><div class="grafik-metric-label">Suhu Min</div><div class="grafik-metric-value">${min}°C</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">🌧️</div><div class="grafik-metric-label">Total Hujan</div><div class="grafik-metric-value">${hujan} mm</div></div>
        <div class="grafik-metric"><div class="grafik-metric-icon">💧</div><div class="grafik-metric-label">Kelembaban</div><div class="grafik-metric-value">${rh}%</div></div>`;
    }

    function renderGrafikKonten() {
        var d    = grafikDataCache[grafikTabAktif];
        var max  = Math.round(avg(d.suhuMax));
        var min  = Math.round(avg(d.suhuMin));
        var hujan = d.hujan.reduce((a,b) => a+b, 0).toFixed(1);
        var rh   = Math.round(avg(d.kelembaban));

        document.getElementById('grafikKonten').innerHTML = `
            <span class="grafik-kota-label">📍 ${grafikNamaKota}, Jawa Timur</span>
            <div class="grafik-metrics" id="grafikMetrics">${metricHTML(max, min, hujan, rh)}</div>
            <div class="grafik-tabs">
                <button class="grafik-tab ${grafikTabAktif==='harian'?'aktif':''}"   onclick="grafikGantiTab('harian',this)">Harian</button>
                <button class="grafik-tab ${grafikTabAktif==='mingguan'?'aktif':''}" onclick="grafikGantiTab('mingguan',this)">Mingguan</button>
                <button class="grafik-tab ${grafikTabAktif==='bulanan'?'aktif':''}"  onclick="grafikGantiTab('bulanan',this)">Bulanan</button>
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
        document.querySelectorAll('.grafik-tab').forEach(b => b.classList.remove('aktif'));
        el.classList.add('aktif');
        var d     = grafikDataCache[tab];
        var max   = Math.round(avg(d.suhuMax));
        var min   = Math.round(avg(d.suhuMin));
        var hujan = d.hujan.reduce((a,b) => a+b, 0).toFixed(1);
        var rh    = Math.round(avg(d.kelembaban));
        document.getElementById('grafikMetrics').innerHTML = metricHTML(max, min, hujan, rh);
        renderChart();
    };

    function renderChart() {
        var d   = grafikDataCache[grafikTabAktif];
        var ctx = document.getElementById('grafikCanvas');
        if (!ctx) return;
        if (grafikChart) { grafikChart.destroy(); grafikChart = null; }
        grafikChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    { label:'Suhu Maks (°C)', data:d.suhuMax, type:'line', borderColor:'#4CAF50', backgroundColor:'rgba(76,175,80,0.1)', pointBackgroundColor:'#4CAF50', borderWidth:2, pointRadius:4, tension:0.35, yAxisID:'yTemp', fill:false, order:1 },
                    { label:'Suhu Min (°C)',  data:d.suhuMin, type:'line', borderColor:'#81c784', backgroundColor:'rgba(129,199,132,0.08)', pointBackgroundColor:'#81c784', borderWidth:1.5, pointRadius:3, borderDash:[4,3], tension:0.35, yAxisID:'yTemp', fill:false, order:1 },
                    { label:'Curah Hujan (mm)', data:d.hujan, type:'bar', backgroundColor:'rgba(14,165,233,0.6)', borderColor:'#0ea5e9', borderWidth:0, borderRadius:4, yAxisID:'yHujan', order:2 },
                    { label:'Kelembaban (%)', data:d.kelembaban, type:'line', borderColor:'#f59e0b', backgroundColor:'transparent', pointBackgroundColor:'#f59e0b', borderWidth:1.5, borderDash:[3,3], pointRadius:3, tension:0.3, yAxisID:'yTemp', fill:false, order:0 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode:'index', intersect:false },
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: function(ctx) {
                        var l = ctx.dataset.label || '';
                        if (l.includes('Hujan'))     return ' ' + l + ': ' + ctx.parsed.y + ' mm';
                        if (l.includes('Kelembaban')) return ' ' + l + ': ' + ctx.parsed.y + '%';
                        return ' ' + l + ': ' + ctx.parsed.y + '°C';
                    }}}
                },
                scales: {
                    yTemp  : { type:'linear', position:'left',  min:0, max:100, ticks:{ font:{size:11}, color:'#94a3b8', callback: v => v <= 50 ? v+'°' : v+'%' }, grid:{ color:'rgba(148,163,184,0.12)' } },
                    yHujan : { type:'linear', position:'right', min:0, ticks:{ font:{size:11}, color:'#0ea5e9', callback: v => v+' mm' }, grid:{ drawOnChartArea:false } },
                    x      : { ticks:{ font:{size:11}, color:'#94a3b8', autoSkip:false, maxRotation:45 }, grid:{ color:'rgba(148,163,184,0.08)' } }
                }
            }
        });
    }
})();
</script>