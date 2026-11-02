<?php
include "session_init.php";
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}
include "koneksi.php";
include "admin_ui.php";

// semua artikel ditampilkan dalam satu halaman, tanpa pagination
// pencarian judul dan isi (prepared statement)
$q = trim((string)($_POST['q'] ?? ''));
$where = '';
$params = [];
if ($q !== '') {
    $where = "WHERE judul LIKE ? OR isi LIKE ?";
    $params = ['%' . $q . '%', '%' . $q . '%'];
}

$stmt = $conn->prepare("SELECT * FROM article $where ORDER BY tanggal DESC");
$stmt->execute($params);
$daftar = $stmt->fetchAll();
$total_records = count($daftar);

if (!$daftar) {
?>
    <div class="panel empty-state">
        <i class="bi bi-journal-x"></i>
        <?= $q !== '' ? 'No articles match "' . htmlspecialchars($q) . '".' : 'No articles yet. Click "Add Article" to write the first one.' ?>
    </div>
<?php
    exit;
}
?>
<div class="panel table-responsive">
    <table class="table table-hover align-middle admin-table">
        <thead>
            <tr>
                <th>Article</th>
                <th class="cell-ringkas-col">Excerpt</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftar as $row) {
                $ada_gambar = $row["gambar"] != '' && file_exists('img/' . $row["gambar"]);
            ?>
                <tr>
                    <td>
                        <div class="cell-article">
                            <?php if ($ada_gambar) { ?>
                                <img src="img/<?= htmlspecialchars($row["gambar"]) ?>" alt="" class="img-popup"
                                    data-src="img/<?= htmlspecialchars($row["gambar"]) ?>" loading="lazy">
                            <?php } else { ?>
                                <span class="noimg"><i class="bi bi-image"></i></span>
                            <?php } ?>
                            <div>
                                <div class="judul"><?= htmlspecialchars($row["judul"] ?? "") ?></div>
                                <div class="meta"><?= htmlspecialchars(date('M j, Y H:i', strtotime($row["tanggal"] ?? 'now'))) ?> &middot; <?= htmlspecialchars($row["username"] ?? "") ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="cell-ringkas cell-ringkas-col"><?= htmlspecialchars(ringkas($row["isi"] ?? "", 130)) ?></td>
                    <td class="cell-aksi">
                        <a href="#" title="Edit" aria-label="Edit" class="btn-icon btn-icon-edit" data-bs-toggle="modal" data-bs-target="#modalEdit<?= (int)$row["id"] ?>"><i class="bi bi-pencil-square"></i></a>
                        <a href="#" title="Delete" aria-label="Delete" class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modalHapus<?= (int)$row["id"] ?>"><i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<p class="count-line"><?= $total_records ?> <?= $total_records === 1 ? 'ARTICLE' : 'ARTICLES' ?><?= $q !== '' ? ' FOUND' : '' ?></p>

<?php
// modal edit dan hapus ditaruh di luar panel kaca supaya posisinya tidak terganggu efek blur
foreach ($daftar as $row) {
    $id = (int)$row["id"];
    $ada_gambar = $row["gambar"] != '' && file_exists('img/' . $row["gambar"]);
?>
    <!-- Awal Modal Edit -->
    <div class="modal fade" id="modalEdit<?= $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulEdit<?= $id ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="judulEdit<?= $id ?>">Edit Article</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <div class="mb-3">
                            <label for="editJudul<?= $id ?>" class="form-label">Title</label>
                            <input type="text" id="editJudul<?= $id ?>" class="form-control" name="judul" placeholder="Enter the article title" value="<?= htmlspecialchars($row["judul"] ?? "") ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editIsi<?= $id ?>" class="form-label">Content</label>
                            <textarea id="editIsi<?= $id ?>" class="form-control" placeholder="Write the article content" name="isi" required><?= htmlspecialchars($row["isi"] ?? "") ?></textarea>
                        </div>
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
                    <h1 class="modal-title fs-5" id="judulHapus<?= $id ?>">Delete Article</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="mb-1">Are you sure you want to delete this article?</p>
                        <p class="fw-bold mb-2">"<?= htmlspecialchars($row["judul"] ?? "") ?>"</p>
                        <p class="text-secondary small mb-0">This also removes its image if no other item uses it. It cannot be undone.</p>
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
