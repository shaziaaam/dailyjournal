<?php
include "session_init.php";

include "koneksi.php";  

// Check jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Admin</title>
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet"
    />
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    /> 
    <link rel="stylesheet" href="style.css" />
    <script src="theme.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md site-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="">Jia's Daily Journal</a>
            <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-md-0 align-items-md-center gap-md-1">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=article">Article</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=gallery">Gallery</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home"><b>Homepage</b></a>
                    </li> 
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="admin.php?page=profil">Profile <?= htmlspecialchars($_SESSION['username']) ?></a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ms-md-2 mt-2 mt-md-0">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Toggle theme" aria-label="Toggle theme">
                            <i class="bi bi-moon-stars-fill icon-moon"></i>
                            <i class="bi bi-brightness-high-fill icon-sun"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Content -->
    <section id="content" class="p-5 flex-grow-1">
        <div class="container">
            <?php
            // hanya halaman yang terdaftar yang boleh dimuat
            $halaman = ['dashboard' => 'Dashboard', 'article' => 'Article', 'gallery' => 'Gallery', 'profil' => 'Profile'];
            $page = $_GET['page'] ?? 'dashboard';

            if (isset($halaman[$page])) {
            ?>
                <h4 class="admin-title display-6"><?= $halaman[$page] ?></h4>
                <?php
                include($page . ".php");
            } else {
            ?>
                <h4 class="admin-title display-6">Not found</h4>
                <p>Page not found.</p>
            <?php
            }
            ?>
        </div>
    </section>
    <!-- Content End -->

    <!-- Footer -->
    <footer id="footer" class="site-footer text-center py-5">
        <div class="social mb-3">
            <a href="https://www.instagram.com/shaziaaam" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.linkedin.com/in/shaziaaam/" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="https://wa.me/628812406778" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a href="mailto:shaziaaaam@gmail.com" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
        </div>
        <div>
            Copyright &copy; 2024 - <span>Shazia Mirza.</span> All Right Reserved.
        </div>
    </footer>
    <!-- Footer End -->

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
    ></script>
</body>
</html>
