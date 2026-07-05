<?php
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
    header("Location: dashboard.php");
    exit;
} else {
    echo "Gagal update status: " . $conn->error;
}

mysqli_close($conn);
?>