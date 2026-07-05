<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include 'includes/connection.php';

  header('Content-Type: application/json');

  if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi Database Gagal!']);
    exit;
  }

  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, password, username FROM tbl_customers WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['customer_id'] = $user['id'];
      $_SESSION['username'] = $username;

      $role = 'user';
      if ($username === 'admin') {
        $role = 'admin';
      }

      echo json_encode(['status' => 'success', 'role' => $role]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Password salah, silakan coba kembali.']);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan dalam sistem.']);
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
        <h2>Selamat Datang</h2>
        <p>Kelola pesanan Anda dengan lebih mudah</p>
      </div>

      <form id="loginForm">
        <div class="field">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" required placeholder="Username" />
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="Password" />
        </div>

        <div id="loginMessage"></div>

        <button type="submit" id="btnLogin" class="btn-login">Login</button>
      </form>

      <div class="login-footer">
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
  document.getElementById('loginForm').onsubmit = async function (e) {
    e.preventDefault();

    const btn = document.getElementById('btnLogin');
    const msgDiv = document.getElementById('loginMessage');
    const originalText = btn.innerText;

    btn.innerText = "Memproses...";
    btn.style.background = "#ccc";
    btn.disabled = true;
    msgDiv.innerHTML = "";

    const formData = new FormData(this);

    try {
      const response = await fetch('login.php', {
        method: 'POST',
        body: formData
      });
      const result = await response.json();

      if (result.status === 'success') {
        btn.innerText = "Berhasil login!";
        btn.style.background = "#28a745";

        setTimeout(() => {
          if (result.role === 'admin') {
            window.location.href = "dashboard.php";
          } else {
            window.location.href = "index.php";
          }
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
</body>

</html>