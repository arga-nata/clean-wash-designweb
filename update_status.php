<?php
$id = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';

if (empty($id) || empty($status)) {
    die('Error: ID atau Status tidak ditemukan.');
}

include 'includes/connection.php';

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