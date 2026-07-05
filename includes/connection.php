<?php
$host = "db";
$user = "db";
$pass = "db";
$db = "db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>