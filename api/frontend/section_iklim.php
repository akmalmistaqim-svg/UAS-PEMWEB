<!-- ===== SECTION 3: DATA IKLIM BPS ===== -->
<style>
/* ====== LAYOUT SECTION ====== */
.section-iklim {
    padding: 50px 20px;
    background: #f3f6fa;
}

.section-iklim .section-title {
    text-align: center;
    margin-bottom: 28px;
}

.section-iklim .section-title h2 {
    font-size: 2.3rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.section-iklim .section-title p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.iklim-wrap {
    max-width: 1000px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
    padding: 32px;
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
    font-size: 1.15rem;
    color: #1e293b;
    line-height: 1.7;
    margin-bottom: 22px;
    padding: 0 10px;
}

/* ====== TABEL WRAPPER ====== */
.iklim-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 12px;
    border: 1px solid #e8edf3;
}

.iklim-table-wrap table,
.iklim-table {
    width: 100% !important;
    min-width: 100% !important;
    border-collapse: collapse;
    table-layout: auto !important;
    font-family: inherit;
}

.iklim-table-wrap table td,
.iklim-table-wrap table th {
    padding: 14px 18px !important;
    font-size: 0.95rem !important;
    white-space: normal !important;
    word-break: break-word;
    border-bottom: 1px solid #eef2f6;
    color: #334155;
    text-align: left;
}

/* Hapus inline width dari BPS yang bikin tabel overflow */
.iklim-table-wrap table[width],
.iklim-table-wrap table td[width],
.iklim-table-wrap table th[width] {
    width: auto !important;
}

/* ====== BARIS HEADER (Unsur Iklim | 2019 | 2020 | 2021) ====== */
.iklim-table-wrap table tr.iklim-header-row td,
.iklim-table-wrap table tr.iklim-header-row th {
    background-color: #14233f !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    text-align: center !important;
    padding: 16px 18px !important;
    border-bottom: none !important;
}

.iklim-table-wrap table tr.iklim-header-row td:first-child,
.iklim-table-wrap table tr.iklim-header-row th:first-child {
    text-align: left !important;
}

/* Superscript angka footnote di header */
.iklim-table-wrap table tr.iklim-header-row sup {
    font-size: 0.7rem !important;
    color: #cbd5e1 !important;
}

/* ====== BARIS KATEGORI (Suhu, Kelembaban, Kecepatan Angin, dst) ====== */
.iklim-table-wrap table tr.iklim-category-row td {
    background-color: #f1f5f9 !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    font-size: 0.95rem !important;
}

/* Sel kosong di baris kategori (kolom 2019/2020/2021) */
.iklim-table-wrap table tr.iklim-category-row td:not(:first-child) {
    background-color: #f1f5f9 !important;
}

/* Baris data biasa: kolom pertama (nama unsur) sedikit beda warna */
.iklim-table-wrap table tr:not(.iklim-header-row):not(.iklim-category-row) td:first-child {
    color: #475569;
    font-weight: 500;
    background-color: #fafbfc;
}

.iklim-table-wrap table tr:not(.iklim-header-row):not(.iklim-category-row) td:not(:first-child) {
    text-align: center;
}

/* ====== BAGIAN CATATAN ====== */
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
    padding: 18px 18px 12px 18px !important;
    color: #64748b !important;
    font-size: 0.82rem !important;
    font-style: italic;
    line-height: 1.8 !important;
    background-color: #f8fafc !important;
    text-align: left !important;
    font-weight: 400 !important;
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
        font-size: 1.7rem;
    }
    .iklim-wrap {
        padding: 18px;
        border-radius: 14px;
    }
    .iklim-judul {
        font-size: 1rem;
    }
    .iklim-table-wrap table td,
    .iklim-table-wrap table th {
        padding: 10px 12px !important;
        font-size: 0.85rem !important;
    }
    .iklim-table-wrap table tr.iklim-header-row td,
    .iklim-table-wrap table tr.iklim-header-row th {
        font-size: 0.85rem !important;
        padding: 12px 10px !important;
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

    // Memberi class pada baris header & baris kategori agar bisa di-style
    function tandaiBarisTabel(tableEl) {
        const rows = tableEl.querySelectorAll('tr');
        rows.forEach((tr, idx) => {
            // Baris pertama = header (Unsur Iklim | 2019 | 2020 | 2021)
            if (idx === 0) {
                tr.classList.add('iklim-header-row');
                return;
            }

            const cells = tr.querySelectorAll('td, th');
            if (cells.length === 0) return;

            // Lewati baris catatan (punya colspan)
            const adaColspan = Array.from(cells).some(c => c.hasAttribute('colspan'));
            if (adaColspan) return;

            if (cells.length > 1) {
                let sisaKosong = true;
                for (let i = 1; i < cells.length; i++) {
                    const txt = cells[i].textContent.replace(/\u00A0/g, '').trim();
                    if (txt !== '') { sisaKosong = false; break; }
                }
                if (sisaKosong) tr.classList.add('iklim-category-row');
            }
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
            tandaiBarisTabel(tableEl);   // <-- tandai baris header & kategori

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