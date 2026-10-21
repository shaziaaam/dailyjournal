// Animasi halaman utama: reveal saat scroll, navbar, dan penanda menu aktif.
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

    // bayangan navbar saat di-scroll
    var nav = document.querySelector(".site-nav");
    var menunggu = false;

    function saatScroll() {
        if (nav) nav.classList.toggle("scrolled", window.scrollY > 10);
        menunggu = false;
    }

    // menu yang baru diklik dikunci sebagai aktif sampai scroll berhenti,
    // supaya garis tidak berkedip di menu-menu yang dilewati
    var kunci = null;
    var lepasKunci = 0;

    function tundaLepas() {
        clearTimeout(lepasKunci);
        lepasKunci = setTimeout(function () { kunci = null; }, 150);
    }

    window.addEventListener("scroll", function () {
        if (kunci) tundaLepas();
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

    function tandaiAktif(link) {
        sections.forEach(function (s) {
            s.link.classList.toggle("active", s.link === link);
        });
    }

    links.forEach(function (a) {
        a.addEventListener("click", function () {
            kunci = a;
            tandaiAktif(a);
            tundaLepas(); // jaga-jaga kalau halaman tidak perlu scroll sama sekali
        });
    });

    if ("IntersectionObserver" in window) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting || kunci) return;
                sections.forEach(function (s) {
                    s.link.classList.toggle("active", s.el === e.target);
                });
            });
        }, { rootMargin: "-45% 0px -50% 0px" });
        sections.forEach(function (s) { spy.observe(s.el); });
    }
})();
