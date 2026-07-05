<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
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
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        header .nav-links {
            display: flex !important;
            list-style: none !important;
            padding-left: 0 !important;
            margin-bottom: 0 !important;
            align-items: center;
        }

        header .nav-links li {
            margin: 0 !important;
            padding: 0 !important;
        }

        header .nav-links a {
            text-decoration: none !important;
            display: inline-block !important;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .logo-img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div class="page" style="display: flex; flex-direction: column; min-height: 100vh;">
        <header>
            <nav class="nav">
                <div class="logo">
                    <a href="dashboard.php" class="logo-container">
                        <img src="logo.png" alt="Logo" class="logo-img">
                        <span class="gradient-text">CleanWash Admin</span>
                    </a>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php"
                            class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Web Utama</a></li>
                    <li><a href="dashboard.php"
                            class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a></li>
                    <li><a href="users.php"
                            class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">Users</a></li>
                    <li><a href="services.php"
                            class="<?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Layanan</a></li>
                    <li>
                        <a href="logout.php" class="<?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">Log Out</a>
                    </li>
                </ul>
            </nav>
        </header>
