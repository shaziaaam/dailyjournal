<?php
include "session_init.php";
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
include "koneksi.php";
include "admin_ui.php";

// semua foto ditampilkan dalam satu halaman, tanpa pagination
$daftar = $conn->query("SELECT * FROM gallery ORDER BY tanggal DESC")->fetchAll();
$total_records = count($daftar);

if (!$daftar) {
?>
    <div class="panel empty-state">
        <i class="bi bi-images"></i>
        No photos yet. Click "Add Photo" to upload the first one.
    </div>
<?php
    exit;
}
?>
<div class="photo-grid">
    <?php foreach ($daftar as $row) {
        $id = (int)$row["id"];
        $ada_gambar = $row["gambar"] != '' && file_exists('img/' . $row["gambar"]);
    ?>
        <div class="photo-card">
            <?php if ($ada_gambar) { ?>
                <img class="photo img-popup" src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt=""
                    data-src="img/<?= htmlspecialchars($row["gambar"]) ?>" loading="lazy">
            <?php } else { ?>
                <div class="photo d-flex align-items-center justify-content-center text-secondary" style="cursor:default"><i class="bi bi-image fs-1"></i></div>
            <?php } ?>
            <div class="photo-actions">
                <a href="#" title="Edit" aria-label="Edit" class="btn-icon btn-icon-edit" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $id ?>"><i class="bi bi-pencil-square"></i></a>
                <a href="#" title="Delete" aria-label="Delete" class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $id ?>"><i class="bi bi-trash3"></i></a>
            </div>
            <div class="photo-meta"><?= htmlspecialchars(date('M j, Y', strtotime($row["tanggal"] ?? 'now'))) ?> &middot; <?= htmlspecialchars($row["username"] ?? "") ?></div>
        </div>
    <?php } ?>
</div>

<p class="count-line"><?= $total_records ?> <?= $total_records === 1 ? 'PHOTO' : 'PHOTOS' ?></p>

<?php
// modal edit dan hapus ditaruh di luar grid supaya posisinya tidak terganggu animasi kartu
foreach ($daftar as $row) {
    $id = (int)$row["id"];
    $ada_gambar = $row["gambar"] != '' && file_exists('img/' . $row["gambar"]);
?>
    <!-- Awal Modal Edit -->
    <div class="modal fade" id="modalEdit<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulEdit<?= $id ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="judulEdit<?= $id ?>">Edit Photo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <div class="mb-3">
                            <label for="editGambar<?= $id ?>" class="form-label">Change Image</label>
                            <input type="file" id="editGambar<?= $id ?>" class="form-control js-preview" name="gambar" accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-text">Leave empty to keep the current image.</div>
                            <div class="preview-box" hidden><img src="" alt="Selected image preview"></div>
                        </div>
                        <?php if ($ada_gambar) { ?>
                            <div class="mb-3">
                                <div class="form-label">Current Image</div>
                                <img class="current-img" src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="">
                            </div>
                        <?php } ?>
                        <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($row["gambar"] ?? "") ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="simpan" value="simpan" class="btn btn-accent"><i class="bi bi-check2-circle"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Akhir Modal Edit -->

    <!-- Awal Modal Hapus -->
    <div class="modal fade" id="modalHapus<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulHapus<?= $id ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="judulHapus<?= $id ?>">Delete Photo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="mb-2">Are you sure you want to delete this photo?</p>
                        <?php if ($ada_gambar) { ?>
                            <img class="current-img mb-2" src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="">
                        <?php } ?>
                        <p class="text-secondary small mb-0">The file is also removed if no other item uses it. It cannot be undone.</p>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="gambar" value="<?= htmlspecialchars($row["gambar"] ?? "") ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="hapus" value="hapus" class="btn btn-delete"><i class="bi bi-trash3"></i> Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Akhir Modal Hapus -->
<?php } ?>
