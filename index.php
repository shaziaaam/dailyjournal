<?php
include "koneksi.php";

// tampilkan tanggal dalam format Inggris, misal Jan 12, 2025
function tgl($waktu) {
    return date('M j, Y', strtotime($waktu));
}

// hari pertama perjalanan; artikel yang tanggalnya jatuh dalam rentang perjalanan diberi label "Day N"
$mulai_trip = '2025-12-24';
$lama_trip = 6;

// contoh: "Day 3 · Wed, Jan 15" (atau "Day 3 · Wednesday, Jan 15, 2025" jika $lengkap)
function label_hari($waktu, $lengkap = false) {
    global $mulai_trip, $lama_trip;
    $a = new DateTime($mulai_trip);
    $b = new DateTime(date('Y-m-d', strtotime($waktu)));
    $n = (int)$a->diff($b)->format('%r%a') + 1;
    $tanggal = $lengkap ? date('l, M j, Y', strtotime($waktu)) : date('D, M j', strtotime($waktu));
    if ($n < 1 || $n > $lama_trip) {
        return tgl($waktu);
    }
    return "Day $n &middot; $tanggal";
}

$articles = $conn->query("SELECT * FROM article ORDER BY tanggal ASC")->fetchAll();
$galleries = $conn->query("SELECT * FROM gallery ORDER BY tanggal ASC")->fetchAll();

// jadwal perjalanan saya: disusun otomatis dari tanggal artikel, jadi selalu sama dengan diary
// kunci = nama file gambar artikel, nilai = nama tempat yang ditampilkan
$nama_tempat = [
    'westminster-abbey.jpg' => 'Westminster Abbey',
    'big-ben.jpg' => 'Big Ben',
    'london-eye.jpg' => 'London Eye',
    'kensington-palace.jpg' => 'Kensington Palace',
    'hyde-park.jpg' => 'Hyde Park',
    'baker-street.jpg' => '221B Baker Street',
    'buckingham-palace.jpg' => 'Buckingham Palace',
    'fortnum-and-mason.jpg' => 'Afternoon tea, Fortnum & Mason',
    'trafalgar-square.jpg' => 'Trafalgar Square',
    'monmouth-coffee.jpg' => 'Monmouth Coffee',
    'neals-yard.jpg' => "Neal's Yard",
    'covent-garden.jpg' => 'Covent Garden',
    'piccadilly-circus.jpg' => 'Piccadilly Circus',
    'british-museum.jpg' => 'British Museum',
    'ye-olde-cheshire-cheese.jpg' => 'Ye Olde Cheshire Cheese',
    'st-pauls-cathedral.jpg' => "St Paul's Cathedral",
    'millennium-bridge.jpg' => 'Millennium Bridge',
    'tate-modern.jpg' => 'Tate Modern',
    'borough-market.jpg' => 'Borough Market',
    'camden-market.jpg' => 'Camden Market',
    'tower-of-london.jpg' => 'Tower of London',
    'tower-bridge.jpg' => 'Tower Bridge',
    'pie-and-mash.jpg' => 'Pie and mash lunch',
    'leadenhall-market.jpg' => 'Leadenhall Market',
];
$jadwal_saya = [];
foreach ($articles as $row) {
    if (!isset($nama_tempat[$row['gambar']])) {
        continue;
    }
    $hari_ke = (int)(new DateTime($mulai_trip))->diff(new DateTime(date('Y-m-d', strtotime($row['tanggal']))))->format('%r%a') + 1;
    if ($hari_ke < 1 || $hari_ke > $lama_trip) {
        continue;
    }
    $jadwal_saya[$hari_ke][] = [date('H.i', strtotime($row['tanggal'])), $nama_tempat[$row['gambar']]];
}
ksort($jadwal_saya);

