<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'includes/connection.php';

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['service_name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $unit = $_POST['unit'] ?? 'kg';
    $estimate = trim($_POST['estimate'] ?? '');

    if (empty($name) || $price <= 0 || empty($estimate)) {
        $message = "Nama, Harga, dan Estimasi wajib diisi dengan benar!";
        $message_type = "danger";
    } else {
        $stmt = $conn->prepare("UPDATE tbl_services SET service_name = ?, price = ?, unit = ?, estimate = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $name, $price, $unit, $estimate, $id);

        if ($stmt->execute()) {
            header("Location: services.php");
            exit;
        } else {
            $message = "Gagal memperbarui layanan: " . $conn->error;
            $message_type = "danger";
        }
    }
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID Layanan tidak valid!");
}

$stmt = $conn->prepare("SELECT * FROM tbl_services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    die("Layanan tidak ditemukan!");
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
    .field-group select,
    .field-group textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .field-group input:focus,
    .field-group select:focus,
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
        <h2 class="form-title">Edit Layanan</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type; ?> text-center"
                style="border-radius: 12px; font-size: 0.9rem; margin-bottom: 20px;">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= $service['id']; ?>">

            <div class="field-group">
                <label for="service_name">Nama Layanan</label>
                <input type="text" name="service_name" id="service_name" required
                    value="<?= htmlspecialchars($service['service_name']); ?>">
            </div>

            <div class="field-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" name="price" id="price" required
                    value="<?= htmlspecialchars($service['price']); ?>">
            </div>

            <div class="field-group">
                <label for="unit">Satuan</label>
                <select name="unit" id="unit">
                    <option value="kg" <?= $service['unit'] == 'kg' ? 'selected' : ''; ?>>kg</option>
                    <option value="pcs" <?= $service['unit'] == 'pcs' ? 'selected' : ''; ?>>pcs</option>
                    <option value="meter" <?= $service['unit'] == 'meter' ? 'selected' : ''; ?>>meter</option>
                </select>
            </div>

            <div class="field-group">
                <label for="estimate">Estimasi Selesai</label>
                <input type="text" name="estimate" id="estimate" required
                    value="<?= htmlspecialchars($service['estimate']); ?>">
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        <a href="services.php" class="btn-back">← Kembali ke Daftar Layanan</a>
    </div>
</div>

<?php
include 'includes/footer.php';
?>