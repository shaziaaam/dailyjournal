<?php
// hanya bisa diakses setelah login (lewat admin.php)
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
?>
<?php
//menghitung jumlah baris data article dan gallery
$jumlah_article = (int)$conn->query("SELECT COUNT(*) FROM article")->fetchColumn();
$jumlah_gallery = (int)$conn->query("SELECT COUNT(*) FROM gallery")->fetchColumn();

?>
<div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4">
    <div class="col">
        <a href="admin.php?page=article" class="text-decoration-none">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-journal-text"></i> Article</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_article; ?></span>
                    </div> 
                </div>
            </div>
        </div>
        </a>
    </div> 
    <div class="col">
        <a href="admin.php?page=gallery" class="text-decoration-none">
        <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-images"></i> Gallery</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_gallery; ?></span>
                    </div> 
                </div>
            </div>
        </div>
        </a>
    </div> 
</div>