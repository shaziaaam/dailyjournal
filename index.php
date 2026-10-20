<?php
include "koneksi.php";

// tampilkan tanggal dalam format Indonesia, misal 12 Jan 2025
function tgl($waktu) {
    $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $t = strtotime($waktu);
    return date('j', $t) . ' ' . $bulan[date('n', $t) - 1] . ' ' . date('Y', $t);
}

$articles = $conn->query("SELECT * FROM article ORDER BY tanggal DESC")->fetchAll();
$galleries = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC")->fetchAll();

$jadwal = [
    'senin' => ['SENIN', [
        ['Etika Profesi', '16.20-18.00 | H.4.4'],
        ['Sistem Operasi', '18.30-21.00 | H.4.8'],
    ]],
    'selasa' => ['SELASA', [
        ['Pendidikan Kewarganegaraan', '12.30-13.10 | Kulino'],
        ['Probabilitas dan Statistik', '15.30-18.00 | H.4.9'],
        ['Kecerdasan Buatan', '18.30-21.00 | H.4.11'],
    ]],
    'rabu' => ['RABU', [
        ['Manajemen Proyek Teknologi Informasi', '15.30-18.00 | H.4.6'],
    ]],
    'kamis' => ['KAMIS', [
        ['Bahasa Indonesia', '12.30-14.10 | Kulino'],
        ['Pendidikan Agama Islam', '16.20-18.00 | Kulino'],
        ['Penambangan Data', '18.30-21.00 | H.4.9'],
    ]],
    'jumat' => ['JUMAT', [
        ['Pemrograman Web Lanjut', '10.20-12.00 | D.2.K'],
    ]],
    'sabtu' => ['SABTU', []],
];
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daily Journal of Jia</title>
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
</head>
<body>
    <!-- nav begin -->
    <nav class="navbar navbar-expand-md site-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Jia's Daily Journal</a>
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
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#article">Article</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#schedule">Schedule</a></li>
                    <li class="nav-item"><a class="nav-link" href="#aboutme">About Me</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <li class="nav-item ms-md-2 mt-2 mt-md-0">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Ganti tema" aria-label="Ganti tema">
                            <i class="bi bi-moon-stars-fill icon-moon"></i>
                            <i class="bi bi-brightness-high-fill icon-sun"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- nav end -->

    <!-- hero begin -->
    <header id="hero" class="hero">
        <div class="container">
            <div class="row align-items-center g-5 flex-md-row-reverse">
                <div class="col-md-5 text-center">
                    <img src="https://i.pinimg.com/736x/10/53/58/10535851524c31e6efd7fbfa49753680.jpg" class="hero-photo" alt="Jia" />
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <span class="eyebrow"><i class="bi bi-journal-bookmark-fill"></i> My Daily Journal</span>
                    <h1 class="mt-3">Choose your own path, <em>create memories,</em> make them stay.</h1>
                    <p class="lead mt-3">
                        Just wanna share my experiences, so you can read and enjoy it with happiness too!
                    </p>
                    <p class="clock mb-4"><i class="bi bi-clock"></i> <span id="tanggal"></span> &middot; <span id="jam"></span></p>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start flex-wrap">
                        <a href="#article" class="btn btn-accent">Baca Article</a>
                        <a href="#gallery" class="btn btn-ghost">Lihat Gallery</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- hero end -->

    <!-- article begin -->
    <section id="article" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Article</h2>
                <p>Cerita dan catatan perjalanan terbaru</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (!$articles) { ?>
                    <p class="text-center text-secondary">Belum ada article.</p>
                <?php } ?>
                <?php foreach ($articles as $row) { ?>
                    <div class="col">
                        <article class="article-card">
                            <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="<?= htmlspecialchars($row["judul"]) ?>" loading="lazy" />
                            <div class="body">
                                <h3 class="mb-2"><?= htmlspecialchars($row["judul"]) ?></h3>
                                <p><?= htmlspecialchars($row["isi"]) ?></p>
                            </div>
                            <div class="foot">
                                <span><i class="bi bi-calendar3"></i> <?= tgl($row["tanggal"]) ?></span>
                                <button type="button" class="read-more"
                                    data-bs-toggle="modal" data-bs-target="#artikelModal"
                                    data-judul="<?= htmlspecialchars($row["judul"]) ?>"
                                    data-isi="<?= htmlspecialchars($row["isi"]) ?>"
                                    data-tanggal="<?= tgl($row["tanggal"]) ?>"
                                    data-gambar="img/<?= htmlspecialchars($row["gambar"]) ?>">
                                    Baca selengkapnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </article>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- modal baca article -->
    <div class="modal fade" id="artikelModal" tabindex="-1" aria-labelledby="artikelJudul" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content overflow-hidden">
                <img id="artikelGambar" src="" alt="" class="w-100" style="max-height: 320px; object-fit: cover;" />
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h3 class="modal-title fs-3" id="artikelJudul"></h3>
                        <small class="text-secondary" id="artikelTanggal"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-2" id="artikelIsi"></div>
            </div>
        </div>
    </div>
    <!-- article end -->

    <!-- gallery begin -->
    <section id="gallery" class="section section-tint">
        <div class="container">
            <div class="section-title">
                <h2>Gallery</h2>
                <p>Potret momen yang ingin selalu diingat</p>
            </div>
            <?php if (!$galleries) { ?>
                <p class="text-center text-secondary">Belum ada foto.</p>
            <?php } else { ?>
            <div id="carouselExample" class="carousel slide carousel-fade gallery-wrap" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($galleries as $i => $row) { ?>
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?= $i ?>"
                            class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Foto <?= $i + 1 ?>"></button>
                    <?php } ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($galleries as $i => $row) { ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" class="d-block w-100" alt="Foto gallery <?= $i + 1 ?>" />
                        </div>
                    <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <?php } ?>
        </div>
    </section>
    <!-- gallery end -->

    <!-- schedule begin -->
    <section id="schedule" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Schedule</h2>
                <p>Jadwal kuliah mingguan</p>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                <?php foreach ($jadwal as $kunci => [$hari, $matkul]) { ?>
                    <div class="col">
                        <div class="day-card" data-hari="<?= $kunci ?>">
                            <div class="day"><?= $hari ?></div>
                            <ul>
                                <?php if (!$matkul) { ?>
                                    <li>FREE</li>
                                <?php } ?>
                                <?php foreach ($matkul as [$nama, $waktu]) { ?>
                                    <li><?= $nama ?><small><?= $waktu ?></small></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- schedule end -->

    <!-- about me begin -->
    <section id="aboutme" class="section section-tint">
        <div class="container">
            <div class="about-card mx-auto" style="max-width: 760px;">
                <div class="d-sm-flex align-items-center justify-content-center gap-4 text-center text-sm-start">
                    <img id="profile-image" src="img/shazia.jpg" alt="Profile Photo" class="rounded-circle mb-3 mb-sm-0 flex-shrink-0" title="Klik untuk menyembunyikan keterangan" />
                    <div id="keterangan-diri">
                        <div class="nim">A11.2023.15176</div>
                        <h2 class="fw-bold mb-1">Shazia Mirza</h2>
                        <p class="mb-1">Program Studi Teknik Informatika</p>
                        <a href="https://dinus.ac.id/" target="_blank">Universitas Dian Nuswantoro <i class="bi bi-box-arrow-up-right small"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about me end -->

    <!-- footer begin -->
    <footer id="footer" class="site-footer text-center py-5">
        <div class="social mb-3">
            <a href="https://www.instagram.com/shaziaaam" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.youtube.com/@ziaaaasss" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            <a href="https://www.tiktok.com/@shajiaaas" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
            <a href="https://wa.me/628812406778" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
        </div>
        <div>
            Copyright &copy; 2024 - <span>Shazia Mirza.</span> All Right Reserved.
        </div>
    </footer>
    <!-- footer end -->

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
    ></script>
    <script>
        // tanggal dan jam di hero
        function tampilWaktu() {
            var waktu = new Date();
            document.getElementById("tanggal").textContent = waktu.toLocaleDateString("id-ID", {
                weekday: "long", day: "numeric", month: "long", year: "numeric"
            });
            document.getElementById("jam").textContent = waktu.toLocaleTimeString("id-ID");
        }
        tampilWaktu();
        setInterval(tampilWaktu, 1000);

        // tandai jadwal hari ini
        var namaHari = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];
        var hariIni = document.querySelector('.day-card[data-hari="' + namaHari[new Date().getDay()] + '"]');
        if (hariIni) hariIni.classList.add("today");

        // isi modal article
        document.getElementById("artikelModal").addEventListener("show.bs.modal", function (e) {
            var d = e.relatedTarget.dataset;
            document.getElementById("artikelJudul").textContent = d.judul;
            document.getElementById("artikelTanggal").textContent = d.tanggal;
            document.getElementById("artikelIsi").textContent = d.isi;
            var gambar = document.getElementById("artikelGambar");
            gambar.src = d.gambar;
            gambar.alt = d.judul;
        });

        // klik foto profil untuk menyembunyikan/menampilkan keterangan
        var keterangan = document.getElementById("keterangan-diri");
        document.getElementById("profile-image").addEventListener("click", function () {
            keterangan.style.display = keterangan.style.display === "none" ? "" : "none";
        });
    </script>
</body>
</html>
