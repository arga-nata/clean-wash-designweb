<?php
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection Failed!");
}

$query = "SELECT o.id, c.customer_name, o.total_amount, o.status, o.order_date, 
              GROUP_CONCAT(s.service_name SEPARATOR ', ') as services 
              FROM tbl_orders o 
              LEFT JOIN tbl_customers c ON o.customer_id = c.id 
              LEFT JOIN tbl_order_items oi ON o.id = oi.order_id 
              LEFT JOIN tbl_services s ON oi.service_id = s.id 
              GROUP BY o.id 
              ORDER BY (o.status = 'Selesai') ASC, o.id DESC";
$result = mysqli_query($conn, $query);

include 'includes/admin_header.php';
?>

<style>
    body {
        background-color: #f8fafc !important;
    }

    .admin-main-wrapper {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
        flex: 1;
    }

    .admin-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        overflow: hidden;
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

    .status-text {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .status-pending {
        color: #dc2626;
    }

    .status-proses {
        color: #ca8a04;
    }

    .status-done {
        color: #16a34a;
    }

    .btn-admin {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        cursor: pointer;
    }

    .btn-admin-primary {
        background-color: #49b1c8;
        color: white !important;
        border: 1px solid #49b1c8;
    }

    .btn-admin-primary:hover {
        background-color: #3ba8ba;
        border-color: #3ba8ba;
        color: white !important;
    }

    .btn-admin-outline {
        background: white;
        color: #49b1c8 !important;
        border: 1px solid #49b1c8;
    }

    .btn-admin-outline:hover {
        background: #f0faff;
        color: #3ba8ba !important;
        border-color: #3ba8ba;
    }
</style>

<div class="admin-main-wrapper">
    <div class="admin-card">
        <div class="custom-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Services</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $status_class = 'status-pending';
                            if ($row['status'] == 'Selesai') {
                                $status_class = 'status-done';
                            } elseif ($row['status'] == 'Proses') {
                                $status_class = 'status-proses';
                            }

                            $next_status = '';
                            $btn_text = '';
                            if ($row['status'] == 'Pending') {
                                $next_status = 'Proses';
                                $btn_text = '➜ Proses';
                            } elseif ($row['status'] == 'Proses') {
                                $next_status = 'Selesai';
                                $btn_text = '✓ Selesai';
                            }
                            ?>
                            <tr>
                                <td style="font-weight: 600; color: #475569;">#<?= $row['id']; ?></td>
                                <td style="font-weight: 600; color: #1e293b;"><?= $row['customer_name'] ?? 'Unknown'; ?></td>
                                <td style="font-size: 0.8rem; color: #94a3b8;"><?= $row['services'] ?? '-'; ?></td>
                                <td style="font-weight: 700; color: #334155;">Rp
                                    <?= number_format($row['total_amount'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <span class="status-text <?= $status_class; ?>">
                                        <?= $row['status']; ?></span>
                                </td>
                                <td style="font-size: 0.8rem;"><?= $row['order_date']; ?></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <?php if ($next_status): ?>
                                            <a href="update_status.php?id=<?= $row['id']; ?>&status=<?= $next_status; ?>"
                                                class="btn-admin btn-admin-primary" title="Majukan Status">
                                                <?= $btn_text; ?>
                                            </a>
                                        <?php endif; ?>
                                        <a href="delete_order.php?id=<?= $row['id']; ?>&admin=1"
                                            class="btn-admin btn-admin-outline" onclick="return confirm('Hapus orderan ini?')"
                                            title="Hapus">
                                            ✕
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 60px; color: #94a3b8;">
                                <div style="font-size: 2rem; margin-bottom: 10px; color: #ccc;">?</div>
                                <p>Belum ada data pesanan yang tersedia.</p>
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