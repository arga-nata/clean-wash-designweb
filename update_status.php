<?php
$secret_key = "a7b8c9d0e1f2g3h4i5j6k7l8m9n0o1p2q3r4s5t6";

if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Access Denied: Invalid Secret Key.');
}

$id = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';

if (empty($id) || empty($status)) {
    die('Error: ID atau Status tidak ditemukan.');
}

$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal!");
}

$stmt = $conn->prepare("UPDATE tbl_orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    header("Location: dashboard_orders.php?key=" . $secret_key);
} else {
    echo "Gagal update status: " . $conn->error;
}

mysqli_close($conn);
?>