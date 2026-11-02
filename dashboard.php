<?php
// hanya bisa diakses setelah login (lewat admin.php)
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit;
}

// menghitung jumlah data article dan gallery
$jumlah_article = (int)$conn->query("SELECT COUNT(*) FROM article")->fetchColumn();
$jumlah_gallery = (int)$conn->query("SELECT COUNT(*) FROM gallery")->fetchColumn();

// artikel yang baru ditambahkan (diurutkan dari id terbaru)
$terbaru = $conn->query("SELECT id, judul, gambar, tanggal FROM article ORDER BY id DESC LIMIT 5")->fetchAll();

$jam = (int)date('G');
$sapa = $jam < 11 ? 'Good morning' : ($jam < 15 ? 'Good afternoon' : ($jam < 19 ? 'Good evening' : 'Good night'));
?>
<div class="page-head">
    <div>
        <h1 class="page-title"><?= $sapa ?>, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <p class="page-sub">Here is what is happening in your journal today.</p>
    </div>
    <div class="page-tools">
        <a href="admin.php?page=gallery&new=1" class="btn btn-ghost"><i class="bi bi-cloud-arrow-up"></i> Upload photo</a>
        <a href="admin.php?page=article&new=1" class="btn btn-accent"><i class="bi bi-plus-lg"></i> New article</a>
    </div>
</div>

<div class="stat-grid">
    <a href="admin.php?page=article" class="panel stat">
        <div class="stat-label">Articles</div>
        <div class="stat-num"><?= $jumlah_article ?></div>
        <div class="stat-note">Stories published</div>
    </a>
    <a href="admin.php?page=gallery" class="panel stat">
        <div class="stat-label">Photos</div>
        <div class="stat-num"><?= $jumlah_gallery ?></div>
        <div class="stat-note">In the gallery</div>
    </a>
</div>

<section class="panel panel-pad">
    <h2 class="panel-title">Recently added</h2>
    <?php if (!$terbaru) { ?>
        <div class="empty-state"><i class="bi bi-journal-plus"></i>No articles yet. Write your first one.</div>
    <?php } else { ?>
        <ul class="recent-list">
            <?php foreach ($terbaru as $row) { ?>
                <li>
                    <a href="admin.php?page=article">
                        <?php if ($row['gambar'] != '' && is_file('img/' . $row['gambar'])) { ?>
                            <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="" loading="lazy" />
                        <?php } ?>
                        <div style="min-width:0">
                            <div class="t"><?= htmlspecialchars($row['judul']) ?></div>
                            <small><?= htmlspecialchars(date('M j, Y', strtotime($row['tanggal']))) ?></small>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>
