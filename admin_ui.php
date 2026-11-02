<?php
// fungsi bantu untuk tampilan admin (dipakai admin.php dan file data)

// tampilkan notifikasi (toast) lalu pindah halaman; menggantikan alert() bawaan browser
// $tipe: "ok" atau "err"
function toast_redirect($pesan, $tipe, $url)
{
    echo "<script>kirimToast(" . json_encode($pesan) . "," . json_encode($tipe) . "," . json_encode($url) . ");</script>";
}

// pagination untuk tabel/grid yang dimuat lewat ajax (kelas .halaman dibaca script di article.php dan gallery.php)
function tampil_pager($hlm, $jumlah_page)
{
    if ($jumlah_page < 2) {
        return;
    }
    $jumlah_number = 1; // jumlah halaman ke kanan dan kiri dari halaman yang aktif
    $start_number = ($hlm > $jumlah_number) ? $hlm - $jumlah_number : 1;
    $end_number = ($hlm < ($jumlah_page - $jumlah_number)) ? $hlm + $jumlah_number : $jumlah_page;

    echo '<nav class="mt-3" aria-label="Pages"><ul class="pagination justify-content-end mb-0">';

    if ($hlm == 1) {
        echo '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><i class="bi bi-chevron-left"></i></a></li>';
    } else {
        echo '<li class="page-item halaman" id="' . ($hlm - 1) . '"><a class="page-link" href="#" aria-label="Previous"><i class="bi bi-chevron-left"></i></a></li>';
    }

    for ($i = $start_number; $i <= $end_number; $i++) {
        $aktif = ($hlm == $i) ? ' active' : '';
        echo '<li class="page-item halaman' . $aktif . '" id="' . $i . '"><a class="page-link" href="#">' . $i . '</a></li>';
    }

    if ($hlm >= $jumlah_page) {
        echo '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next"><i class="bi bi-chevron-right"></i></a></li>';
    } else {
        echo '<li class="page-item halaman" id="' . ($hlm + 1) . '"><a class="page-link" href="#" aria-label="Next"><i class="bi bi-chevron-right"></i></a></li>';
    }

    echo '</ul></nav>';
}

// potong teks panjang untuk ringkasan di tabel
function ringkas($teks, $panjang = 120)
{
    $teks = trim((string)$teks);
    if (mb_strlen($teks) <= $panjang) {
        return $teks;
    }
    return rtrim(mb_substr($teks, 0, $panjang)) . '…';
}
