// Dimuat di <head> supaya tema terpasang sebelum halaman tampil (tanpa kedip).
(function () {
    var tema = null;
    try {
        tema = localStorage.getItem("tema");
    } catch (e) {}
    if (!tema) {
        tema = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    document.documentElement.setAttribute("data-bs-theme", tema);

    document.addEventListener("DOMContentLoaded", function () {
        var tombol = document.getElementById("theme-toggle");
        if (!tombol) return;
        tombol.addEventListener("click", function () {
            var baru = document.documentElement.getAttribute("data-bs-theme") === "dark" ? "light" : "dark";
            document.documentElement.setAttribute("data-bs-theme", baru);
            try {
                localStorage.setItem("tema", baru);
            } catch (e) {}
        });
    });
})();
