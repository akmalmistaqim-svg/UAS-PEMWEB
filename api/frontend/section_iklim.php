<!-- ===== SECTION 3: DATA IKLIM BPS ===== -->
<style>
/* ====== LAYOUT SECTION ====== */
.section-iklim {
    padding: 50px 20px;
    background: #f4f6fa;
}

.section-iklim .section-title {
    text-align: center;
    margin-bottom: 28px;
}

.section-iklim .section-title h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.section-iklim .section-title p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.iklim-wrap {
    max-width: 900px;
    margin: 0 auto;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    padding: 24px;
    overflow-x: auto;
}

.iklim-sumber {
    text-align: center;
    color: #94a3b8;
    font-size: 0.85rem;
    margin-top: 16px;
}

/* ====== JUDUL TABEL (dari API BPS) ====== */
.iklim-judul {
    text-align: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    line-height: 1.7;
    margin-bottom: 20px;
    padding: 0 10px;
}

/* ====== TABEL ====== */
.iklim-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.iklim-table-wrap table,
.iklim-table {
    width: 100% !important;
    min-width: 100% !important;
    border-collapse: collapse;
    table-layout: auto !important;
    font-size: 13px;
}

/* Hapus inline width dari BPS yang bikin tabel overflow */
.iklim-table-wrap table[width],
.iklim-table-wrap table td[width],
.iklim-table-wrap table th[width] {
    width: auto !important;
}

.iklim-table-wrap table th {
    background: #1d4ed8;
    color: #ffffff;
    padding: 10px;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    white-space: normal;
    word-break: break-word;
}

.iklim-table-wrap table th sup {
    color: #bfdbfe;
}

.iklim-table-wrap table td {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    text-align: center;
    color: #1e293b;
    white-space: normal;
    word-break: break-word;
}

.iklim-table-wrap table tr:nth-child(even) {
    background: #f8fafc;
}

.iklim-table-wrap table tr:hover {
    background: #eff6ff;
    transition: background 0.2s;
}

/* Kolom pertama (nama unsur) rata kiri */
.iklim-table-wrap table td:first-child {
    text-align: left;
    font-weight: 500;
}

/* ====== BAGIAN CATATAN ====== */
.iklim-table-wrap table tr:has(td[colspan]) {
    background: #f8fafc !important;
}

.iklim-table-wrap table tr:has(td[colspan]):hover {
    background: #f8fafc !important;
}

.iklim-table-wrap table tr:has(td[colspan]) td {
    text-align: left !important;
    font-weight: 400 !important;
    font-style: italic;
    color: #64748b !important;
    font-size: 0.8rem !important;
    line-height: 1.8 !important;
    border: none !important;
    padding: 14px 10px !important;
}

/* Superscript angka footnote umum */
.iklim-table-wrap table sup {
    font-size: 0.65rem !important;
    color: #94a3b8 !important;
    vertical-align: super;
}

/* ====== LOADING & ERROR ====== */
.iklim-loading {
    text-align: center;
    padding: 40px 0;
    color: #64748b;
    font-size: 0.95rem;
}

.iklim-error-box {
    text-align: center;
    padding: 30px 20px;
    color: #ef4444;
    font-size: 0.95rem;
    background: #fef2f2;
    border-radius: 12px;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 768px) {
    .section-iklim .section-title h2 {
        font-size: 1.5rem;
    }
    .iklim-wrap {
        padding: 16px;
        border-radius: 0.75rem;
    }
    .iklim-judul {
        font-size: 0.95rem;
    }
    .iklim-table-wrap table th,
    .iklim-table-wrap table td {
        padding: 6px 8px;
        font-size: 12px;
    }
}
</style>

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

<script>
(async function() {
    const apiKey = "8100aa782b00c8674a151309454e0901";
    const url = `https://webapi.bps.go.id/v1/api/view/domain/3500/model/statictable/lang/ind/id/2303/key/${apiKey}`;

    function decodeHTMLEntities(str) {
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    }

    function removeInlineWidths(el) {
        // Hapus semua atribut width inline dari BPS
        el.removeAttribute('width');
        el.removeAttribute('style');
        el.querySelectorAll('[width], [style]').forEach(child => {
            child.removeAttribute('width');
            // Hapus hanya width dari style inline, sisakan yang lain
            if (child.style.width) child.style.width = '';
        });
    }

    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();
        if (!data?.data?.table) throw new Error("Tabel tidak ditemukan");

        const judul = data.data.title ?? "Data Iklim";
        const tabelDecoded = decodeHTMLEntities(data.data.table);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = tabelDecoded;

        // Buang style/head/script dari BPS
        wrapper.querySelectorAll('style, head, script').forEach(el => el.remove());

        const tableEl = wrapper.querySelector('table');
        if (tableEl) {
            tableEl.classList.add('iklim-table');
            removeInlineWidths(tableEl); // <-- hapus width inline

            const konten = document.getElementById('iklimKonten');
            konten.innerHTML = '<div class="iklim-judul">' + judul + '</div>' +
                               '<div class="iklim-table-wrap"></div>';
            konten.querySelector('.iklim-table-wrap').appendChild(tableEl);
        } else {
            document.getElementById('iklimKonten').innerHTML =
                '<div class="iklim-judul">' + judul + '</div>' +
                '<div class="iklim-table-wrap">' + tabelDecoded + '</div>';
        }

    } catch(e) {
        document.getElementById('iklimKonten').innerHTML = `
            <div class="iklim-error-box">
                ⚠️ Gagal memuat data iklim.<br>
                <small style="color:#94a3b8;">${e.message}</small><br><br>
                <small>Coba refresh atau kunjungi 
                <a href="https://jatim.bps.go.id" target="_blank" style="color:#3b82f6;">BPS Jawa Timur</a>.</small>
            </div>`;
    }
})();
</script>