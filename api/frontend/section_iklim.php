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

    try {
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();

        if (!data?.data?.table) throw new Error("Tabel tidak ditemukan");

        const judul = data.data.title ?? "Data Iklim";
        const tabel = data.data.table;

        document.getElementById('iklimKonten').innerHTML =
            '<div class="iklim-judul">' + judul + '</div>' +
            '<div class="iklim-table-wrap">' + tabel + '</div>';

    } catch(e) {
        // Fallback: tampilkan tabel statis jika API gagal
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

<script>
(async function() {
    try {
        var res  = await fetch('/api/iklim.php');
        var data = await res.json();
        if (data.error) throw new Error(data.error);
        document.getElementById('iklimKonten').innerHTML =
            '<div class="iklim-judul">' + data.judul + '</div>' +
            '<div class="iklim-table-wrap">' + data.tabel + '</div>';
    } catch(e) {
        document.getElementById('iklimKonten').innerHTML =
            '<div class="iklim-error-box">⚠️ Gagal memuat data iklim.<br><small style="color:#94a3b8;">' + e.message + '</small></div>';
    }
})();
</script>