    <?php
    include "koneksi.php"
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Daily Journal of Jia</title>
        <link rel="icon" href="https://i.pinimg.com/736x/07/a7/11/07a7118298e6d8e6ee655a7046199ee9.jpg" />
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
    </head>
    <body>
        <!-- nav begin -->
        <nav class="navbar navbar-expand-sm bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><b><i>Jia's Daily Journal</i></b></a>
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
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
                <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#article">Article</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#gallery">Gallery</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#schedule">Schedule</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#aboutme">About Me</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="login.php" target="_blank">Login</a>
                </li>
                <button
                type="button"
                class="btn btn-dark theme"
                id="darkmode"
                title="dark"
                >
                <i class="bi bi-moon-stars-fill"></i>
                </button>
                <button
                type="button"
                class="btn btn-danger theme"
                id="lightmode"
                title="light"
                >
                <i class="bi bi-brightness-high"></i>
                </button>
            </ul>
            </div>
        </div>
        </nav>
        <!-- nav end -->
        <!-- hero begin -->
        <section id="hero" class="text-center p-5 bg-danger-subtle text-sm-start">
        <div class="container">
            <div class="d-sm-flex flex-sm-row-reverse align-items-center">
            <img src="https://i.pinimg.com/736x/10/53/58/10535851524c31e6efd7fbfa49753680.jpg" class="img-fluid" width="300">
            <div>
                <h1 class="fw-bold display-4">
                Choose Your Own Path, Create Memories, Make Them Stay
                </h1>
                <h4 class="lead display-6">
                Just wanna share my experiences, so you can read and enjoy it with happiness too!
                </h4>
                <h6>
                <span id="tanggal"></span>
                <span id="jam"></span>
                </h6>
            </div>
            </div>
        </div>
        </section>
        <!-- hero end -->
        <!-- article begin -->
        <section id="article" class="text-center p-5">
        <div class="container">
            <h1 class="fw-bold display-4 pb-3">Article</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php
            $sql = "SELECT * FROM article ORDER BY tanggal DESC";
            $hasil = $conn->query($sql); 

            while($row = $hasil->fetch_assoc()){
            ?>
                <div class="col">
                <div class="card h-100">
                    <img src="img/<?= $row["gambar"]?>" class="card-img-top" alt="..." />
                    <div class="card-body">
                    <h5 class="card-title"><?= $row["judul"]?></h5>
                    <p class="card-text">
                        <?= $row["isi"]?>
                    </p>
                    </div>
                    <div class="card-footer">
                    <small class="text-body-secondary">
                        <?= $row["tanggal"]?>
                    </small>
                    </div>
                </div>
                </div>
                <?php
            }
            ?> 
            </div>
        </div>
        </section>
        <!-- article end -->
        <!-- gallery begin -->
        <section id="gallery" class="bg-danger-subtle pt-1 pb-5">
        <div class="container">
            <div class="title text-center mb-4 mt-3">
            <h1 class="fw-bold display-4 pb-3">Gallery</h1>
            </div>
            <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
            <?php
            $sql = "SELECT * FROM gallery ORDER BY tanggal DESC";
            $hasil = $conn->query($sql);
            $isActive = true; 

            while($row = $hasil->fetch_assoc()){
                $activeClass = $isActive ? 'active' : '';
                $isActive = false;
            ?>
            <div class="carousel-item <?= $activeClass ?>">
            <img src="img/<?= $row["gambar"]?>" class="d-block w-100" alt="..." />
            </div>
            <?php
            }
            ?> 
            </div>
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#carouselExample"
                data-bs-slide="prev"
            >
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#carouselExample"
                data-bs-slide="next"
            >
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        </div>
        </section>
        <!-- gallery end -->
        <!-- schedule begin -->
        <section id="schedule" class="text-center p-5">
        <div class="container">
            <h1 class="fw-bold display-4 pb-3">Schedule</h1>
            <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">SENIN</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    Etika Profesi<br />16.20-18.00 | H.4.4
                    </li>
                    <li class="list-group-item">
                    Sistem Operasi<br />18.30-21.00 | H.4.8
                    </li>
                </ul>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">SELASA</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    Pendidikan Kewarganegaraan<br />12.30-13.10 | Kulino
                    </li>
                    <li class="list-group-item">
                    Probabilitas dan Statistik<br />15.30-18.00 | H.4.9
                    </li>
                    <li class="list-group-item">
                    Kecerdasan Buatan<br />18.30-21.00 | H.4.11
                    </li>
                </ul>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">RABU</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    Manajemen Proyek Teknologi Informasi<br />15.30-18.00 | H.4.6
                    </li>
                </ul>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">KAMIS</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    Bahasa Indonesia<br />12.30-14.10 | Kulino
                    </li>
                    <li class="list-group-item">
                    Pendidikan Agama Islam<br />16.20-18.00 | Kulino
                    </li>
                    <li class="list-group-item">
                    Penambangan Data<br />18.30-21.00 | H.4.9
                    </li>
                </ul>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">JUMAT</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                    Pemrograman Web Lanjut<br />10.20-12.00 | D.2.K
                    </li>
                </ul>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-header bg-danger text-white">SABTU</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">FREE</li>
                </ul>
                </div>
            </div>
            </div>
        </div>
        </section>
        <!-- schedule end -->
        <!-- about me begin -->
        <section id="aboutme" class="text-center p-5 bg-danger-subtle">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-center">
                <div class="p-3">
                    <img id="profile-image" src="https://i.pinimg.com/736x/2a/61/10/2a61107c7a6f62a79a03440a3c5f78aa.jpg" alt="Profile Photo" class="rounded-circle img-fluid mb-4" style="width: 200px; height: 200px; object-fit: cover; cursor: pointer;"
                    onclick="toggleText()">
                </div>
            <div id="keterangan-diri" class="p-md-5 text-sm-start">
                <h3 class="lead">A11.2023.15176</h3>
                <h1 class="fw-bold">Shazia Mirza</h1>
                <h5>Program Studi Teknik Informatika</h5>
                <a href="https://dinus.ac.id/" class="text-decoration-none text-dark fw-bold" target="_blank">Universitas Dian Nuswantoro</a>
            </div>
            </div>
        </div>
        </section>
        <!-- about me end -->
        <!-- footer begin -->
        <footer id="footer" class="text-center p-5">
            <div class="bi">
                <a href="http://surl.li/oxsat" target="_blank"><i class="bi bi-instagram h2 p-2 text-dark"></i></a>
                <a href="http://surl.li/pozmdl" target="_blank"><i class="bi bi-twitter-x h2 p-2 text-dark"></i></a>
                <a href="http://surl.li/jnbiwg" target="_blank"><i class="bi bi-youtube h2 p-2 text-dark"></i></a>
                <a href="http://surl.li/uyeykk" target="_blank"><i class="bi bi-tiktok h2 p-2 text-dark"></i></a>
                <a href="http://surl.li/oxskz" target="_blank"><i class="bi bi-whatsapp h2 p-2 text-dark"></i></a>
            </div>
            <br/>
            <div>
            Copyright &copy; 2024 - <span>Shazia Mirza.</span> All Right Reserved.
            </div>
        </footer>
        <!-- footer end -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        window.setTimeout("tampilWaktu()", 1000);

        function tampilWaktu() {
            var waktu = new Date();
            var bulan = waktu.getMonth() + 1;

            setTimeout("tampilWaktu()", 1000);
            document.getElementById("tanggal").innerHTML =
            waktu.getDate() + "/" + bulan + "/" + waktu.getFullYear();
            document.getElementById("jam").innerHTML =
            waktu.getHours() +
            ":" +
            waktu.getMinutes() +
            ":" +
            waktu.getSeconds();
        }
    </script>
    <script>
    const profileImage = document.getElementById("profile-image");
    const keterangan = document.getElementById("keterangan-diri");
    
    profileImage.addEventListener("click", function() {
        if (keterangan.style.display === "none") {
            keterangan.style.display = "block"; 
        } else {
            keterangan.style.display = "none"; 
        }
    }
    );
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
            document.getElementById("darkmode").onclick = function () {
        document.body.style.backgroundColor = "black";

        document.getElementById("hero").classList.replace("bg-danger-subtle", "bg-secondary");
        document.getElementById("hero").classList.replace("text-black", "text-white");

        document.getElementById("article").classList.replace("bg-body-tertiary", "bg-dark");
        document.getElementById("article").classList.remove("text-black");
        document.getElementById("article").classList.add("text-white");

        document.getElementById("gallery").classList.replace("bg-danger-subtle", "bg-secondary");
        document.getElementById("gallery").classList.replace("text-black", "text-white");

        document.getElementById("schedule").classList.replace("bg-body-tertiary", "bg-dark");
        document.getElementById("schedule").classList.remove("text-black");
        document.getElementById("schedule").classList.add("text-white");

        document.getElementById("aboutme").classList.replace("bg-danger-subtle", "bg-secondary");
        document.getElementById("aboutme").classList.replace("text-black", "text-white");

        document.getElementById("footer").classList.remove("text-black");
        document.getElementById("footer").classList.add("text-white");



        const cards = document.getElementsByClassName("card");
        for (let card of cards) {
        card.classList.add("bg-secondary", "text-white");
        }

        const listItems = document.getElementsByClassName("list-group-item");
        for (let item of listItems) {
        item.classList.add("bg-secondary", "text-white");
        }

        const icon = document.getElementsByClassName("bi");
        for (let i=0; i < icon.length; i++){
        icon[i].classList.remove("text-dark");
        icon[i].classList.add("text-white");
        }
    };

        document.getElementById("lightmode").onclick = function () {
        document.body.style.backgroundColor = "white";

        document.getElementById("hero").classList.replace("bg-secondary", "bg-danger-subtle");
        document.getElementById("hero").classList.replace("text-white", "text-black");

        document.getElementById("article").classList.replace("bg-black", "bg-body-tertiary");
        document.getElementById("article").classList.remove("text-white");
        document.getElementById("article").classList.add("text-black");

        document.getElementById("gallery").classList.replace("bg-secondary", "bg-danger-subtle");
        document.getElementById("gallery").classList.replace("text-white", "text-black");

        document.getElementById("aboutme").classList.replace("bg-secondary", "bg-danger-subtle");
        document.getElementById("aboutme").classList.replace("text-white", "text-black");

        document.getElementById("schedule").classList.replace("bg-black", "bg-body-tertiary");
        document.getElementById("schedule").classList.remove("text-white");
        document.getElementById("schedule").classList.add("text-black");

        document.getElementById("footer").classList.replace("text-white", "text-black");

        const cards = document.getElementsByClassName("card");
        for (let card of cards) {
        card.classList.remove("bg-secondary", "text-white");
        }

        const listItems = document.getElementsByClassName("list-group-item");
        for (let item of listItems) {
        item.classList.remove("bg-secondary", "text-white");
        }

        const icon = document.getElementsByClassName("bi");
        for (let i=0; i < icon.length; i++){
        icon[i].classList.remove("text-white");
        icon[i].classList.add("text-dark");
        }
    };
    </script>
    </body>
    </html>
