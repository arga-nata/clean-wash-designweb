<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['custName'])) {
  error_reporting(0);
  ini_set('display_errors', 0);

  $host = "db";
  $user = "db";
  $pass = "db";
  $db = "db";

  try {
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn)
      throw new Exception("Koneksi Database Gagal!");

    $name = trim(ucwords(strtolower($_POST['custName'] ?? '')));
    $phone = trim($_POST['custPhone'] ?? '');
    $address = trim(ucwords(strtolower($_POST['custAddress'] ?? '')));
    $deliveryMethod = $_POST['deliveryMethod'] ?? 'Ambil Sendiri';
    $locationArea = $_POST['locationArea'] ?? 'kota';
    $deliveryFee = (float) ($_POST['deliveryFee'] ?? 0);
    $total = (float) ($_POST['totalAmount'] ?? 0);
    $cartItems = json_decode($_POST['cartData'] ?? '[]', true);

    if (empty($name) || empty($phone) || empty($address)) {
      throw new Exception("Nama, No. WhatsApp, dan Alamat wajib diisi lengkap!");
    }
    if (empty($cartItems) || !is_array($cartItems)) {
      throw new Exception("Keranjang belanja kosong!");
    }

    mysqli_begin_transaction($conn);

    $stmt = $conn->prepare("SELECT id FROM tbl_customers WHERE customer_phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
      $customer = $res->fetch_assoc();
      $customer_id = $customer['id'];
    } else {
      $stmt = $conn->prepare("INSERT INTO tbl_customers (username, password, customer_name, customer_phone, customer_address) VALUES (?, ?, ?, ?, ?)");
      $temp_user = strtolower(str_replace(' ', '', $name)) . rand(10, 99);
      $temp_pass = password_hash('123456', PASSWORD_DEFAULT);
      $stmt->bind_param("sssss", $temp_user, $temp_pass, $name, $phone, $address);
      $stmt->execute();
      $customer_id = $conn->insert_id;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_orders (customer_id, delivery_method, location_area, delivery_fee, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issdd", $customer_id, $deliveryMethod, $locationArea, $deliveryFee, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;

    foreach ($cartItems as $item) {
      $stmt_svc = $conn->prepare("SELECT id FROM tbl_services WHERE service_name = ?");
      $stmt_svc->bind_param("s", $item['name']);
      $stmt_svc->execute();
      $res_svc = $stmt_svc->get_result();
      $svc = $res_svc->fetch_assoc();
      $service_id = $svc ? $svc['id'] : 1;

      $subtotal = (float) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 0);
      $stmt_item = $conn->prepare("INSERT INTO tbl_order_items (order_id, service_id, qty, subtotal) VALUES (?, ?, ?, ?)");
      $stmt_item->bind_param("iidd", $order_id, $service_id, $item['qty'], $subtotal);
      $stmt_item->execute();
    }

    mysqli_commit($conn);
    if (ob_get_length())
      ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;

  } catch (Exception $e) {
    if (isset($conn)) {
      mysqli_rollback($conn);
      mysqli_close($conn);
    }
    if (ob_get_length())
      ob_clean();
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
  }
}

// --- AMBIL DATA UNTUK TAMPILAN (Satu Koneksi) ---
$user_data = ['customer_name' => '', 'customer_phone' => '', 'customer_address' => ''];
$services_list = [];

$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    // 1. Ambil Data User
    if (isset($_SESSION['customer_id'])) {
        $cust_id = $_SESSION['customer_id'];
        $query_user = "SELECT customer_name, customer_phone, customer_address FROM tbl_customers WHERE id = '$cust_id'";
        $res_user = mysqli_query($conn, $query_user);
        if ($res_user && mysqli_num_rows($res_user) > 0) {
            $user_data = mysqli_fetch_assoc($res_user);
        }
    }
    
    // 2. Ambil Data Layanan
    $res_svc = mysqli_query($conn, "SELECT * FROM tbl_services ORDER BY service_name ASC");
    if ($res_svc) {
        while ($row = mysqli_fetch_assoc($res_svc)) {
            $services_list[] = $row;
        }
    }
    
    mysqli_close($conn);
}
?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="keranjang.css" />
<style>
  .capitalize-input {
    text-transform: capitalize;
  }
