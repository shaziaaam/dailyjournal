<?php
// hanya bisa diakses setelah login (lewat admin.php)
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
?>
<div class="page-head">
    <div>
        <h1 class="page-title">Article</h1>
        <p class="page-sub">Write, edit, and remove your travel stories.</p>
    </div>
    <div class="page-tools">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="search" id="cari" class="form-control" placeholder="Search articles" aria-label="Search articles" autocomplete="off">
        </div>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Add Article
        </button>
    </div>
</div>

<div id="article_data"></div>

<!-- Awal Modal Tambah-->
<div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulTambah">Add Article</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambahJudul" class="form-label">Title</label>
                        <input type="text" id="tambahJudul" class="form-control" name="judul" placeholder="Enter the article title" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambahIsi" class="form-label">Content</label>
                        <textarea id="tambahIsi" class="form-control" placeholder="Write the article content" name="isi" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tambahGambar" class="form-label">Image</label>
                        <input type="file" id="tambahGambar" class="form-control js-preview" name="gambar" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">JPG, PNG, GIF, or WebP. Large photos are resized automatically.</div>
                        <div class="preview-box" hidden><img src="" alt="Selected image preview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="simpan" value="simpan" class="btn btn-accent"><i class="bi bi-check2-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah-->

<script>
$(document).ready(function(){
    var kata = '';
    var timer;

    function load_data(hlm){
        $.ajax({
            url : "article_data.php",
            method : "POST",
            data : { hlm: hlm || 1, q: kata },
            success : function(data){
                $('#article_data').html(data);
            }
        });
    }
    load_data(1);

    $(document).on('click', '.halaman', function(e){
        e.preventDefault();
        load_data($(this).attr("id"));
    });

    // pencarian: tunggu sebentar setelah berhenti mengetik
    $('#cari').on('input', function(){
        var nilai = this.value;
        clearTimeout(timer);
        timer = setTimeout(function(){
            kata = nilai;
            load_data(1);
        }, 250);
    });

    <?php if (isset($_GET['new'])) { ?>
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambah')).show();
    <?php } ?>
});
</script>

<?php
include "upload_foto.php";

//jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    //jika ada file yang dikirim
    if ($nama_gambar != '') {
        //panggil function upload_foto untuk cek spesifikasi file yg dikirimkan user
        //function ini memiliki 2 keluaran yaitu status dan message
        $cek_upload = upload_foto($_FILES["gambar"]);

        //cek status true/false
        if ($cek_upload['status']) {
            //jika true maka message berisi nama file gambar
            $gambar = $cek_upload['message'];
        } else {
            //jika false maka message berisi pesan error, tampilkan dalam alert
            toast_redirect($cek_upload['message'], 'err', 'admin.php?page=article');
            die;
        }
    }

    //cek apakah ada id yang dikirimkan dari form
    if (isset($_POST['id'])) {
        //jika ada id, lakukan update data dengan id tersebut
        $id = (int)$_POST['id'];

        //nama gambar lama diambil dari database, bukan dari form
        $stmt = $conn->prepare("SELECT gambar FROM article WHERE id = ?");
        $stmt->execute([$id]);
        $gambar_lama = (string)$stmt->fetchColumn();

        if ($nama_gambar == '') {
            //jika tidak ganti gambar
            $gambar = $gambar_lama;
        }

        $stmt = $conn->prepare("UPDATE article
                                SET
                                judul =?,
                                isi =?,
                                gambar = ?,
                                username = ?
                                WHERE id = ?");

        // tanggal asli artikel dipertahankan saat diedit supaya urutan cerita tidak berubah
        $simpan = $stmt->execute([$judul, $isi, $gambar, $username, $id]);

        //jika ganti gambar, hapus file gambar lama (jika tidak dipakai di tempat lain)
        if ($simpan && $nama_gambar != '') {
            hapus_gambar($conn, $gambar_lama);
        }
    } else {
        //jika tidak ada id, lakukan insert data baru
        $stmt = $conn->prepare("INSERT INTO article (judul,isi,gambar,tanggal,username)
                                VALUES (?,?,?,?,?)");

        $simpan = $stmt->execute([$judul, $isi, $gambar, $tanggal, $username]);
    }

    if ($simpan) {
        toast_redirect('Saved successfully', 'ok', 'admin.php?page=article');
    } else {
        toast_redirect('Failed to save', 'err', 'admin.php?page=article');
    }

}

//jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];

    //nama gambar diambil dari database, bukan dari form
    $stmt = $conn->prepare("SELECT gambar FROM article WHERE id = ?");
    $stmt->execute([$id]);
    $gambar = (string)$stmt->fetchColumn();

    $stmt = $conn->prepare("DELETE FROM article WHERE id =?");

    $hapus = $stmt->execute([$id]);

    if ($hapus) {
        //hapus file gambar jika tidak dipakai di tempat lain
        hapus_gambar($conn, $gambar);

        toast_redirect('Deleted successfully', 'ok', 'admin.php?page=article');
    } else {
        toast_redirect('Failed to delete', 'err', 'admin.php?page=article');
    }

}
?>
