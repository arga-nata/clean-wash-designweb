<?php include 'includes/header.php'; ?>

<div class="page">
  <section class="hero">
    <div class="banner">
      <div class="banner_left">
        <h2 class="text-anim">Laundry Cepat, Bersih, dan Terpercaya</h2>
        <br />
        <p>
          CleanWash Laundry hadir untuk membantu Anda mendapatkan layanan laundry yang praktis, hemat waktu, dan
          berkualitas dengan harga terjangkau.
        </p>
        <br />

        <a onclick="scrollLayanan()" class="btn-solid">Lihat Layanan</a>
        <a href="kontak.html" class="btn-outline">Hubungi Kami</a>
      </div>

      <div class="banner_right">
        <h2>Pelayanan Profesional</h2>
        <br />
        <p>Cuci, setrika, dan laundry express untuk kebutuhan harian Anda.</p>
      </div>
    </div>
    <div class="scroll-down">
      <iconify-icon icon="mdi:chevron-double-down"></iconify-icon>
    </div>
  </section>

  <div id="layanan" class="page-container">
    <h2 class="section-title">Layanan Kami</h2>
    <div class="services-grid">
      <div class="service-card">
        <div class="card-icon">
          <iconify-icon icon="bi:basket3-fill"></iconify-icon>
        </div>
        <h3>Laundry Kiloan</h3>
        <p>
          Layanan ini cocok untuk pakaian sehari-hari dengan sistem per kilogram. Pilihan tepat bagi pelanggan yang
          ingin mencuci dalam jumlah banyak dengan harga lebih hemat.
        </p>
      </div>
      <div class="service-card">
        <iconify-icon icon="lucide-lab:jacket"></iconify-icon>
        <h3>Laundry Satuan</h3>
        <p>
          Layanan satuan digunakan untuk item tertentu seperti jas, jaket, selimut, bed cover, dan pakaian khusus
          lainnya yang membutuhkan penanganan lebih detail.
        </p>
      </div>
      <div class="service-card">
        <iconify-icon icon="bxs:washer"></iconify-icon>
        <iconify-icon icon="line-md:plus"></iconify-icon>
        <iconify-icon icon="material-symbols:iron-rounded"></iconify-icon>
        <h3>Cuci + Setrika</h3>
        <p>
          Pakaian akan dicuci hingga bersih, kemudian disetrika dengan rapi
          sehingga siap digunakan kembali oleh pelanggan.
        </p>
      </div>
      <div class="service-card">
        <iconify-icon icon="material-symbols:iron-rounded"></iconify-icon>
        <h3>Setrika Saja</h3>
        <p>
          Layanan ini ditujukan bagi pelanggan yang telah mencuci pakaian sendiri, tetapi membutuhkan bantuan untuk
          menyetrika agar lebih rapi.
        </p>
      </div>
      <div class="service-card">
        <iconify-icon icon="material-symbols:laundry"></iconify-icon>
        <h3>Express Laundry</h3>
        <p>
          Layanan cepat untuk pelanggan yang membutuhkan hasil laundry dalam waktu singkat, sangat cocok untuk
          kebutuhan
          mendesak.
        </p>
      </div>
      <div class="service-card">
        <iconify-icon icon="bxs:blanket"></iconify-icon>
        <h3>Cuci Selimut dan Bed Cover</h3>
        <p>
          Kami juga melayani pencucian perlengkapan rumah tangga seperti
          selimut, bed cover, dan sejenisnya dengan proses yang aman dan bersih.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  function scrollLayanan() {
    const el = document.getElementById("layanan");
    if (el) {
      el.scrollIntoView({ behavior: "smooth" });
    }
  }

  const cards = document.querySelectorAll(".service-card");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add("show");
          }, index * 300);
        }
      });
    },
    { threshold: 0.3 }
  );
  cards.forEach((card) => observer.observe(card));

  const galleryCards = document.querySelectorAll(".gallery-card");
  const galleryObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
        }
      });
    },
    { threshold: 0.2 }
  );
  galleryCards.forEach((card, index) => {
    card.style.transitionDelay = `${index * 0.5}s`;
    galleryObserver.observe(card);
  });
</script>

<?php include 'includes/footer.php'; ?>