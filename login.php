<?php
//memulai session atau melanjutkan session yang sudah ada
session_start();

//menyertakan code dari file koneksi
include "koneksi.php";

//check jika sudah ada user yang login arahkan ke halaman admin
if (isset($_SESSION['username'])) {
    header("location:admin.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];

    //menggunakan fungsi enkripsi md5 supaya sama dengan password yang tersimpan di database
    $password = md5($_POST['pass']);

    //prepared statement
    $stmt = $conn->prepare("SELECT username
                            FROM user
                            WHERE username=? AND password=?");

    //database executes the statement dengan parameter username dan password
    $stmt->execute([$username, $password]);

    //mengambil baris hasil sebagai array asosiatif
    $row = $stmt->fetch();

    //check apakah ada baris hasil data user yang cocok
    if (!empty($row)) {
        //jika ada, simpan variable username pada session
        session_regenerate_id(true);
        $_SESSION['username'] = $row['username'];

        //mengalihkan ke halaman admin
        header("location:admin.php");
        exit;
    }

    //jika tidak ada (gagal), tampilkan pesan di halaman login
    $error = "Username atau password salah.";
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | My Daily Journal</title>
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet"
    />
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    />
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="style.css" />
    <script src="theme.js"></script>
</head>
<body class="login-page">
    <button type="button" class="theme-toggle login-theme" id="theme-toggle" title="Ganti tema" aria-label="Ganti tema">
        <i class="bi bi-moon-stars-fill icon-moon"></i>
        <i class="bi bi-brightness-high-fill icon-sun"></i>
    </button>

    <main class="login-wrap">
        <div class="login-card">
            <div class="text-center mb-4">
                <img src="img/favicon.png" alt="" class="login-logo" />
                <h1 class="login-brand">Jia's Daily Journal</h1>
                <p class="text-secondary mb-0">Masuk untuk mengelola article dan gallery</p>
            </div>

            <?php if ($error) { ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php } ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="user" class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" id="user" name="user" class="form-control" placeholder="Username"
                            value="<?= htmlspecialchars($_POST['user'] ?? '') ?>" autocomplete="username" required autofocus />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="pass" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="pass" name="pass" class="form-control" placeholder="Password"
                            autocomplete="current-password" required />
                        <button type="button" class="btn btn-outline-secondary" id="lihat-pass" aria-label="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-accent btn-lg">Login</button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke beranda</a>
            </div>
        </div>
    </main>

    <script>
        var pass = document.getElementById("pass");
        var lihat = document.getElementById("lihat-pass");
        lihat.addEventListener("click", function () {
            var tampil = pass.type === "password";
            pass.type = tampil ? "text" : "password";
            lihat.innerHTML = tampil ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            lihat.setAttribute("aria-label", tampil ? "Sembunyikan password" : "Tampilkan password");
        });
    </script>
</body>
</html>
