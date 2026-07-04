<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $host = "db";
  $user = "db";
  $pass = "db";
  $db = "db";
  $conn = mysqli_connect($host, $user, $pass, $db);

  header('Content-Type: application/json');

  if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi Database Gagal!']);
    exit;
  }

  $username = $_POST['username'];
  $password = $_POST['password'];
  $name = ucwords(strtolower($_POST['fullname']));
  $phone = $_POST['phone'];
  $address = ucwords(strtolower($_POST['address']));

  $stmt_user = $conn->prepare("SELECT id FROM tbl_customers WHERE username = ?");
  $stmt_user->bind_param("s", $username);
  $stmt_user->execute();
  if ($stmt_user->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan, silakan gunakan username lain.']);
    exit;
  }

  $stmt_phone = $conn->prepare("SELECT id FROM tbl_customers WHERE customer_phone = ?");
  $stmt_phone->bind_param("s", $phone);
  $stmt_phone->execute();
  if ($stmt_phone->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp sudah terdaftar dalam sistem.']);
    exit;
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO tbl_customers (username, password, customer_name, customer_phone, customer_address) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $username, $hashed_password, $name, $phone, $address);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat proses pendaftaran.']);
  }
  mysqli_close($conn);
  exit;
}
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="login.css">

<div class="login-center-wrapper">
  <div class="login-container">
    <div class="login-box">
      <div class="login-header">
        <h2>Daftar Akun</h2>
        <p>Kelola pesanan laundry lebih praktis</p>
      </div>

      <form id="registerForm">
        <div class="field">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" required placeholder="Username" />
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="Password" />
        </div>
        <div class="field">
          <label for="fullname">Nama Lengkap</label>
          <input type="text" name="fullname" id="fullname" required placeholder="Nama Lengkap"
            class="capitalize-input" />
        </div>
        <div class="field">
          <label for="phone">No. WhatsApp</label>
          <input type="text" name="phone" id="phone" required placeholder="Nomor WhatsApp" />
        </div>
        <div class="field">
          <label for="address">Alamat Lengkap</label>
          <input type="text" name="address" id="address" required placeholder="Alamat Lengkap"
            class="capitalize-input" />
        </div>

        <div id="registerMessage"></div>

        <button type="submit" id="btnRegister" class="btn-login">Daftar</button>
      </form>

      <div class="login-footer">
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
  document.getElementById('registerForm').onsubmit = async function (e) {
    e.preventDefault();

    const btn = document.getElementById('btnRegister');
    const msgDiv = document.getElementById('registerMessage');
    const originalText = btn.innerText;

    btn.innerText = "Memproses...";
    btn.style.background = "#ccc";
    btn.disabled = true;
    msgDiv.innerHTML = "";

    const formData = new FormData(this);

    try {
      const response = await fetch('register.php', {
        method: 'POST',
        body: formData
      });
      const result = await response.json();

      if (result.status === 'success') {
        btn.innerText = "Berhasil register!";
        btn.style.background = "#28a745";

        setTimeout(() => {
          window.location.href = "login.php";
        }, 1500);
      } else {
        msgDiv.innerHTML = `<div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; text-align: center;">${result.message}</div>`;
        btn.innerText = originalText;
        btn.style.background = "";
        btn.disabled = false;
      }
    } catch (error) {
      msgDiv.innerHTML = `<div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; text-align: center;">Terjadi kesalahan sistem.</div>`;
      btn.innerText = originalText;
      btn.style.background = "";
      btn.disabled = false;
    }
  };
</script>