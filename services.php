<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit;
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $name = trim($_POST['service_name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $unit = $_POST['unit'] ?? 'kg';
    $estimate = trim($_POST['estimate'] ?? '');

    if (empty($name) || $price <= 0 || empty($estimate)) {
        $message = "Nama, Harga, dan Estimasi wajib diisi dengan benar!";
        $message_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO tbl_services (service_name, price, unit, estimate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $unit, $estimate);
        
        if ($stmt->execute()) {
            $message = "Layanan berhasil ditambahkan!";
            $message_type = "success";
        } else {
            $message = "Gagal menambahkan layanan: " . $conn->error;
            $message_type = "danger";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tbl_services WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: services.php");
        exit;
    }
}

$query = "SELECT * FROM tbl_services ORDER BY id DESC";
$result = mysqli_query($conn, $query);

include 'includes/admin_header.php';
?>

<style>
    body {
        background-color: #f8fafc !important;
    }

    .admin-main-wrapper {
        max-width: 1000px;
        margin: 60px auto;
        padding: 0 20px;
        flex: 1;
    }

    .admin-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        padding: 40px;
        margin-bottom: 30px;
    }

    .admin-card-table {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 30px;
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

    .field-group input, .field-group select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .field-group input:focus, .field-group select:focus {
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

    .custom-table-wrapper {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .admin-table thead {
        background-color: rgba(73, 177, 200, 0.9);
        color: white;
    }

    .admin-table th {
        padding: 18px 24px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: none;
    }

    .admin-table td {
        padding: 16px 24px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .admin-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-admin-outline {
        background: white;
        color: #49b1c8 !important;
        border: 1px solid #49b1c8;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-admin-outline:hover {
        background: #f0faff;
        color: #3ba8ba !important;
        border-color: #3ba8ba;
    }
</style>

<div class="admin-main-wrapper">
    <div class="admin-card">
        <?php if ($message): ?>
            <div class="alert alert-<?= $message_type; ?> text-center" style="border-radius: 12px; font-size: 0.9rem; margin-bottom: 20px;">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="row g-3">
            <div class="col-md-6 field-group">
                <label for="service_name">Nama Layanan</label>
                <input type="text" name="service_name" id="service_name" required placeholder="Contoh: Cuci Karpet">
            </div>
            <div class="col-md-3 field-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" name="price" id="price" required placeholder="Contoh: 15000">
            </div>
            <div class="col-md-3 field-group">
                <label for="unit">Satuan</label>
                <select name="unit" id="unit">
                    <option value="kg">kg</option>
                    <option value="pcs">pcs</option>
                    <option value="meter">meter</option>
                </select>
            </div>
            <div class="col-12 field-group">
                <label for="estimate">Estimasi Selesai</label>
                <input type="text" name="estimate" id="estimate" required placeholder="Contoh: 2 Hari">
            </div>
            <div class="col-12">
                <button type="submit" name="add_service" class="btn-save">Simpan Layanan</button>
            </div>
        </form>
    </div>

    <div class="admin-card-table">
        <div class="custom-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Satuan</th>
                        <th>Estimasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td style="font-weight: 600; color: #1e293b;"><?= $row['service_name']; ?></td>
                                <td style="font-weight: 700;">Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                                <td><?= $row['unit']; ?></td>
                                <td><?= $row['estimate']; ?></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="edit_service.php?id=<?= $row['id']; ?>" class="btn-admin-outline" title="Edit">Edit</a>
                                        <a href="services.php?delete=<?= $row['id']; ?>" 
                                           class="btn-admin-outline" 
                                           onclick="return confirm('Hapus layanan ini?')" title="Hapus">
                                            ✕
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; color: #94a3b8;">Belum ada layanan tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
