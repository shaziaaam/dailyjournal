// Dimuat di <head> supaya tema terpasang sebelum halaman tampil (tanpa kedip).
(function () {
    // penanda JS aktif: animasi reveal hanya menyembunyikan konten jika JS jalan
    document.documentElement.classList.add("js");

    var tema = null;
    try {
        tema = localStorage.getItem("tema");
    } catch (e) {}
    if (!tema) {
        tema = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    document.documentElement.setAttribute("data-bs-theme", tema);

    document.addEventListener("DOMContentLoaded", function () {
        // bisa lebih dari satu tombol (navbar mobile dan desktop)
        document.querySelectorAll(".theme-toggle").forEach(function (tombol) {
            tombol.addEventListener("click", function () {
                var baru = document.documentElement.getAttribute("data-bs-theme") === "dark" ? "light" : "dark";
                document.documentElement.setAttribute("data-bs-theme", baru);
                try {
                    localStorage.setItem("tema", baru);
                } catch (e) {}
            });
        });
    });
})();
