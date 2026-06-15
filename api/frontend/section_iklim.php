<!-- ===== SECTION 3: DATA IKLIM BPS ===== -->
<style>
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
}

.iklim-table-wrap table td,
.iklim-table-wrap table th {
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    white-space: normal !important;
    word-break: break-word;
}

/* Hapus inline width dari BPS yang bikin tabel overflow */
.iklim-table-wrap table[width],
.iklim-table-wrap table td[width],
.iklim-table-wrap table th[width] {
    width: auto !important;
}

/* ===== BAGIAN CATATAN ===== */
/* Target td yang berisi teks "Catatan:" */
.iklim-table-wrap table td p,
.iklim-table-wrap table td[colspan] {
    font-size: 0.82rem !important;
    color: #64748b !important;
    line-height: 1.8 !important;
}

/* Baris catatan — border atas pemisah */
.iklim-table-wrap table tr:has(td[colspan]) {
    border-top: 2px solid #e2e8f0 !important;
}

.iklim-table-wrap table tr:has(td[colspan]) td {
    padding-top: 16px !important;
    padding-bottom: 8px !important;
    color: #64748b !important;
    font-size: 0.82rem !important;
    font-style: italic;
    line-height: 1.8 !important;
    background-color: #f8fafc !important;
    border-radius: 0 0 8px 8px;
}

/* Superscript angka footnote */
.iklim-table-wrap table sup {
    font-size: 0.65rem !important;
    color: #94a3b8 !important;
    vertical-align: super;
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