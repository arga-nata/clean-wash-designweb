<?php
// --- BACKEND PHP ---
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi Database Gagal!");
}

// Ambil customer_id dari URL atau default ke 1
$customer_id = isset($_GET['cid']) ? intval($_GET['cid']) : 1;
$query = "SELECT * FROM tbl_orders WHERE customer_id = '$customer_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Riwayat Pesanan - CleanWash Laundry</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <style>
    header .nav-links {
      display: flex !important;
      list-style: none !important;
      padding-left: 0 !important;
      margin-bottom: 0 !important;
    }

    header .nav-links li {
      margin: 0 !important;
      padding: 0 !important;
    }

    header .nav-links a {
      text-decoration: none !important;
      display: inline-block !important;
    }
  </style>
</head>

<body>
  <div class="page">
    <header>
      <nav class="nav">
        <div class="logo">
          <span class="gradient-text">CleanWash</span>
        </div>
        <ul class="nav-links">
          <li><a href="index.html">Beranda</a></li>
          <li><a href="tentang_kami.html">Tentang Kami</a></li>
          <li><a href="harga.html">Daftar Harga</a></li>
          <li><a href="paket.html">Paket Langganan</a></li>
          <li><a href="keranjang.php">Pemesanan</a></li>
          <li><a href="galeri.html">Galeri</a></li>
          <li>
            <a href="riwayat-order.php" class="active">Riwayat</a>
          </li>
          <li><a href="kontak.html">Hubungi Kami</a></li>
          <li><a href="login.html">Log In</a></li>
        </ul>
      </nav>
    </header>
    <div class="page-container">
      <h2 class="text-center section-title">Riwayat Pesanan Saya</h2>
      <p class="text-center mb-5">
        Kumpulan pesanan yang telah Anda lakukan di CleanWash.
      </p>

      <div id="order-list" class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($order = mysqli_fetch_assoc($result)): ?>
            <?php
            $order_id = $order['id'];
            $items_query = "SELECT s.service_name, i.qty FROM tbl_order_items i 
                                JOIN tbl_services s ON i.service_id = s.id 
                                WHERE i.order_id = '$order_id'";
            $items_res = mysqli_query($conn, $items_query);

            $all_items = [];
            $total_berat = 0;
            while ($item = mysqli_fetch_assoc($items_res)) {
              $all_items[] = $item['service_name'];
              $total_berat += (float) $item['qty'];
            }

            $display_items_arr = array_slice($all_items, 0, 2);
            $display_items = implode(", ", $display_items_arr);
            $sisa_item = count($all_items) - 2;
            $semua_layanan = ($sisa_item > 0) ? $display_items . "... (+" . $sisa_item . " item lainnya)" : $display_items;

            $status_class = "text-bg-danger";
            if ($order['status'] === "Selesai")
              $status_class = "text-bg-success";
            elseif ($order['status'] === "Proses")
              $status_class = "text-bg-warning";
            ?>
            <div class="col">
              <div class="card h-100">
                <img src="waduh.jpg" class="card-img-top" alt="mesin-cuci">
                <div class="card-body text-start">
                  <div class="d-flex justify-content-between mb-2">
                    <h5 class="card-title">Pesanan #<?php echo $order['id']; ?></h5>
                    <h1 class="badge p-2 w-50 <?php echo $status_class; ?>"><?php echo $order['status']; ?></h1>
                  </div>
                  <p class="card-text"><strong>Layanan:</strong> <?php echo $semua_layanan; ?></p>
                  <p class="card-text"><strong>Total Berat:</strong> <?php echo $total_berat; ?> kg</p>
                  <p class="card-text"><strong>Total:</strong> Rp
                    <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
                  </p>
                </div>
                <div class="card-footer border-top-0 pb-4">
                  <div class="d-grid gap-2">
                    <a href="detail-order.html?id=<?php echo $order['id']; ?>" class="btn btn-primary p-2">Detail
                      Pesanan</a>
                    <button onclick="hapusPesanan(<?php echo $order['id']; ?>)" class="btn btn-danger p-2">Hapus
                      Pesanan</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12 text-center py-5">
            <div style="font-size: 50px; color: #ccc; margin-bottom: 20px;">📦</div>
            <p class="text-muted">Anda belum memiliki riwayat pesanan.</p>
            <a href="keranjang.php" class="btn btn-primary btn-sm mt-3">Buat Pesanan Sekarang</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
      function hapusPesanan(id) {
        if (confirm("Apakah Anda yakin ingin menghapus pesanan #" + id + " ini?")) {
          const urlParams = new URLSearchParams(window.location.search);
          const cid = urlParams.get('cid') || '1';
          window.location.href = `delete_order.php?id=${id}&cid=${cid}`;
        }
      }
    </script>
  </div>
  <footer>
    <div class="footer-content">
      <div class="footer-logo">CleanWash</div>
      <p class="footer-copy">
        &copy; 2026 CleanWash Laundry. All Rights Reserved.
      </p>
    </div>
  </footer>
</body>

</html>