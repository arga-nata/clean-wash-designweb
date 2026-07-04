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
          <div class="card h-100"
            style="background: white; border: 1px solid #eee; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: left;">
            <img src="waduh.jpg" style="width: 100%; height: 200px; object-fit: cover;" alt="mesin-cuci">
            <div class="card-body" style="padding: 25px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h5 style="margin: 0; font-weight: 700;">Pesanan #<?php echo $order['id']; ?></h5>
                <span class="badge <?php echo $status_class; ?>"
                  style="padding: 5px 12px; border-radius: 10px; font-size: 0.8rem;"><?php echo $order['status']; ?></span>
              </div>
              <p style="font-size: 0.9rem; color: #555; margin-bottom: 8px;"><strong>Layanan:</strong>
                <?php echo $semua_layanan; ?></p>
              <p style="font-size: 0.9rem; color: #555; margin-bottom: 20px;"><strong>Total:</strong> Rp
                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
              </p>

              <div style="display: flex; gap: 10px;">
                <a href="detail-order.php?id=<?php echo $order['id']; ?>"
                  style="flex: 1; text-align: center; background: #49b1c8; color: white; text-decoration: none; padding: 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;">Detail</a>
                <button onclick="hapusPesanan(<?php echo $order['id']; ?>)"
                  style="flex: 1; background: #ef4444; color: white; border: none; padding: 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Hapus</button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div style="grid-column: 1 / -1; text-align: center; padding: 60px 0;">
        <div style="font-size: 60px; margin-bottom: 20px;">📦</div>
        <p style="color: #888; font-size: 1.1rem;">Anda belum memiliki riwayat pesanan.</p>
        <a href="keranjang.php"
          style="display: inline-block; margin-top: 10px; background: #49b1c8; color: white; text-decoration: none; padding: 12px 24px; border-radius: 20px; font-weight: 600;">Buat
          Pesanan Sekarang</a>
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