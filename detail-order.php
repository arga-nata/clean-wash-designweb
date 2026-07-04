<?php
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi Database Gagal!");
}

if (!isset($_GET['id'])) {
  die("ID Pesanan tidak ditemukan!");
}

$order_id = intval($_GET['id']);

$sql_order = "SELECT o.*, c.customer_name, c.customer_phone, c.customer_address 
              FROM tbl_orders o 
              JOIN tbl_customers c ON o.customer_id = c.id 
              WHERE o.id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
  die("Pesanan tidak ditemukan di database!");
}

$status = $order['status'];
$status_clean = trim(strtolower($status));
$progress_width = "0";
$progress_label = $status;

if ($status_clean == "pending") {
  $progress_width = "33";
} elseif ($status_clean == "proses") {
  $progress_width = "66";
} elseif ($status_clean == "selesai") {
  $progress_width = "100";
}

include 'includes/header.php';
?>

<div class="container py-5" style="margin-top: 60px">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 id="order-id-title" class="mb-0">Detail Pesanan #<?php echo $order['id']; ?></h2>
            <a href="riwayat-order.php" class="btn btn-outline-secondary btn-sm"
              style="border-radius: 10px; font-weight: 600;">
              ← Kembali ke Riwayat
            </a>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span id="order-date" class="text-muted">Tanggal:
              <?php echo date('d M Y', strtotime($order['order_date'])); ?></span>
            <div>
              <span class="text-muted">Estimasi Selesai: </span>
              <span id="order-estimate">3-5 Hari</span>
            </div>
          </div>
          <div class="card bg-light shadow-sm" style="border-radius: 15px">
            <div class="card-body p-2">
              <div class="row">
                <div class="col-4">
                  <small class="text-muted d-block">Nama Lengkap</small>
                  <span id="cust-name" class="fw-medium"><?php echo $order['customer_name']; ?></span>
                </div>
                <div class="col-4 border-start border-end">
                  <small class="text-muted d-block">No. WA</small>
                  <span id="cust-phone" class="fw-medium"><?php echo $order['customer_phone']; ?></span>
                </div>
                <div class="col-4">
                  <small class="text-muted d-block">Alamat Lengkap</small>
                  <span id="cust-address" class="fw-medium text-truncate d-block"
                    style="max-width: 100px"><?php echo $order['customer_address']; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-5">
          <label class="fw-bold mb-2">Status Pesanan:</label>
          <div class="progress" style="height: 30px">
            <div id="main-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
              role="progressbar" style="width: <?php echo $progress_width; ?>%"
              aria-valuenow="<?php echo str_replace('%', '', $progress_width); ?>" aria-valuemin="0"
              aria-valuemax="100">
              <span id="progress-label" class="fw-bold"><?php echo $progress_label; ?></span>
            </div>
          </div>
        </div>

        <div class="card shadow-sm border">
          <div class="card-body p-0">
            <div class="p-3 border-bottom">
              <h5 class="fw-bold mb-0">Rincian Layanan:</h5>
            </div>
            <ul class="list-group list-group-flush" id="items-list">
              <?php
              $order_id = $order['id'];
              $items_query = "SELECT s.service_name, i.qty, s.unit, s.price, s.estimate FROM tbl_order_items i 
                                  JOIN tbl_services s ON i.service_id = s.id 
                                  WHERE i.order_id = '$order_id'";
              $items_res = mysqli_query($conn, $items_query);
              while ($item = mysqli_fetch_assoc($items_res)):
                $subtotal = $item['price'] * $item['qty'];
                ?>
                <li class="list-group-item py-3">
                  <div class="fw-bold mb-1"><?php echo $item['service_name']; ?></div>
                  <div class="text-muted small mb-1">
                    Jumlah:
                    <?php echo intval($item['qty']) . ' ' . $item['unit']; ?>
                  </div>
                  <div class="fw-bold text-primary" style="font-size: 0.9rem;">
                    Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>
            <div class="p-3 bg-light border-top">
              <div class="d-flex justify-content-between">
                <span class="fw-bold">Total Bayar:</span>
                <span class="fw-bold text-primary" id="total-price">
                  Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
