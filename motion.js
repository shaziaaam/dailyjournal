// Animasi halaman utama: reveal saat scroll, progres scroll, navbar, dan penanda menu aktif.
(function () {
    var kurangiGerak = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // elemen yang muncul saat di-scroll; delay bergantian antar saudara supaya terasa mengalir
    var target = [
        [".section-title", ""],
        [".article-card", ""],
        [".gallery-wrap", "reveal-scale"],
        [".day-card", ""],
        [".about-card", "reveal-scale"],
        [".site-footer", ""]
    ];

    var daftar = [];
    target.forEach(function (t) {
        document.querySelectorAll(t[0]).forEach(function (el, i) {
            el.classList.add("reveal");
            if (t[1]) el.classList.add(t[1]);
            el.style.setProperty("--d", (i % 3) * 120 + "ms");
            daftar.push(el);
        });
    });

    if ("IntersectionObserver" in window && !kurangiGerak) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add("in");
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.15, rootMargin: "0px 0px -40px 0px" });
        daftar.forEach(function (el) { io.observe(el); });
    } else {
        daftar.forEach(function (el) { el.classList.add("in"); });
    }

    // bar progres scroll + bayangan navbar
    var bar = document.createElement("div");
    bar.className = "scroll-progress";
    document.body.appendChild(bar);
    var nav = document.querySelector(".site-nav");
    var menunggu = false;

    function saatScroll() {
        var tinggi = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.transform = "scaleX(" + (tinggi > 0 ? window.scrollY / tinggi : 0) + ")";
        if (nav) nav.classList.toggle("scrolled", window.scrollY > 10);
        menunggu = false;
    }

    window.addEventListener("scroll", function () {
        if (!menunggu) {
            menunggu = true;
            requestAnimationFrame(saatScroll);
        }
    }, { passive: true });
    saatScroll();

    // tandai menu navbar sesuai section yang sedang dilihat
    var links = document.querySelectorAll('.site-nav .nav-link[href^="#"]');
    var sections = [];
    links.forEach(function (a) {
        var s = a.getAttribute("href") === "#" ? document.getElementById("hero") : document.querySelector(a.getAttribute("href"));
        if (s) sections.push({ link: a, el: s });
    });

    if ("IntersectionObserver" in window) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                sections.forEach(function (s) {
                    s.link.classList.toggle("active", s.el === e.target);
                });
            });
        }, { rootMargin: "-45% 0px -50% 0px" });
        sections.forEach(function (s) { spy.observe(s.el); });
    }

    // klik menu navbar: scroll halus dengan easing, lalu section tujuan "menyambut" dengan animasi singkat
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    var sedangScroll = false;

    function scrollKe(el, hash) {
        var navTinggi = nav ? nav.offsetHeight : 0;
        var tujuan = el.id === "hero" ? 0 : el.getBoundingClientRect().top + window.scrollY - navTinggi + 1;
        tujuan = Math.max(0, Math.min(tujuan, document.documentElement.scrollHeight - window.innerHeight));
        var awal = window.scrollY;
        var jarak = tujuan - awal;

        function selesai() {
            sedangScroll = false;
            document.documentElement.style.scrollBehavior = "";
            if (history.replaceState) history.replaceState(null, "", hash === "#" ? location.pathname + location.search : hash);
            el.classList.remove("arrive");
            void el.offsetWidth; // paksa reflow supaya animasi bisa diputar ulang
            el.classList.add("arrive");
            setTimeout(function () { el.classList.remove("arrive"); }, 1400);
        }

        if (kurangiGerak || Math.abs(jarak) < 2) {
            window.scrollTo(0, tujuan);
            selesai();
            return;
        }

        sedangScroll = true;
        document.documentElement.style.scrollBehavior = "auto"; // matikan smooth bawaan CSS agar tidak bentrok
        var durasi = Math.min(1100, 450 + Math.abs(jarak) * 0.35);
        var mulai = null;

        function langkah(waktu) {
            if (mulai === null) mulai = waktu;
            var p = Math.min((waktu - mulai) / durasi, 1);
            window.scrollTo(0, awal + jarak * easeInOutCubic(p));
            if (p < 1) {
                requestAnimationFrame(langkah);
            } else {
                selesai();
            }
        }
        requestAnimationFrame(langkah);
    }

    document.querySelectorAll('.site-nav a[href^="#"], .hero a[href^="#"], .navbar-brand[href^="#"]').forEach(function (a) {
        a.addEventListener("click", function (e) {
            var hash = a.getAttribute("href");
            var el = hash === "#" ? document.getElementById("hero") : document.querySelector(hash);
            if (!el) return;
            e.preventDefault();
            if (sedangScroll) return;

            // tutup menu mobile jika sedang terbuka
            var menu = document.getElementById("navbarSupportedContent");
            if (menu && menu.classList.contains("show") && window.bootstrap) {
                window.bootstrap.Collapse.getOrCreateInstance(menu).hide();
            }

            // tandai menu aktif langsung, tidak menunggu scroll selesai
            sections.forEach(function (s) {
                s.link.classList.toggle("active", s.el === el);
            });
            scrollKe(el, hash);
        });
    });
})();
