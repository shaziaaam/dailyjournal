<?php
// hanya bisa diakses setelah login (lewat admin.php)
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
?>
<div class="page-head">
    <div>
        <h1 class="page-title">Gallery</h1>
        <p class="page-sub">Upload, replace, and remove photos shown on the homepage.</p>
    </div>
    <div class="page-tools">
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-cloud-arrow-up"></i> Add Photo
        </button>
    </div>
</div>

<div id="gallery_data"></div>

<!-- Awal Modal Tambah-->
<div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulTambah">Add Photo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambahGambar" class="form-label">Choose an image to upload</label>
                        <input type="file" id="tambahGambar" class="form-control js-preview" name="gambar" accept="image/jpeg,image/png,image/gif,image/webp" required>
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
    function load_data(hlm){
        $.ajax({
            url : "gallery_data.php",
            method : "POST",
            data : { hlm: hlm || 1 },
            success : function(data){
                $('#gallery_data').html(data);
            }
        });
    }
    load_data(1);

    $(document).on('click', '.halaman', function(e){
        e.preventDefault();
        load_data($(this).attr("id"));
    });

    <?php if (isset($_GET['new'])) { ?>
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambah')).show();
    <?php } ?>
});
</script>

<?php
include "upload_foto.php";

// Jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        $cek_upload = upload_foto($_FILES["gambar"]);
        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            toast_redirect($cek_upload['message'], 'err', 'admin.php?page=gallery');
            die;
        }
    }

    $gambar_lama = '';
    if (isset($_POST['id'])) {
        $id = (int)$_POST['id'];

        // Nama gambar lama diambil dari database, bukan dari form
        $stmt = $conn->prepare("SELECT gambar FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $gambar_lama = (string)$stmt->fetchColumn();

        if ($nama_gambar == '') {
            $gambar = $gambar_lama;
        }

        // tanggal asli foto dipertahankan saat diedit supaya urutan galeri tidak berubah
        $stmt = $conn->prepare("UPDATE gallery SET gambar = ?, username = ? WHERE id = ?");
        $params = [$gambar, $username, $id];
    } else {
        $stmt = $conn->prepare("INSERT INTO gallery (gambar, tanggal, username) VALUES (?, ?, ?)");
        $params = [$gambar, $tanggal, $username];
    }

    if ($stmt->execute($params)) {
        // Jika ganti gambar, hapus file lama (jika tidak dipakai di tempat lain)
        if ($nama_gambar != '' && $gambar_lama != '') {
            hapus_gambar($conn, $gambar_lama);
        }

        toast_redirect('Saved successfully', 'ok', 'admin.php?page=gallery');
    } else {
        toast_redirect('Failed to save: ' . $stmt->errorInfo()[2], 'err', 'admin.php?page=gallery');
    }

}

// Jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];

    // Nama gambar diambil dari database, bukan dari form
    $stmt = $conn->prepare("SELECT gambar FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $gambar = (string)$stmt->fetchColumn();

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Hapus file gambar jika tidak dipakai di tempat lain
        hapus_gambar($conn, $gambar);

        toast_redirect('Deleted successfully', 'ok', 'admin.php?page=gallery');
    } else {
        toast_redirect('Failed to delete: ' . $stmt->errorInfo()[2], 'err', 'admin.php?page=gallery');
    }

}
?>
