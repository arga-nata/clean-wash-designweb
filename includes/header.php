<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$cid = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : (isset($_GET['cid']) ? $_GET['cid'] : '2');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        header .nav-links {
            display: flex !important;
            list-style: none !important;
            padding-left: 0 !important;
            margin-bottom: 0 !important;
        }

        header .nav-links li {
            margin: 0 !important;
            padding: 0 !important;
        }

        header .nav-links a {
            text-decoration: none !important;
            display: inline-block !important;
        }
    </style>
</head>

<body>
    <div class="page" style="display: flex; flex-direction: column; min-height: 100vh;">
        <header>
            <nav class="nav">
                <div class="logo"><span class="gradient-text">CleanWash</span></div>
                <ul class="nav-links">
                    <li><a href="index.php"
                            class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a></li>
                    <li><a href="tentang_kami.php"
                            class="<?php echo ($current_page == 'tentang_kami.php') ? 'active' : ''; ?>">Tentang
                            Kami</a></li>
                    <li><a href="harga.php"
                            class="<?php echo ($current_page == 'harga.php') ? 'active' : ''; ?>">Harga</a></li>
                    <li><a href="paket.php"
                            class="<?php echo ($current_page == 'paket.php') ? 'active' : ''; ?>">Paket
                            Langganan</a></li>
                    <li><a href="keranjang.php"
                            class="<?php echo ($current_page == 'keranjang.php') ? 'active' : ''; ?>">Pemesanan</a></li>
                    <li><a href="galeri.php"
                            class="<?php echo ($current_page == 'galeri.php') ? 'active' : ''; ?>">Galeri</a></li>
                    <li><a href="riwayat-order.php?cid=<?php echo $cid; ?>"
                            class="<?php echo ($current_page == 'riwayat-order.php' || $current_page == 'detail-order.php') ? 'active' : ''; ?>">Riwayat</a>
                    </li>
                    <li><a href="kontak.php"
                            class="<?php echo ($current_page == 'kontak.php') ? 'active' : ''; ?>">Hubungi Kami</a></li>
                    <li>
                        <?php if (isset($_SESSION['customer_id'])): ?>
                            <a href="logout.php" class="<?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">Log
                                Out</a>
                        <?php else: ?>
                            <a href="login.php" class="<?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">Log
                                In</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </header>