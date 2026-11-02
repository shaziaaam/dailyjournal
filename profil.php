<?php
include "koneksi.php";

// Cek jika user sudah login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

// Ambil data user berdasarkan username dari session
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    echo "<script>document.location='logout.php';</script>";
    exit;
}
?>

<div class="page-head">
    <div>
        <h1 class="page-title">Profile</h1>
        <p class="page-sub">Change your password or profile photo.</p>
    </div>
</div>

<div class="panel profile-card">
    <form method="post" action="" enctype="multipart/form-data">
        <div class="d-flex align-items-center gap-3 mb-4">
            <?php if (!empty($user['foto']) && is_file("img/" . $user['foto'])) { ?>
                <img class="profile-photo" src="img/<?= htmlspecialchars($user['foto']) ?>" alt="Profile photo">
            <?php } else { ?>
                <span class="avatar profile-photo fs-2"><?= htmlspecialchars(strtoupper(mb_substr($user['username'], 0, 1))) ?></span>
            <?php } ?>
            <div>
                <div class="fw-bold fs-5"><?= htmlspecialchars($user['username']) ?></div>
                <div class="text-secondary small">Signed in as admin</div>
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Change Password</label>
            <input type="password" id="password" class="form-control" name="password" placeholder="Enter a new password only if you want to change it" autocomplete="new-password">
        </div>
        <div class="mb-4">
            <label for="foto" class="form-label">Change Profile Photo</label>
            <input type="file" id="foto" class="form-control js-preview" name="foto" accept="image/jpeg,image/png,image/gif,image/webp">
            <div class="form-text">JPG, PNG, GIF, or WebP. Large photos are resized automatically.</div>
            <div class="preview-box" hidden><img src="" alt="Selected image preview"></div>
        </div>
        <button type="submit" name="simpan" class="btn btn-accent"><i class="bi bi-check2-circle"></i> Save</button>
    </form>
</div>

<?php
// Tombol simpan
if (isset($_POST['simpan'])) {
    // id dan foto lama diambil dari akun yang sedang login, bukan dari form
    $id = $user['id'];
    $password = (string)$_POST['password'];
    $foto_lama = (string)$user['foto'];
    $foto_baru = $foto_lama;

    // Proses upload foto jika ada
    if ($_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        include_once "upload_foto.php";
        $cek_upload = upload_foto($_FILES['foto']);

        if ($cek_upload['status']) {
            $foto_baru = $cek_upload['message'];
        } else {
            toast_redirect($cek_upload['message'], 'err', 'admin.php?page=profil');
            exit;
        }
    }

    // Proses update ke database
    if ($password !== '') {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET password = ?, foto = ? WHERE id = ?");
        $params = [$password_hashed, $foto_baru, $id];
    } else {
        $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE id = ?");
        $params = [$foto_baru, $id];
    }

    if ($stmt->execute($params)) {
        // Hapus foto lama jika sudah diganti dan tidak dipakai di tempat lain
        if ($foto_baru !== $foto_lama) {
            include_once "upload_foto.php";
            hapus_gambar($conn, $foto_lama);
        }
        toast_redirect('Profile updated successfully', 'ok', 'admin.php?page=profil');
    } else {
        toast_redirect('Failed to update profile: ' . $stmt->errorInfo()[2], 'err', 'admin.php?page=profil');
    }
}
?>
