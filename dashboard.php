<?php
// 1. KONEKSI DATABASE
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection Failed!");
}

// --- STATS QUERY ---
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_orders");
$total_data = mysqli_fetch_assoc($total_query);

$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_orders WHERE status = 'Pending'");
$pending_data = mysqli_fetch_assoc($pending_query);

$done_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_orders WHERE status = 'Selesai'");
$done_data = mysqli_fetch_assoc($done_query);

// 2. QUERY ORDER LIST
$query = "SELECT o.id, c.customer_name, o.total_amount, o.status, o.order_date, 
              GROUP_CONCAT(s.service_name SEPARATOR ', ') as services 
              FROM tbl_orders o 
              LEFT JOIN tbl_customers c ON o.customer_id = c.id 
              LEFT JOIN tbl_order_items oi ON o.id = oi.order_id 
              LEFT JOIN tbl_services s ON oi.service_id = s.id 
              GROUP BY o.id 
              ORDER BY o.id DESC";
$result = mysqli_query($conn, $query);

// 3. PANGGIL NAVBAR
include 'includes/header.php';
?>

<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 16px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .status-badge {
        padding: 0.5em 1em;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .table-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
    }
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">📦 Order Management</h2>
            <p class="text-muted">Pantau dan kelola semua pesanan laundry pelanggan.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-dark p-2">Admin Mode</span>
        </div>
    </div>

    <!-- Stat Cards Section -->
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                        <span class="fs-3">📋</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small fw-medium">Total Orderan</p>
                        <h3 class="fw-bold mb-0"><?= $total_data['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                        <span class="fs-3">⏳</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small fw-medium">Menunggu (Pending)</p>
                        <h3 class="fw-bold mb-0"><?= $pending_data['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-white shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3 me-3">
                        <span class="fs-3">✅</span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small fw-medium">Selesai</p>
                        <h3 class="fw-bold mb-0"><?= $done_data['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Daftar Pesanan Terbaru</h5>
            <small class="text-muted">Urutan berdasarkan ID terbaru</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Services</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="fw-medium">#<?= $row['id']; ?></td>
                                <td><strong><?= $row['customer_name'] ?? 'Unknown'; ?></strong></td>
                                <td class="text-muted small"><?= $row['services'] ?? '-'; ?></td>
                                <td class="fw-bold text-dark">Rp <?= number_format($row['total_amount'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status-badge <?= ($row['status'] == 'Selesai') ? 'bg-success text-white' : 'bg-warning text-dark'; ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td class="text-muted small"><?= $row['order_date']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="update_status.php?id=<?= $row['id']; ?>&status=Selesai"
                                            class="btn btn-sm btn-outline-success" title="Set Selesai">
                                            ✓ Selesai
                                        </a>
                                        <a href="delete_order.php?id=<?= $row['id']; ?>"
                                            class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus orderan ini?')" title="Hapus">
                                            🗑️ Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-3">📦</div>
                                <p>Belum ada pesanan. Silakan buat pesanan di halaman depan!</p>
                            </td>
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
