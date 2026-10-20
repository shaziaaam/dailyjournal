<?php
// hanya bisa diakses setelah login (lewat admin.php)
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
?>
<div class="container">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-accent mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Add Gallery
    </button>
    <div class="row">
        <div class="table-responsive" id="gallery_data">  
        </div>

        <!-- Awal Modal Tambah-->
        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Gallery</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Choose an image to upload</label>
                                <input type="file" class="form-control" name="gambar">
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
    </div>
</div>

<script>
$(document).ready(function(){
    load_data();
    function load_data(hlm){
        $.ajax({
            url : "gallery_data.php",
            method : "POST",
            data : {hlm: hlm},
            success : function(data){
                    $('#gallery_data').html(data);
            }
        })
    }
    $(document).on('click', '.halaman', function(){
    var hlm = $(this).attr("id");
    load_data(hlm);
    });
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
            echo "<script>
                alert(" . json_encode($cek_upload['message']) . ");
                document.location='admin.php?page=gallery';
            </script>";
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

        $stmt = $conn->prepare("UPDATE gallery SET gambar = ?, tanggal = ?, username = ? WHERE id = ?");
        $params = [$gambar, $tanggal, $username, $id];
    } else {
        $stmt = $conn->prepare("INSERT INTO gallery (gambar, tanggal, username) VALUES (?, ?, ?)");
        $params = [$gambar, $tanggal, $username];
    }

    if ($stmt->execute($params)) {
        // Jika ganti gambar, hapus file lama (jika tidak dipakai di tempat lain)
        if ($nama_gambar != '' && $gambar_lama != '') {
            hapus_gambar($conn, $gambar_lama);
        }

        echo "<script>
            alert('Saved successfully');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert(" . json_encode('Failed to save: ' . $stmt->errorInfo()[2]) . ");
            document.location='admin.php?page=gallery';
        </script>";
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

        echo "<script>
            alert('Deleted successfully');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert(" . json_encode('Failed to delete: ' . $stmt->errorInfo()[2]) . ");
            document.location='admin.php?page=gallery';
        </script>";
    }

}
?>
