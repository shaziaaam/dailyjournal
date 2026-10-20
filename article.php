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
        <i class="bi bi-plus-lg"></i> Add Article
    </button>
    <div class="row">
        <div class="table-responsive" id="article_data">
            
        </div>

        <!-- Awal Modal Tambah-->
        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Article</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Title</label>
                                <input type="text" class="form-control" name="judul" placeholder="Enter the article title" required>
                            </div>
                            <div class="mb-3">
                                <label for="floatingTextarea2">Content</label>
                                <textarea class="form-control" placeholder="Write the article content" name="isi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Image</label>
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
            url : "article_data.php",
            method : "POST",
            data : {
					            hlm: hlm
				           },
            success : function(data){
                    $('#article_data').html(data);
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
		        //jika true maka message berisi pesan error, tampilkan dalam alert
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=article';
            </script>";
            die;
        }
    }

		//cek apakah ada id yang dikirimkan dari form
    if (isset($_POST['id'])) {
        //jika ada id,    lakukan update data dengan id tersebut
        $id = $_POST['id'];

        if ($nama_gambar == '') {
            //jika tidak ganti gambar
            $gambar = $_POST['gambar_lama'];
        } else {
            //jika ganti gambar, hapus gambar lama
            unlink("assets/" . $_POST['gambar_lama']);
        }

        $stmt = $conn->prepare("UPDATE article 
                                SET 
                                judul =?,
                                isi =?,
                                gambar = ?,
                                tanggal = ?,
                                username = ?
                                WHERE id = ?");

        $simpan = $stmt->execute([$judul, $isi, $gambar, $tanggal, $username, $id]);
    } else {
		    //jika tidak ada id, lakukan insert data baru
        $stmt = $conn->prepare("INSERT INTO article (judul,isi,gambar,tanggal,username)
                                VALUES (?,?,?,?,?)");

        $simpan = $stmt->execute([$judul, $isi, $gambar, $tanggal, $username]);
    }

    if ($simpan) {
        echo "<script>
            alert('Saved successfully');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Failed to save');
            document.location='admin.php?page=article';
        </script>";
    }

}

//jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        //hapus file gambar
        unlink("assets/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE id =?");

    $hapus = $stmt->execute([$id]);

    if ($hapus) {
        echo "<script>
            alert('Deleted successfully');
            document.location='admin.php?page=article';
        </script>";
    } else {
        echo "<script>
            alert('Failed to delete');
            document.location='admin.php?page=article';
        </script>";
    }

}
?>