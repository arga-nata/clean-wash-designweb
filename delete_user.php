<?php
include 'includes/connection.php';

if (!$conn) {
    die("Koneksi Database Gagal!");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM tbl_customers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit;
    } else {
        echo "Gagal menghapus user: " . $conn->error;
    }
} else {
    echo "ID user tidak ditemukan!";
}

mysqli_close($conn);
?>