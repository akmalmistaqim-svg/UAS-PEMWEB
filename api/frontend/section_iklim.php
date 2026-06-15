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