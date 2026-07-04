<?php
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal!");
}

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);

    $sql_items = "DELETE FROM tbl_order_items WHERE order_id = '$order_id'";
    mysqli_query($conn, $sql_items);

    $sql_order = "DELETE FROM tbl_orders WHERE id = '$order_id'";
    if (mysqli_query($conn, $sql_order)) {
        $cid = isset($_GET['cid']) ? $_GET['cid'] : 1;
        header("Location: riwayat-order.php?cid=$cid");
    } else {
        echo "Gagal menghapus pesanan: " . mysqli_error($conn);
    }
} else {
    echo "ID pesanan tidak ditemukan!";
}

mysqli_close($conn);
?>