// rekomendasi itinerary untuk pembaca, diurutkan dari area yang paling wajib dikunjungi.
// Pembaca mengisi jumlah hari, lalu area ke-1 sampai ke-N yang ditampilkan.
// format: [area, [[tempat, jam, catatan, jalan dari tempat sebelumnya], ...]]
// jam dan lama jalan kaki hanya perkiraan; cek jam buka resmi sebelum berangkat
$itinerary = [
    ['Royal London and Westminster', [
        ['Buckingham Palace', '10.15', 'Changing of the Guard is at 11.00, usually Mon, Wed, Fri. Check the official calendar.', null],
        ['Westminster Abbey', '12.30', 'Opens 09.30 Mon-Sat, closed to visitors on Sundays.', "about 20 min walk through St James's Park"],
        ['Big Ben', '14.30', 'Best photo from Westminster Bridge.', 'about 3 min walk'],
        ['London Eye', '16.00', 'Book a slot before sunset for golden light.', 'about 10 min walk'],
    ]],
    ['West End', [
        ['Monmouth Coffee', '09.00', '27 Monmouth Street. Open from 08.00 Mon-Sat, closed Sundays.', null],
        ["Neal's Yard", '10.00', 'Colorful courtyard, best photos before the crowds.', 'about 3 min walk'],
        ['Covent Garden', '11.00', 'Street performers are best in the afternoon.', 'about 5 min walk'],
        ['Trafalgar Square', '14.00', 'Lions, fountains, and the National Gallery.', 'about 12 min walk'],
        ['Afternoon tea, Fortnum & Mason', '15.30', 'Served from late morning to evening. Book ahead, from around 80 GBP per person.', 'about 12 min walk'],
        ['Piccadilly Circus', '17.30', 'The lights are best once the sky turns dark.', 'about 5 min walk'],
    ]],
    ['The Thames and the City', [
        ["St Paul's Cathedral", '10.00', 'Sightseeing opens around 10.00, Mon-Sat. Climb the dome while your legs are fresh.', null],
        ['Millennium Bridge', '11.45', 'Stop halfway for the river view.', 'about 5 min walk'],
        ['Tate Modern', '12.00', 'Free to enter, open until 18.00. Head to the top floor for the view.', 'about 5 min walk'],
        ['Borough Market', '13.30', 'Closed on Mondays, closes around 17.00. Come hungry.', 'about 15 min walk'],
        ['Leadenhall Market', '16.00', 'A calm stop under the glass roof. Best on weekdays, many shops close at weekends.', 'about 20 min walk'],
    ]],
    ['Tower and the East', [
        ['Tower of London', '09.30', 'Opens 09.00 Tue-Sat, 10.00 Sun-Mon. Go at opening to skip the queues.', null],
        ['Tower Bridge', '11.30', 'Walk across, then look back at the Tower.', 'about 5 min walk'],
        ['M. Manze (pie and mash)', '13.00', '87 Tower Bridge Road. Open Mon-Sat from about 10.30, Sun 11.00-14.45. Expect a queue.', 'about 10 min walk'],
    ]],
    ['Bloomsbury and Fleet Street', [
        ['British Museum', '10.00', 'Plan at least three hours. Late opening on Fridays until 20.30.', null],
        ['Ye Olde Cheshire Cheese', '18.00', '145 Fleet Street. Open until about 23.00, shorter hours on Sundays.', 'about 25 min walk or a short ride'],
    ]],
    ['Kensington and Marylebone', [
        ['Kensington Palace', '10.30', 'Quiet gardens, nice in soft morning light.', null],
        ['Hyde Park', '13.00', 'Slow walk by the Serpentine.', 'about 15 min walk through Kensington Gardens'],
        ['221B Baker Street', '15.30', 'Opens 09.30 daily, last entry about 17.30. Queues grow by noon.', 'about 30 min walk or a short ride'],
    ]],
    ['North London', [
        ['Camden Market', '11.00', 'Main market opens at 10.00, food halls from 11.30. Busiest on weekends. Leave the afternoon free.', null],
    ]],
];

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daily Journal of Jia</title>
    <meta name="description" content="Jia's Daily Journal: travel stories, photo gallery, and a weekly London itinerary." />
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
    <nav class="navbar navbar-expand-lg site-nav sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Jia's Daily Journal</a>
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <button type="button" class="theme-toggle" title="Toggle theme" aria-label="Toggle theme">
                    <i class="bi bi-moon-stars-fill icon-moon"></i>
                    <i class="bi bi-brightness-high-fill icon-sun"></i>
                </button>
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
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#article">Article</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#itinerary">Itinerary</a></li>
                    <li class="nav-item"><a class="nav-link" href="#aboutme">About Me</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <li class="nav-item nav-clock d-none d-lg-block" aria-hidden="true">
                        <div class="clock">
                            <span class="clock-time"><span id="jam">--</span><span class="clock-colon">:</span><span id="menit">--</span> <span id="ampm"></span></span>
                            <span class="clock-date" id="tanggal"></span>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-lg-2">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Toggle theme" aria-label="Toggle theme">
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
                    <p class="lead mt-3 mb-4">
                        Just wanna share my experiences, so you can read and enjoy it with happiness too!
                    </p>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start flex-wrap">
                        <a href="#article" class="btn btn-accent">Read Articles</a>
                        <a href="#gallery" class="btn btn-ghost">View Gallery</a>
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
                <p>Stories and travel notes, fresh off the road</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="articleList">
                <?php if (!$articles) { ?>
                    <p class="text-center text-secondary">No articles yet.</p>
                <?php } ?>
                <?php foreach ($articles as $row) { ?>
                    <div class="col">
                        <article class="article-card">
                            <div class="thumb">
                                <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="<?= htmlspecialchars($row["judul"]) ?>" loading="lazy" />
                            </div>
                            <div class="body">
                                <h3 class="mb-2"><?= htmlspecialchars($row["judul"]) ?></h3>
                                <p><?= htmlspecialchars($row["isi"]) ?></p>
                            </div>
                            <div class="foot">
                                <span><i class="bi bi-calendar3"></i> <?= label_hari($row["tanggal"]) ?></span>
                                <button type="button" class="read-more"
                                    data-bs-toggle="modal" data-bs-target="#artikelModal"
                                    data-judul="<?= htmlspecialchars($row["judul"]) ?>"
                                    data-isi="<?= htmlspecialchars($row["isi"]) ?>"
                                    data-tanggal="<?= html_entity_decode(label_hari($row["tanggal"], true)) ?>"
                                    data-gambar="img/<?= htmlspecialchars($row["gambar"]) ?>">
                                    Read more <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </article>
                    </div>
                <?php } ?>
            </div>
            <nav class="mt-4" id="articlePager" aria-label="Article pages" hidden>
                <ul class="pagination justify-content-center mb-0"></ul>
            </nav>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <p>Moments worth remembering</p>
            </div>
            <?php if (!$galleries) { ?>
                <p class="text-center text-secondary">No photos yet.</p>
            <?php } else { ?>
            <div id="carouselExample" class="carousel slide carousel-fade gallery-wrap" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($galleries as $i => $row) { ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" class="d-block w-100" alt="Gallery photo <?= $i + 1 ?>" />
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
            <div class="gallery-thumbs" id="galleryThumbs">
                <?php foreach ($galleries as $i => $row) { ?>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Photo <?= $i + 1 ?>"
                        <?= $i === 0 ? 'aria-current="true"' : '' ?>>
                        <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="" loading="lazy" />
                    </button>
                <?php } ?>
            </div>
            <script>
                // thumbnail aktif mengikuti slide, dan otomatis digulir ke area yang terlihat
                (function () {
                    var carousel = document.getElementById("carouselExample");
                    var strip = document.getElementById("galleryThumbs");
                    if (!carousel || !strip) return;
                    var thumbs = strip.querySelectorAll("button");
                    carousel.addEventListener("slide.bs.carousel", function (e) {
                        thumbs.forEach(function (t, i) {
                            var aktif = i === e.to;
                            t.classList.toggle("active", aktif);
                            if (aktif) {
                                t.setAttribute("aria-current", "true");
                                strip.scrollTo({
                                    left: t.offsetLeft - (strip.clientWidth - t.offsetWidth) / 2,
                                    behavior: "smooth"
                                });
                            } else {
                                t.removeAttribute("aria-current");
                            }
                        });
                    });
                })();
            </script>
            <?php } ?>
        </div>
    </section>
    <!-- gallery end -->

    <!-- itinerary begin -->
    <section id="itinerary" class="section">
        <div class="container">
            <div class="section-title">
                <h2>Itinerary</h2>
                <p>Where I went in London, and a plan for your own trip</p>
            </div>

            <!-- jadwal saya -->
            <div class="itin-sub">
                <h3>My schedule</h3>
                <p><?= (int)$lama_trip ?> days in London, day by day</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4" id="mySchedule">
                <?php foreach ($jadwal_saya as $n => $tempat_hari) {
                    $tgl_hari = strtotime("$mulai_trip +" . ($n - 1) . " days"); ?>
                    <div class="col">
                        <div class="day-card">
                            <div class="day"><span>DAY <?= $n ?></span><span class="area"><?= date('D, M j', $tgl_hari) ?></span></div>
                            <ul>
                                <?php foreach ($tempat_hari as [$jam, $nama]) { ?>
                                    <li>
                                        <strong><?= htmlspecialchars($nama) ?></strong>
                                        <small><i class="bi bi-clock"></i> <?= $jam ?></small>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- rencana untuk pembaca -->
            <div class="itin-sub">
                <h3>Plan your own trip</h3>
                <p>Tell us how many days you have in London, and we will suggest where to go</p>
            </div>

            <div class="planner">
                <label for="hariInput">How many days will you be in London?</label>
                <div class="stepper">
                    <button type="button" id="hariKurang" aria-label="Fewer days"><i class="bi bi-dash-lg"></i></button>
                    <input type="number" id="hariInput" min="1" max="<?= count($itinerary) ?>" value="5" inputmode="numeric" />
                    <button type="button" id="hariTambah" aria-label="More days"><i class="bi bi-plus-lg"></i></button>
                </div>
                <span class="plan-sum" id="planSum" aria-live="polite"></span>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center" id="planList">
                <?php foreach ($itinerary as $i => [$area, $tempat]) { ?>
                    <div class="col plan-col" data-count="<?= count($tempat) ?>">
                        <div class="day-card">
                            <div class="day plan-head"><span class="num"><?= $i + 1 ?></span><span class="area-name"><?= htmlspecialchars($area) ?></span></div>
                            <ul>
                                <?php foreach ($tempat as [$nama, $jam, $catatan, $jalan]) { ?>
                                    <li>
                                        <?php if ($jalan) { ?>
                                            <span class="walk"><i class="bi bi-person-walking"></i> <?= htmlspecialchars($jalan) ?></span>
                                        <?php } ?>
                                        <strong><?= htmlspecialchars($nama) ?></strong>
                                        <small><i class="bi bi-clock"></i> <?= htmlspecialchars($jam) ?> &middot; <?= htmlspecialchars($catatan) ?></small>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <p class="plan-skip" id="planSkip" hidden></p>
            <p class="text-center text-secondary small mt-4 mb-0">Plan one area per day. Times and walking distances are estimates, so confirm opening hours on each official website before you go.</p>
        </div>

        <script>
            // jumlah hari diisi pembaca: tampilkan area ke-1 sampai ke-N, sisanya disembunyikan
            (function () {
                var input = document.getElementById("hariInput");
                var kurang = document.getElementById("hariKurang");
                var tambah = document.getElementById("hariTambah");
                var ringkas = document.getElementById("planSum");
                var lewat = document.getElementById("planSkip");
                var kartu = document.querySelectorAll("#planList .plan-col");
                var maks = kartu.length;
                var sebelumnya = null;

                function tampil(n) {
                    var tempat = 0;
                    var dilewati = [];
                    kartu.forEach(function (el, i) {
                        var ikut = i < n;
                        var barang = el.hidden;
                        el.hidden = !ikut;
                        if (ikut) {
                            tempat += parseInt(el.getAttribute("data-count"), 10);
                            // animasi hanya untuk kartu yang baru muncul
                            if (barang && sebelumnya !== null) {
                                el.classList.remove("plan-show");
                                void el.offsetWidth;
                                el.classList.add("plan-show");
                            }
                        } else {
                            dilewati.push(el.querySelector(".area-name").textContent);
                        }
                    });
                    ringkas.textContent = n + (n === 1 ? " day" : " days") + " · " + tempat + " places";
                    if (dilewati.length) {
                        lewat.hidden = false;
                        lewat.textContent = "Not included with " + n + (n === 1 ? " day" : " days") + ": " + dilewati.join(", ") + ".";
                    } else {
                        lewat.hidden = true;
                    }
                    sebelumnya = n;
                }

                function atur(nilai) {
                    var n = parseInt(nilai, 10);
                    if (isNaN(n)) return;
                    n = Math.max(1, Math.min(maks, n));
                    input.value = n;
                    tampil(n);
                }

                input.addEventListener("input", function () { if (input.value !== "") atur(input.value); });
                input.addEventListener("blur", function () { atur(input.value === "" ? (sebelumnya || 5) : input.value); });
                kurang.addEventListener("click", function () { atur((parseInt(input.value, 10) || 1) - 1); });
                tambah.addEventListener("click", function () { atur((parseInt(input.value, 10) || 0) + 1); });
                atur(input.value);
            })();
        </script>
    </section>
    <!-- itinerary end -->

    <!-- about me begin -->
    <section id="aboutme" class="section section-tint">
        <div class="container">
            <div class="about-card mx-auto" style="max-width: 760px;">
                <div class="d-sm-flex align-items-center justify-content-center gap-4 text-center text-sm-start">
                    <img id="profile-image" src="img/profile.jpg" alt="Profile Photo" class="rounded-circle mb-3 mb-sm-0 flex-shrink-0" title="Click to show or hide details" />
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

    <!-- modal kredit foto -->
    <?php $kredit = require __DIR__ . "/photo_credits.php"; ?>
    <div class="modal fade" id="kreditModal" tabindex="-1" aria-labelledby="kreditJudul" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="kreditJudul">Photo credits</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">Photos of London landmarks below are from Wikimedia Commons, resized and in some cases cropped. All other photos are my own.</p>
                    <ul class="credits-list">
                        <?php foreach ($kredit as [$berkas, $tempat, $penulis, $lisensi, $url_lisensi, $sumber]) { ?>
                            <li>
                                <strong><?= htmlspecialchars($tempat) ?></strong>
                                <span>
                                    <a href="<?= htmlspecialchars($sumber) ?>" target="_blank" rel="noopener">Photo</a>
                                    by <?= htmlspecialchars($penulis) ?>,
                                    <a href="<?= htmlspecialchars($url_lisensi) ?>" target="_blank" rel="noopener license"><?= htmlspecialchars($lisensi) ?></a>
                                </span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- footer begin -->
    <footer id="footer" class="site-footer text-center py-5">
        <div class="social mb-3">
            <a href="https://www.instagram.com/shaziaaam" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.linkedin.com/in/shaziaaam/" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="https://wa.me/628812406778" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a href="mailto:shaziaaaam@gmail.com" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
        </div>
        <div>
            Copyright &copy; 2025 - <span>Shazia Mirza.</span> All Right Reserved.
        </div>
        <div class="mt-2">
            <button type="button" class="credits-link" data-bs-toggle="modal" data-bs-target="#kreditModal">Photo credits</button>
        </div>
    </footer>
    <!-- footer end -->

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"
    ></script>
    <script src="motion.js"></script>
    <script>
        // tanggal dan jam di navbar
        function tampilWaktu() {
            var waktu = new Date();
            var jam = waktu.getHours();
            document.getElementById("jam").textContent = jam % 12 || 12;
            document.getElementById("menit").textContent = String(waktu.getMinutes()).padStart(2, "0");
            document.getElementById("ampm").textContent = jam < 12 ? "AM" : "PM";
            document.getElementById("tanggal").textContent = waktu.toLocaleDateString("en-US", {
                weekday: "short", month: "short", day: "numeric"
            });
        }
        tampilWaktu();
        setInterval(tampilWaktu, 1000);

        // pagination article: 6 artikel per halaman
        (function () {
            var perHalaman = 6;
            var kartu = document.querySelectorAll("#articleList > .col");
            var nav = document.getElementById("articlePager");
            var daftar = nav.querySelector("ul");
            var jumlah = Math.ceil(kartu.length / perHalaman);
            if (jumlah < 2) return;
            nav.hidden = false;

            function tampil(hlm, gulir) {
                kartu.forEach(function (el, i) {
                    el.hidden = Math.floor(i / perHalaman) + 1 !== hlm;
                });
                daftar.innerHTML = "";
                function item(isi, tujuan, aktif, mati, label) {
                    var li = document.createElement("li");
                    li.className = "page-item" + (aktif ? " active" : "") + (mati ? " disabled" : "");
                    var a = document.createElement("a");
                    a.className = "page-link";
                    a.href = "#article";
                    a.innerHTML = isi;
                    if (label) a.setAttribute("aria-label", label);
                    if (!mati) a.addEventListener("click", function (e) { e.preventDefault(); tampil(tujuan, true); });
                    li.appendChild(a);
                    daftar.appendChild(li);
                }
                item('<i class="bi bi-chevron-left"></i>', hlm - 1, false, hlm === 1, "Previous");
                for (var i = 1; i <= jumlah; i++) item(i, i, i === hlm, false);
                item('<i class="bi bi-chevron-right"></i>', hlm + 1, false, hlm === jumlah, "Next");
                if (gulir) document.getElementById("article").scrollIntoView({ behavior: "smooth" });
            }
            tampil(1, false);
        })();

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
