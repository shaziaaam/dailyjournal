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
})();
