<?php
session_start();
include 'includes/header.php';
?>

<div class="bg-gradient-clean text-white text-center pt-4 shadow-sm">
  <div class="container pb-4">
    <h1 class="fw-bold">Pilihan Paket Langganan Bulanan</h1>
    <p>
      Lebih hemat, lebih praktis, pakaian bersih rapi setiap hari tanpa ribet!
    </p>
  </div>
</div>

<main class="container mb-5 pt-5">
  <div class="row g-4 justify-content-center">
    <div class="col-md-4">
      <div class="card h-100 pricing-card shadow-sm text-center p-4">
        <div class="card-body d-flex flex-column">
          <h3 class="card-title fw-bold text-secondary mb-3">Paket Hemat</h3>
          <p class="text-muted">Cocok untuk mahasiswa atau kebutuhan personal</p>
          <h2 class="fw-bold text-dark my-3">
            Rp 75.000<span class="fs-6 text-muted">/bulan</span>
          </h2>
          <hr />
          <ul class="list-unstyled text-start my-4 grow">
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Kuota Cuci Setrika 15 Kg
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Parfume Premium Bebas Pilih
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Durasi Reguler (2-3 hari)
            </li>
            <li class="mb-3 text-muted text-decoration-line-through">
              <i class="fa-solid fa-xmark text-danger me-2"></i> Gratis Antar Jemput
            </li>
          </ul>
          <div style="align-items: center;">
            <a href="keranjang.php" class="btn-solid mt-auto">Pilih Paket</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 pricing-card card-popular shadow text-center p-4">
        <div class="card-body d-flex flex-column">
          <h3 class="card-title fw-bold text-dark mb-3">Paket Reguler</h3>
          <p class="text-muted">Pilihan terbaik untuk keluarga kecil</p>
          <h2 class="fw-bold text-dark my-3">
            Rp 180.000<span class="fs-6 text-muted">/bulan</span>
          </h2>
          <hr />
          <ul class="list-unstyled text-start my-4 grow">
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Kuota Cuci Setrika 40 Kg
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Parfume Premium Bebas Pilih
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Durasi Reguler (2-3 hari)
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Gratis Antar Jemput (Maks 5km)
            </li>
          </ul>
          <div style="align-items: center;">
            <a href="keranjang.php" class="btn-solid mt-auto">Pilih Paket</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 pricing-card shadow-sm text-center p-4">
        <div class="card-body d-flex flex-column">
          <h3 class="card-title fw-bold text-secondary mb-3">Paket Jumbo</h3>
          <p class="text-muted">Solusi praktis tanpa pusing cuci baju serumah</p>
          <h2 class="fw-bold text-dark my-3">
            Rp 320.000<span class="fs-6 text-muted">/bulan</span>
          </h2>
          <hr />
          <ul class="list-unstyled text-start my-4 grow">
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Kuota Cuci Setrika 80 Kg
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Parfume Premium Bebas Pilih
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Prioritas Antrean Ekpres (1-2 hari)
            </li>
            <li class="mb-3">
              <i class="fa-solid fa-check text-success me-2"></i> Gratis Antar Jemput Sepuasnya
            </li>
          </ul>
          <div style="align-items: center;">
            <a href="keranjang.php" class="btn-solid mt-auto">Pilih Paket</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  .bg-gradient-clean {
    background: linear-gradient(135deg, #49b1c8, #3d92a4);
    color: white;
  }

  .pricing-card {
    border: none;
    border-radius: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
  }

  .card-popular {
    border: 3px solid #49b1c8 !important;
    position: relative;
  }

  .card-popular::before {
    content: "Populer";
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: #49b1c8;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
    z-index: 10;
  }
</style>

<?php include 'includes/footer.php'; ?>