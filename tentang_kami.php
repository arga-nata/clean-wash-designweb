<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <title>Tentang Kami - CleanWash Laundry</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <style>
    .about-container {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 0 10%;
    }

    .about-container h1 {
      font-size: 2.5rem;
      color: #333;
      margin-bottom: 30px;
    }

    .about-img {
      width: 100%;
      max-width: 700px;
      border-radius: 20px;
      margin-bottom: 40px;
    }

    .about-text {
      max-width: 800px;
      margin: 0 auto;
    }

    .about-text p {
      font-size: 1.1rem;
      color: #666;
      line-height: 1.8;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="page" style="display: flex; flex-direction: column; min-height: 100vh;">
    <?php include 'includes/header.php'; ?>

    <div class="about-container">
      <h1>Tentang Kami</h1>

      <img src="waduh.jpg" class="about-img" alt="Tentang CleanWash Laundry" />

      <div class="about-text">
        <p>
          CleanWash Laundry merupakan usaha jasa pelayanan yang bergerak di
          bidang laundry untuk memenuhi kebutuhan masyarakat akan layanan cuci
          pakaian yang praktis dan efisien.
        </p>
        <p>
          CleanWash Laundry didirikan untuk memberikan solusi bagi pelanggan
          yang memiliki kesibukan tinggi sehingga tidak memiliki cukup waktu
          untuk mencuci dan merapikan pakaian sendiri. Dengan pelayanan yang
          ramah, proses kerja yang rapi, dan hasil cucian yang bersih, kami
          berkomitmen memberikan pengalaman terbaik bagi setiap pelanggan.
        </p>
      </div>
    </div>

    <?php include 'includes/footer.php'; ?>
  </div>
</body>

</html>