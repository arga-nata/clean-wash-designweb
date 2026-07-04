<?php
session_start();
include 'includes/header.php';
?>

<div class="page-containers">
  <div class="head-title">
    <h2 class="section-title">Galeri CleanWash Laundry</h2>
    <p class="subtitle">Berikut beberapa tampilan area kerja, proses laundry, dan hasil pelayanan kami</p>
  </div>

  <div class="gallery-grid">
    <div class="gallery-card">
      <div class="img-container">
        <img src="jpg/Penerimaan.jpg" alt="Area Penerimaan" />
      </div>
      <div class="card-body">
        <h3 class="card-title">Area Penerimaan</h3>
        <p class="card-description">
          Tempat pelanggan melakukan penyerahan dan pengambilan pakaian.
        </p>
      </div>
    </div>

    <div class="gallery-card">
      <div class="img-container">
        <img src="jpg/ProsesLaundryjpg.jpg" alt="Proses Pencucian" />
      </div>
      <div class="card-body">
        <h3 class="card-title">Proses Pencucian</h3>
        <p class="card-description">
          Pakaian dicuci dengan prosedur yang rapi dan bersih.
        </p>
      </div>
    </div>

    <div class="gallery-card">
      <div class="img-container">
        <img src="jpg/Setrika.jpg" alt="Proses Setrika" />
      </div>
      <div class="card-body">
        <h3 class="card-title">Proses Setrika</h3>
        <p class="card-description">
          Pakaian disetrika agar lebih rapi sebelum dikemas.
        </p>
      </div>
    </div>

    <div class="gallery-card">
      <div class="img-container">
        <img src="jpg/Pengemasan.jpg" alt="Pengemasan" />
      </div>
      <div class="card-body">
        <h3 class="card-title">Pengemasan</h3>
        <p class="card-description">
          Pakaian dikemas dengan hati-hati untuk memastikan tidak ada kerusakan.
        </p>
      </div>
    </div>

    <div class="gallery-card">
      <div class="img-container">
        <img src="jpg/Satuanjpg.jpg" alt="Laundry Satuan" />
      </div>
      <div class="card-body">
        <h3 class="card-title">Laundry Satuan</h3>
        <p class="card-description">
          Penanganan khusus untuk pakaian tertentu seperti jas dan selimut.
        </p>
      </div>
    </div>
  </div>
</div>

<style>
  .head-title {
    text-align: center;
    margin-bottom: 50px;
  }

  .head-title .subtitle {
    font-size: 1.1rem;
    color: #666;
    margin-top: 10px;
  }

  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    padding: 0 20px;
  }

  .gallery-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid #eee;
    opacity: 0;
    transform: translateY(30px);
  }

  .gallery-card.show {
    opacity: 1;
    transform: translateY(0);
  }

  .gallery-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  }

  .img-container {
    width: 100%;
    height: 220px;
    overflow: hidden;
  }

  .img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .gallery-card:hover .img-container img {
    transform: scale(1.1);
  }

  .card-body {
    padding: 20px;
    text-align: left;
  }

  .card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #49b1c8;
    margin-bottom: 10px;
  }

  .card-description {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.5;
  }

  @media (max-width: 992px) {
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 600px) {
    .gallery-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".gallery-card");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            setTimeout(() => {
              entry.target.classList.add("show");
            }, index * 150);
          }
        });
      },
      { threshold: 0.1 }
    );
    cards.forEach((card) => observer.observe(card));
  });
</script>

<?php include 'includes/footer.php'; ?>
