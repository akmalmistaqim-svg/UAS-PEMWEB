<!-- ===== SECTION: DATA IKLIM BPS ===== -->
<section id="dataiklim" class="section-iklim">
    <div class="section-title">
        <h2>🌡️ Data Iklim Provinsi Jawa Timur</h2>
        <p>Data pengamatan unsur iklim dari stasiun BMKG Jawa Timur</p>
    </div>
    <div class="iklim-wrap">
        <div id="iklimKonten">
            <div class="iklim-loading">
                <span class="grafik-spinner"></span> Memuat data iklim...
            </div>
        </div>
        <p class="iklim-sumber">Sumber: Badan Pusat Statistik (BPS) Provinsi Jawa Timur · BMKG</p>
    </div>
</section>

<script>
(async function () {
    function decodeHTMLEntities(str) {
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    }

    function removeInlineWidths(el) {
        el.removeAttribute('width');
        el.removeAttribute('style');
        el.querySelectorAll('[width], [style]').forEach(child => {
            child.removeAttribute('width');
            if (child.style && child.style.width) child.style.width = '';
        });
    }

    try {
        const res = await fetch('/api/frontend/iklim_api.php');
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();
        if (data.error) throw new Error(data.error);
        if (data.status !== 'OK') throw new Error("Respons tidak valid");

        const judul = data.judul ?? "Data Iklim";
        const tabelDecoded = decodeHTMLEntities(data.tabel);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = tabelDecoded;

        wrapper.querySelectorAll('style, head, script').forEach(el => el.remove());

        const tableEl = wrapper.querySelector('table');
        const konten  = document.getElementById('iklimKonten');

        if (tableEl) {
            tableEl.classList.add('iklim-table');
            removeInlineWidths(tableEl);

            konten.innerHTML =
                '<div class="iklim-judul">' + judul + '</div>' +
                '<div class="iklim-table-wrap"></div>';
            konten.querySelector('.iklim-table-wrap').appendChild(tableEl);
        } else {
            konten.innerHTML =
                '<div class="iklim-judul">' + judul + '</div>' +
                '<div class="iklim-table-wrap">' + tabelDecoded + '</div>';
        }

    } catch (e) {
        document.getElementById('iklimKonten').innerHTML = `
            <div class="iklim-error-box">
                ⚠️ Gagal memuat data iklim.<br>
                <small style="color:#94a3b8;">${e.message}</small><br><br>
                <small>Coba refresh atau kunjungi
                <a href="https://jatim.bps.go.id" target="_blank" style="color:#4CAF50;">BPS Jawa Timur</a>.</small>
            </div>`;
    }
})();
</script>