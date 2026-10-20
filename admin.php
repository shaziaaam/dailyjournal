<?php
session_start();

include "koneksi.php";  

// Tanpa login: semua pengunjung memakai akun admin
$_SESSION['username'] = "admin";
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Admin</title>
    <link rel="icon" href="https://i.pinimg.com/736x/07/a7/11/07a7118298e6d8e6ee655a7046199ee9.jpg" />
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
    <style>  
        html {
            position: relative;
            min-height: 100%;
        }
        
        body {
            margin-bottom: 100px; /* Margin bottom by footer height */
        }
        
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100px; /* Set the fixed height of the footer here */
            background: var(--tint);
            border-top: 1px solid var(--line);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm site-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="">Jia's Daily Journal</a>
            <button
            class="navbar-toggler"
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
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-sm-center gap-sm-1">
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
                        <?= $_SESSION['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="admin.php?page=profil">Profile <?= $_SESSION['username'] ?></a></li>
                        </ul>
                    </li>
                    <li class="nav-item ms-sm-2 mt-2 mt-sm-0">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Ganti tema" aria-label="Ganti tema">
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
    <section id="content" class="p-5">
        <div class="container">
            <?php
            if (isset($_GET['page'])) {
            ?>
                <h4 class="admin-title display-6"><?= ucfirst($_GET['page']) ?></h4>
                <?php
                $page = basename($_GET['page']); // Hindari LFI
                if (file_exists($page . ".php")) {
                    include($page . ".php");
                } else {
                    echo "<p>Halaman tidak ditemukan.</p>";
                }
            } else {
            ?>
                <h4 class="admin-title display-6">Dashboard</h4>
                <?php
                include("dashboard.php");
            }
            ?>
        </div>
    </section>
    <!-- Content End -->

    <!-- Footer -->
    <footer class="text-center p-3 site-footer">
        <div class="social">
            <a href="http://surl.li/oxsat" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="http://surl.li/pozmdl" target="_blank"><i class="bi bi-twitter-x"></i></a>
            <a href="http://surl.li/jnbiwg" target="_blank"><i class="bi bi-youtube"></i></a>
            <a href="http://surl.li/uyeykk" target="_blank"><i class="bi bi-tiktok"></i></a>
            <a href="http://surl.li/oxskz" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
        <br/>
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
