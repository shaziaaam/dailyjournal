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
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User tidak ditemukan!'); document.location='logout.php';</script>";
    exit;
}
?>

<div class="container mt-4">
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <input type="hidden" name="foto_lama" value="<?php echo $user['foto']; ?>">

        <div class="mb-3">
            <label for="password" class="form-label" style="font-size: 18px;">Ganti Password</label>
            <input type="password" class="form-control" name="password" placeholder="Tuliskan Password Baru Jika Ingin Mengganti Password Saja">
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label" style="font-size: 18px;">Ganti Foto Profil</label>
            <input type="file" class="form-control" name="foto">
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label" style="font-size: 18px;">Foto Profil Saat Ini</label><br>
            <img src="img/<?php echo htmlspecialchars($user['foto']); ?>" width="100px">
        </div>
        <button type="submit" name="simpan" class="btn btn-primary">simpan</button>
    </form>
</div>

<?php
// Tombol simpan
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $foto_lama;

    // Proses upload foto jika ada
    if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES['foto']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi format file
        if (in_array($file_type, ['jpg', 'jpeg', 'png'])) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto_baru = basename($_FILES['foto']['name']);

                // Hapus foto lama jika ada
                if ($foto_lama && file_exists("img/$foto_lama")) {
                    unlink("img/$foto_lama");
                }
            } else {
                echo "<script>alert('Error saat mengunggah foto.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file tidak diizinkan.');</script>";
            exit;
        }
    }

    // Proses update ke database
    if (!empty($password)) {
        $password_hashed = md5($password); // Hash password dengan MD5
        $stmt = $conn->prepare("UPDATE user SET password = ?, foto = ? WHERE id = ?");
        $stmt->bind_param("ssi", $password_hashed, $foto_baru, $id);
    } else {
        $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $foto_baru, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); document.location='profil.php';</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui: " . $conn->error . "');</script>";
    }
}
?>
