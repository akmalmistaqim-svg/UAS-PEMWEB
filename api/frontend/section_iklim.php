<!-- ===== SECTION 3: DATA IKLIM BPS ===== -->
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

    try {
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();

        if (!data?.data?.table) throw new Error("Tabel tidak ditemukan");

        const judul = data.data.title ?? "Data Iklim";

        // Decode HTML entities dulu, baru render sebagai HTML
        const tabelDecoded = decodeHTMLEntities(data.data.table);

        const wrapper = document.createElement('div');
        wrapper.innerHTML =
            '<div class="iklim-judul">' + judul + '</div>' +
            '<div class="iklim-table-wrap">' + tabelDecoded + '</div>';

        // Hapus tag <html>, <head>, <style> dari dalam tabel BPS
        // karena BPS kadang return full HTML document
        const styleTags = wrapper.querySelectorAll('style, head, script');
        styleTags.forEach(el => el.remove());

        // Ambil hanya bagian <table> saja
        const tableEl = wrapper.querySelector('table');
        if (tableEl) {
            // Tambahkan class supaya bisa di-style
            tableEl.classList.add('iklim-table');

            document.getElementById('iklimKonten').innerHTML =
                '<div class="iklim-judul">' + judul + '</div>' +
                '<div class="iklim-table-wrap"></div>';

            document.querySelector('.iklim-table-wrap').appendChild(tableEl);
        } else {
            // Fallback jika tidak ada tag table
            document.getElementById('iklimKonten').innerHTML =
                '<div class="iklim-judul">' + judul + '</div>' +
                '<div class="iklim-table-wrap">' + tabelDecoded + '</div>';
        }

    } catch(e) {
        document.getElementById('iklimKonten').innerHTML = `
            <div class="iklim-error-box">
                ⚠️ Gagal memuat data iklim.<br>
                <small style="color:#94a3b8;">${e.message}</small><br><br>
                <small>Coba refresh halaman atau kunjungi 
                <a href="https://jatim.bps.go.id" target="_blank" style="color:#3b82f6;">BPS Jawa Timur</a> 
                langsung.</small>
            </div>`;
    }
})();
</script>