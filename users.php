<?php
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection Failed!");
}

$query = "SELECT * FROM tbl_customers ORDER BY (username = 'admin') DESC, id DESC";
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
                        <th>ID</th>
                        <th>Role</th>
                        <th>Nama Lengkap</th>
                        <th>WhatsApp</th>
                        <th>Alamat</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $is_admin = ($row['username'] === 'admin');
                            $role_text = $is_admin ? 'Admin' : 'User';
                            ?>
                            <tr>
                                <td style="font-weight: 600; color: #475569;">#<?= $row['id']; ?></td>
                                <td><?= $role_text; ?></td>
                                <td style="font-weight: 600; color: #1e293b;"><?= $row['customer_name']; ?></td>
                                <td><?= $row['customer_phone']; ?></td>
                                <td style="font-size: 0.8rem;"><?= $row['customer_address']; ?></td>
                                <td class="text-muted small"><?= $row['username']; ?></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn-admin btn-admin-outline"
                                            title="Edit User">
                                            Edit
                                        </a>
                                        <?php if (!$is_admin): ?>
                                            <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn-admin btn-admin-outline"
                                                onclick="return confirm('Hapus user ini?')">
                                                ✕
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 60px; color: #94a3b8;">
                                <div style="font-size: 2rem; margin-bottom: 10px; color: #ccc;">?</div>
                                <p>Tidak ada data customer ditemukan.</p>
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