</style>

<section class="order-section"
  style="padding-top: 110px; flex: 1; padding-left: 10%; padding-right: 10%; padding-bottom: 40px; display: flex; flex-direction: column; align-items: center;">
  <div style="text-align: center; margin-bottom: 40px; width: 100%;">
    <h1 class="section-title">Pemesanan Layanan Laundry</h1>
    <p style="color: #666; font-size: 1.1rem;">Silakan lengkapi formulir di bawah ini untuk melakukan pemesanan.</p>
  </div>

  <div class="order-wrapper">
    <div class="main-order-card">
      <form action="keranjang.php" method="POST" id="orderForm">
        <div class="field">
          <label for="serviceSelect">Pilih Jenis Layanan</label>
          <select id="serviceSelect" onchange="updatePlaceholder()">
            <option value="" disabled selected>-- Pilih Layanan --</option>
            <?php foreach ($services_list as $svc): ?>
                <option value="<?= htmlspecialchars($svc['service_name']) ?>" 
                        data-price="<?= $svc['price'] ?>" 
                        data-unit="<?= htmlspecialchars($svc['unit']) ?>" 
                        data-estimate="<?= htmlspecialchars($svc['estimate']) ?>">
                    <?= htmlspecialchars($svc['service_name']) ?> (Rp <?= number_format($svc['price'], 0, ',', '.') ?>/<?= htmlspecialchars($svc['unit']) ?>)
                </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="serviceQty">Jumlah Pesanan</label>
          <input type="number" id="serviceQty" placeholder="Masukkan jumlah pesanan..." min="1" />
        </div>

        <button type="button" class="btn-add-pill" onclick="handleAddToCart()">Tambahkan ke Daftar Pesanan</button>

        <div style="margin: 30px 0; border-top: 1px solid #eee; padding-top: 30px;">
          <div class="customer-grid">
            <div class="field">
              <label for="custName">Nama Lengkap</label>
              <input type="text" name="custName" id="custName" required placeholder="Masukkan nama lengkap Anda..."
                class="capitalize-input" value="<?php echo htmlspecialchars($user_data['customer_name'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label for="custPhone">No. WhatsApp</label>
              <input type="text" name="custPhone" id="custPhone" required placeholder="Contoh: 081234567890"
                value="<?php echo htmlspecialchars($user_data['customer_phone'] ?? ''); ?>" />
            </div>
            <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
              <div style="flex: 1; min-width: 200px">
                <label class="fw-bold" style="font-size: 0.9rem; display: block; margin-bottom: 10px;">Metode
                  Pengambilan:</label>
                <div style="display: flex; gap: 15px; flex-wrap: wrap">
                  <label style="font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <input type="radio" name="deliveryMethod" value="Ambil Sendiri" checked onchange="calculateTotal()">
                    Ambil Sendiri
                  </label>
                  <label style="font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <input type="radio" name="deliveryMethod" value="Kurir Jemput" onchange="calculateTotal()"> Kurir
                    Jemput
                  </label>
                </div>
              </div>
              <div style="flex: 1; min-width: 200px; margin-top: 10px">
                <div class="field">
                  <label for="locationArea">Area Pengambilan:</label>
                  <select name="locationArea" id="locationArea" onchange="calculateTotal()">
                    <option value="kota">Kota Blitar (Rp 5.000)</option>
                    <option value="kabupaten">Kabupaten Blitar (Rp 10.000)</option>
                    <option value="luar">Luar Kota (Rp 20.000)</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="field full-width">
              <label for="custAddress">Alamat Lengkap</label>
              <input type="text" name="custAddress" id="custAddress" required
                placeholder="Masukkan alamat lengkap penjemputan..." class="capitalize-input"
                value="<?php echo htmlspecialchars($user_data['customer_address'] ?? ''); ?>" />
            </div>
          </div>
        </div>

        <input type="hidden" name="totalAmount" id="hiddenTotalAmount">
        <input type="hidden" name="deliveryFee" id="hiddenDeliveryFee">
        <input type="hidden" name="cartData" id="hiddenCartData">

        <div id="btnWhatsAppWrapper">
          <button type="submit" name="submit_order" id="btnWhatsApp" class="btn-whatsapp" disabled>
            Buat Pesanan Sekarang
          </button>
        </div>
      </form>
    </div>

    <div class="summary-card">
      <div id="cartList" class="cart-list"></div>
      <div class="total-block" id="totalBlock" style="width: 100%; text-align: left; display: block; margin-top: 20px;">
        <div style="width: 100%; display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px;">
          <div style="display: flex; justify-content: space-between; width: 100%; font-size: 0.85rem; color: #777;">
            <span>Subtotal Layanan:</span> <span id="subtotalAmount">Rp 0</span>
          </div>
          <div style="display: flex; justify-content: space-between; width: 100%; font-size: 0.85rem; color: #777;">
            <span>Biaya Pengiriman:</span> <span id="deliveryFeeAmount">Rp 0</span>
          </div>
          <div
            style="display: flex; justify-content: space-between; width: 100%; font-size: 0.85rem; color: #777; border-bottom: 1px dashed #ddd; padding-bottom: 10px;">
            <span>Estimasi Selesai:</span> <span id="totalEstimate" class="fw-medium">0 Hari</span>
          </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: 5px;">
          <span class="total-label">Total Pembayaran</span>
          <span class="total-amount" id="totalAmount">Rp 0</span>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="app.js"></script>
