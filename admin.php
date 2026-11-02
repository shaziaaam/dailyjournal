<?php
include "session_init.php";

include "koneksi.php";
include "admin_ui.php";

// Check jika belum ada user yang login, arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

// hanya halaman yang terdaftar yang boleh dimuat
$halaman = [
    'dashboard' => ['Dashboard', 'bi-grid-1x2', 'Overview'],
    'article'   => ['Article', 'bi-journal-text', 'Content'],
    'gallery'   => ['Gallery', 'bi-images', 'Content'],
    'profil'    => ['Profile', 'bi-person-circle', 'Account'],
];
$page = $_GET['page'] ?? 'dashboard';

// foto profil untuk sidebar
$stmt = $conn->prepare("SELECT foto FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$foto_user = (string)$stmt->fetchColumn();
$ada_foto = $foto_user !== '' && is_file("img/" . $foto_user);
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
    href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
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
    <link rel="stylesheet" href="admin.css" />
    <script src="theme.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // notifikasi kecil di pojok kanan bawah, menggantikan alert() bawaan browser
        function tampilToast(pesan, tipe) {
            var wadah = document.getElementById("toast-wrap");
            if (!wadah) return;
            var el = document.createElement("div");
            el.className = "toast-item" + (tipe === "err" ? " err" : "");
            el.setAttribute("role", tipe === "err" ? "alert" : "status");
            var ikon = document.createElement("i");
            ikon.className = "bi " + (tipe === "err" ? "bi-exclamation-octagon-fill" : "bi-check-circle-fill");
            var teks = document.createElement("span");
            teks.textContent = pesan;
            el.appendChild(ikon);
            el.appendChild(teks);
            wadah.appendChild(el);
            setTimeout(function () {
                el.classList.add("keluar");
                setTimeout(function () { el.remove(); }, 350);
            }, tipe === "err" ? 6000 : 3500);
        }

        // simpan pesan lalu pindah halaman; pesan ditampilkan setelah halaman baru terbuka
        function kirimToast(pesan, tipe, url) {
            try {
                sessionStorage.setItem("toast", JSON.stringify({ m: pesan, t: tipe }));
            } catch (e) {}
            document.location = url;
        }
    </script>
</head>
<body class="admin-body">
    <div class="admin-shell">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar" aria-label="Admin navigation">
            <a class="side-brand" href="admin.php?page=dashboard">
                <img src="img/favicon.png" alt="" />
                <span>Jia's Daily<br>Journal</span>
            </a>

            <?php
            $grup_sebelumnya = '';
            foreach ($halaman as $kunci => [$nama, $ikon, $grup]) {
                if ($grup !== $grup_sebelumnya) {
                    if ($grup_sebelumnya !== '') {
                        echo '</nav>';
                    }
                    echo '<div class="side-label">' . htmlspecialchars($grup) . '</div><nav class="side-nav">';
                    $grup_sebelumnya = $grup;
                }
                $aktif = $page === $kunci ? ' active' : '';
            ?>
                <a class="side-link<?= $aktif ?>" href="admin.php?page=<?= $kunci ?>"<?= $aktif ? ' aria-current="page"' : '' ?>>
                    <i class="bi <?= $ikon ?>"></i> <?= htmlspecialchars($nama) ?>
                </a>
            <?php
            }
            echo '</nav>';
            ?>

            <div class="side-foot">
                <a class="side-link" href="index.php" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right"></i> View site
                </a>
                <div class="side-user">
                    <?php if ($ada_foto) { ?>
                        <img class="avatar" src="img/<?= htmlspecialchars($foto_user) ?>" alt="" />
                    <?php } else { ?>
                        <span class="avatar"><?= htmlspecialchars(strtoupper(mb_substr($_SESSION['username'], 0, 1))) ?></span>
                    <?php } ?>
                    <div class="who">
                        <div class="name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                        <small>Admin</small>
                    </div>
                    <a class="btn-icon btn-logout" href="logout.php" title="Logout" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </aside>
        <div class="sidebar-scrim" id="sidebar-scrim"></div>

        <!-- Content -->
        <main class="admin-main">
            <div class="admin-topbar">
                <button type="button" class="burger" id="burger" aria-label="Open menu" aria-controls="sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <span class="brand">Jia's Daily Journal</span>
                <button type="button" class="theme-toggle ms-auto" id="theme-toggle" title="Toggle theme" aria-label="Toggle theme">
                    <i class="bi bi-moon-stars-fill icon-moon"></i>
                    <i class="bi bi-brightness-high-fill icon-sun"></i>
                </button>
            </div>

            <div class="page-enter" id="page-content">
            <?php
            if (isset($halaman[$page])) {
                include($page . ".php");
            } else {
            ?>
                <div class="page-head">
                    <div>
                        <h1 class="page-title">Not found</h1>
                        <p class="page-sub">This page does not exist. <a href="admin.php?page=dashboard">Back to dashboard</a>.</p>
                    </div>
                </div>
            <?php
            }
            ?>
            </div>

            <footer class="mt-auto pt-4 text-center text-secondary small">
                Copyright &copy; 2025 - <span>Shazia Mirza.</span> All Right Reserved.
            </footer>
        </main>
    </div>

    <div class="toast-wrap" id="toast-wrap" aria-live="polite"></div>

    <!-- pop-up gambar besar (dipakai halaman article dan gallery) -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-label="Image preview" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="popup-gambar" src="" alt="" class="img-fluid rounded" />
                </div>
            </div>
        </div>
    </div>

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
    ></script>
    <script>
        // menu geser di layar kecil
        (function () {
            var tutup = function () { document.body.classList.remove("sidebar-open"); };
            document.getElementById("burger").addEventListener("click", function () {
                document.body.classList.toggle("sidebar-open");
            });
            document.getElementById("sidebar-scrim").addEventListener("click", tutup);
            document.querySelectorAll(".side-link").forEach(function (a) { a.addEventListener("click", tutup); });
        })();

        // pindah menu di sidebar: halaman lama memudar dulu, lalu halaman baru muncul dengan animasi
        (function () {
            var gerakKurang = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
            var isi = document.getElementById("page-content");

            // tombol kembali di browser: pastikan halaman tidak tertinggal dalam keadaan memudar
            window.addEventListener("pageshow", function () { isi.classList.remove("page-leaving"); });

            if (gerakKurang) return;
            document.querySelectorAll(".side-link[href^='admin.php']").forEach(function (a) {
                a.addEventListener("click", function (e) {
                    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
                    if (a.classList.contains("active")) return;
                    e.preventDefault();
                    isi.classList.add("page-leaving");
                    setTimeout(function () { document.location = a.getAttribute("href"); }, 170);
                });
            });
        })();

        // tampilkan pesan hasil simpan/hapus dari halaman sebelumnya
        try {
            var simpanan = sessionStorage.getItem("toast");
            if (simpanan) {
                sessionStorage.removeItem("toast");
                var t = JSON.parse(simpanan);
                tampilToast(t.m, t.t);
            }
        } catch (e) {}

        // memilih foto: dikecilkan otomatis di browser (maks 1800px, JPEG), lalu ditampilkan pratinjaunya.
        // Berlaku juga untuk modal yang dimuat lewat ajax. Format WebP ikut diubah jadi JPEG.
        var MAKS_BYTE = 2000000;
        var MAKS_SISI = 1800;

        async function kecilkan(file) {
            if (!/^image\/(jpeg|png|webp)$/.test(file.type)) return file;
            var bmp = await createImageBitmap(file, { imageOrientation: "from-image" });
            var skala = Math.min(1, MAKS_SISI / Math.max(bmp.width, bmp.height));
            if (skala === 1 && file.size <= 1500000 && file.type !== "image/webp") return file;
            var kanvas = document.createElement("canvas");
            kanvas.width = Math.round(bmp.width * skala);
            kanvas.height = Math.round(bmp.height * skala);
            var ctx = kanvas.getContext("2d");
            ctx.fillStyle = "#ffffff"; // PNG transparan jadi latar putih
            ctx.fillRect(0, 0, kanvas.width, kanvas.height);
            ctx.drawImage(bmp, 0, 0, kanvas.width, kanvas.height);
            var blob = await new Promise(function (ok) { kanvas.toBlob(ok, "image/jpeg", 0.85); });
            if (!blob || (file.type === "image/jpeg" && skala === 1 && blob.size >= file.size)) return file;
            return new File([blob], file.name.replace(/\.[^.]+$/, "") + ".jpg", { type: "image/jpeg" });
        }

        document.addEventListener("change", async function (e) {
            var input = e.target;
            if (!input.matches || !input.matches('input[type="file"].js-preview')) return;
            var wadah = input.closest(".mb-3");
            var kotak = wadah.querySelector(".preview-box");
            var catatan = wadah.querySelector(".form-text");
            var asli = input.files && input.files[0];
            if (!asli) { if (kotak) kotak.hidden = true; return; }

            var file = asli;
            try {
                file = await kecilkan(asli);
            } catch (err) {
                file = asli; // format tidak bisa dibaca browser: biarkan server yang menilai
            }
            if (file !== asli) {
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            }

            if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                input.value = "";
                if (kotak) kotak.hidden = true;
                tampilToast("Only JPG, PNG, GIF, or WebP images are supported.", "err");
                return;
            }
            if (file.size > MAKS_BYTE) {
                input.value = "";
                if (kotak) kotak.hidden = true;
                tampilToast("This image is still larger than 2MB. Please choose a smaller one.", "err");
                return;
            }

            if (kotak) {
                kotak.querySelector("img").src = URL.createObjectURL(file);
                kotak.hidden = false;
            }
            if (catatan && file !== asli) {
                catatan.textContent = "Resized automatically: " + Math.round(asli.size / 1024) + " KB to " + Math.round(file.size / 1024) + " KB.";
            }
        });

        // modal ditutup: kosongkan pilihan file dan pratinjau supaya tidak tertinggal saat dibuka lagi
        document.addEventListener("hidden.bs.modal", function (e) {
            var form = e.target.querySelector("form");
            if (!form) return;
            form.reset();
            form.querySelectorAll(".preview-box").forEach(function (k) { k.hidden = true; });
        });

        // klik gambar kecil untuk melihat ukuran besar
        document.addEventListener("click", function (e) {
            var img = e.target.closest ? e.target.closest(".img-popup") : null;
            if (!img) return;
            document.getElementById("popup-gambar").setAttribute("src", img.getAttribute("data-src"));
            bootstrap.Modal.getOrCreateInstance(document.getElementById("modalDetail")).show();
        });
    </script>
</body>
</html>
