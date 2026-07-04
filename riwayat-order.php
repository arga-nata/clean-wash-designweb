<?php
session_start();

$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi Database Gagal!");
}

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];
$query = "SELECT * FROM tbl_orders WHERE customer_id = '$customer_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<?php include 'includes/header.php'; ?>

<section class="page-container" style="padding: 110px 10% 60px 10%; text-align: center;">
  <h2 class="section-title">Riwayat Pesanan Saya</h2>
  <p style="color: #666; margin-bottom: 40px;">
    Kumpulan pesanan yang telah Anda lakukan di CleanWash.
  </p>

  <div style="display: flex; flex-wrap: wrap; gap: 25px; justify-content: center;">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($order = mysqli_fetch_assoc($result)): ?>
        <?php
        $order_id = $order['id'];
        $items_query = "SELECT s.service_name, i.qty FROM tbl_order_items i 
                                  JOIN tbl_services s ON i.service_id = s.id 
                                  WHERE i.order_id = '$order_id'";
        $items_res = mysqli_query($conn, $items_query);

        $items_list = [];
        while ($item = mysqli_fetch_assoc($items_res)) {
          $qty = (int) $item['qty'];
          $items_list[] = $item['service_name'] . " (" . $qty . ")";
        }
        $semua_layanan = implode(", ", $items_list);

        $status_class = "text-bg-danger";
        if ($order['status'] === "Selesai")
          $status_class = "text-bg-success";
        elseif ($order['status'] === "Proses")
          $status_class = "text-bg-warning";
        ?>
        <div style="width: 100%; max-width: 350px;">
          <div class="card h-100 shadow-sm"
            style="border-radius: 20px; overflow: hidden; text-align: left; border: 1px solid #eee;">
            <img src="waduh.jpg" class="card-img-top" style="height: 200px; object-fit: cover;" alt="mesin-cuci">
            <div class="card-body" style="padding: 25px;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin: 0; font-weight: 700;">Pesanan #<?php echo $order['id']; ?></h5>
                <span class="badge <?php echo $status_class; ?>"
                  style="border-radius: 10px; font-size: 0.8rem;padding-block: 10px; padding-inline: 30px;"><?php echo $order['status']; ?></span>
              </div>
              <p style="font-size: 0.9rem; color: #555; margin-bottom: 8px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                title="<?php echo $semua_layanan; ?>">
                <strong>Layanan:</strong> <?php echo $semua_layanan; ?>
              </p>
              <p style="font-size: 0.9rem; color: #555; margin-bottom: 20px;"><strong>Total:</strong> Rp
                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
              </p>

              <div class="d-flex flex-column gap-2">
                <a href="detail-order.php?id=<?php echo $order['id']; ?>" class="btn btn-info text-white flex-fill"
                  style="border-radius: 10px; font-size: 0.85rem; font-weight: 600;">Detail</a>
                <button onclick="hapusPesanan(<?php echo $order['id']; ?>)" class="btn btn-danger flex-fill"
                  style="border-radius: 10px; font-size: 0.85rem; font-weight: 600;">Hapus</button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 0; width: 100%;">
        <div style="font-size: 60px; margin-bottom: 20px;">📦</div>
        <p style="color: #888; font-size: 1.1rem;">Anda belum memiliki riwayat pesanan.</p>
        <a href="keranjang.php" class="btn btn-info text-white"
          style="border-radius: 20px; font-weight: 600; padding: 12px 24px;">Buat Pesanan Sekarang</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
  function hapusPesanan(id) {
    if (confirm("Apakah Anda yakin ingin menghapus pesanan #" + id + " ini?")) {
      window.location.href = `delete_order.php?id=${id}`;
    }
  }
</script>
</body>

</html>