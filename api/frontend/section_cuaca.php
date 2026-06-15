<!-- ===== SECTION: PREDIKSI CUACA (Banner Only) ===== -->
<section class="section-cuaca">
    <div class="cuaca-banner">
        <div class="cuaca-banner-bg"></div>
        <div class="cuaca-banner-content">
            <div class="cuaca-banner-icon">🌤️</div>
            <h2 class="cuaca-banner-title">Prediksi Cuaca Jawa Timur</h2>
            <p class="cuaca-banner-desc">Cek prediksi cuaca harian untuk 38 kota di Jawa Timur secara akurat dan real-time</p>
            <div class="cuaca-banner-stats">
                <div class="cuaca-stat-pill">📍 38 Kota</div>
                <div class="cuaca-stat-pill">📅 7 Hari ke Depan</div>
                <div class="cuaca-stat-pill">⚡ Real-time</div>
            </div>
            <a href="/iklim.php" class="cuaca-banner-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
                Cek Cuaca Sekarang
            </a>
        </div>
    </div>
</section>

<style>
.section-cuaca {
    background: #f8fafc;
    padding: 70px 5%;
}

.cuaca-banner {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(56, 189, 248, 0.18);
}

.cuaca-banner-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 40%, #7dd3fc 70%, #bae6fd 100%);
    z-index: 0;
}

/* Dekorasi awan abstrak */
.cuaca-banner-bg::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.10);
    top: -80px;
    right: -60px;
}

.cuaca-banner-bg::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    bottom: -60px;
    left: -40px;
}

.cuaca-banner-content {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 50px 40px;
    color: white;
}

.cuaca-banner-icon {
    font-size: 56px;
    margin-bottom: 16px;
    display: block;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
}

.cuaca-banner-title {
    font-size: 28px;
    font-weight: 700;
    color: white;
    margin-bottom: 12px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.12);
}

.cuaca-banner-desc {
    font-size: 14px;
    color: rgba(255,255,255,0.90);
    max-width: 480px;
    margin: 0 auto 24px auto;
    line-height: 1.6;
}

.cuaca-banner-stats {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.cuaca-stat-pill {
    background: rgba(255,255,255,0.22);
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 999px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    backdrop-filter: blur(4px);
}

.cuaca-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: white;
    color: #0284c7;
    border: none;
    border-radius: 12px;
    padding: 14px 32px;
    font-size: 15px;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    transition: transform 0.2s, box-shadow 0.2s;
}

.cuaca-banner-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.20);
    color: #0284c7;
}

.cuaca-banner-btn i {
    font-size: 14px;
}

@media (max-width: 768px) {
    .cuaca-banner-content {
        padding: 36px 24px;
    }
    .cuaca-banner-title {
        font-size: 22px;
    }
    .cuaca-banner-icon {
        font-size: 44px;
    }
}
</style>