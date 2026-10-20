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
    echo "<script>alert('User not found!'); document.location='logout.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <form method="post" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="password" class="form-label" style="font-size: 18px;">Change Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter a new password only if you want to change it" autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label" style="font-size: 18px;">Change Profile Photo</label>
            <input type="file" class="form-control" name="foto">
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label" style="font-size: 18px;">Current Profile Photo</label><br>
            <?php if (!empty($user['foto']) && is_file("img/" . $user['foto'])) { ?>
                <img src="img/<?= htmlspecialchars($user['foto']) ?>" width="100px" alt="Profile photo">
            <?php } else { ?>
                <span class="text-secondary">No profile photo yet.</span>
            <?php } ?>
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
            echo "<script>alert(" . json_encode($cek_upload['message']) . ");</script>";
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
        echo "<script>alert('Profile updated successfully!'); document.location='admin.php?page=profil';</script>";
    } else {
        echo "<script>alert(" . json_encode('Failed to update profile: ' . $stmt->errorInfo()[2]) . ");</script>";
    }
}
?>