<script>
  async function handleAddToCart() {
    const btn = document.querySelector(".btn-add-pill");
    const originalText = btn.innerText;
    const select = document.getElementById("serviceSelect");
    const qtyInput = document.getElementById("serviceQty");
    if (!select.value) { alert("Mohon pilih layanan terlebih dahulu."); return; }
    if (!qtyInput.value || qtyInput.value <= 0) { alert("Mohon masukkan jumlah pesanan yang valid"); return; }
    btn.innerText = "Menambahkan...";
    btn.style.backgroundColor = "#ccc";
    btn.disabled = true;
    await new Promise(resolve => setTimeout(resolve, 500));
    const selectedOption = select.options[select.selectedIndex];
    addToCart(selectedOption.value, parseInt(selectedOption.getAttribute("data-price")), selectedOption.getAttribute("data-unit"), selectedOption.getAttribute("data-estimate"), parseInt(qtyInput.value));
    btn.innerText = "Berhasil Ditambahkan!";
    btn.style.backgroundColor = "#28a745";
    setTimeout(() => {
      btn.innerText = originalText;
      btn.style.backgroundColor = "";
      btn.disabled = false;
    }, 1500);
  }
  function updatePlaceholder() {
    const select = document.getElementById("serviceSelect");
    const qtyInput = document.getElementById("serviceQty");
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption) {
      qtyInput.placeholder = `Masukkan jumlah dalam ${selectedOption.getAttribute("data-unit") || "kg"}`;
      qtyInput.value = "";
    }
  }
  const myForm = document.getElementById("orderForm");
  if (myForm) {
    myForm.onsubmit = async function (e) {
      e.preventDefault();
      const btn = document.getElementById("btnWhatsApp");
      const originalText = btn.innerText;
      btn.innerText = "Mengirim...";
      btn.style.backgroundColor = "#ccc";
      btn.disabled = true;
      const formData = new FormData(myForm);
      formData.append("totalAmount", document.getElementById("hiddenTotalAmount").value);
      formData.append("deliveryFee", document.getElementById("hiddenDeliveryFee").value);
      formData.append("cartData", JSON.stringify(cart));
      try {
        const response = await fetch("keranjang.php", { method: "POST", body: formData });
        const text = await response.text();
        try {
          const result = JSON.parse(text);
          if (response.ok && result.status === "success") {
            btn.innerText = "Berhasil!";
            btn.style.backgroundColor = "#28a745";
            localStorage.removeItem("cleanwash_cart");
            cart = [];
            setTimeout(() => { window.location.href = "riwayat-order.php"; }, 1500);
          } else {
            throw new Error(result.message || "Server error");
          }
        } catch (jsonErr) {
          console.error("Raw response:", text);
          throw new Error("Server mengirim respon tidak valid. Cek console.");
        }
      } catch (error) {
        alert("Terjadi kesalahan: " + error.message);
        btn.innerText = originalText;
        btn.style.backgroundColor = "";
        btn.disabled = false;
      }
    }
  }
</script>
</body>
</html>
