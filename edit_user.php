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

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $username = trim($_POST['username'] ?? '');
    $name = trim(ucwords(strtolower($_POST['customer_name'] ?? '')));
    $phone = trim($_POST['customer_phone'] ?? '');
    $address = trim(ucwords(strtolower($_POST['customer_address'] ?? '')));

    if (empty($username) || empty($name) || empty($phone) || empty($address)) {
        $message = "Semua kolom wajib diisi!";
        $message_type = "danger";
    } else {
        $stmt = $conn->prepare("UPDATE tbl_customers SET username = ?, customer_name = ?, customer_phone = ?, customer_address = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $name, $phone, $address, $id);

        if ($stmt->execute()) {
            header("Location: users.php");
            exit;
        } else {
            $message = "Gagal memperbarui data: " . $conn->error;
            $message_type = "danger";
        }
    }
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID User tidak valid!");
}

$stmt = $conn->prepare("SELECT * FROM tbl_customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan!");
}

include 'includes/admin_header.php';
?>

<style>
    body {
        background-color: #f8fafc !important;
    }

    .admin-main-wrapper {
        max-width: 600px;
        margin: 60px auto;
        padding: 0 20px;
        flex: 1;
    }

    .admin-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        padding: 40px;
    }

    .form-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 30px;
        text-align: center;
        font-size: 1.5rem;
    }

    .field-group {
        margin-bottom: 20px;
    }

    .field-group label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 8px;
    }

    .field-group input,
    .field-group textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .field-group input:focus,
    .field-group textarea:focus {
        outline: none;
        border-color: #49b1c8;
        box-shadow: 0 0 0 3px rgba(73, 177, 200, 0.1);
    }

    .btn-save {
        width: 100%;
        padding: 14px;
        background-color: #49b1c8;
        color: white !important;
        border: 1px solid #49b1c8;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 10px;
        text-align: center;
        display: block;
        text-decoration: none;
    }

    .btn-save:hover {
        background-color: #3ba8ba;
        border-color: #3ba8ba;
    }

    .btn-back {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #94a3b8;
        text-decoration: none !important;
        font-size: 0.85rem;
        transition: color 0.2s ease;
    }

    .btn-back:hover {
        color: #64748b;
    }
</style>

<div class="admin-main-wrapper">
    <div class="admin-card">
        <h2 class="form-title">Edit Data Customer</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type; ?> text-center"
                style="border-radius: 12px; font-size: 0.9rem; margin-bottom: 20px;">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= $user['id']; ?>">

            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required
                    value="<?= htmlspecialchars($user['username']); ?>">
            </div>

            <div class="field-group">
                <label for="customer_name">Nama Lengkap</label>
                <input type="text" name="customer_name" id="customer_name" required
                    value="<?= htmlspecialchars($user['customer_name']); ?>">
            </div>

            <div class="field-group">
                <label for="customer_phone">No. WhatsApp</label>
                <input type="text" name="customer_phone" id="customer_phone" required
                    value="<?= htmlspecialchars($user['customer_phone']); ?>">
            </div>

            <div class="field-group">
                <label for="customer_address">Alamat Lengkap</label>
                <textarea name="customer_address" id="customer_address" required rows="3"
                    style="resize: none;"><?= htmlspecialchars($user['customer_address']); ?></textarea>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        <a href="users.php" class="btn-back">← Kembali ke Daftar User</a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>