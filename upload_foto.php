<?php
function upload_foto($File){
	$uploadOk = 1;
	$hasil = array();
	$message = '';

	//File properties:
	$FileName = $File['name'];
	$TmpLocation = $File['tmp_name'];
	$FileSize = $File['size'];

	//Figure out what kind of file this is:
	$FileExt = explode('.', $FileName);
	$FileExt = strtolower(end($FileExt));

	//Allowed files:
	$Allowed = array('jpg', 'png', 'gif', 'jpeg');

	// Check upload error (misalnya melebihi batas upload_max_filesize di php.ini)
	if ($File['error'] !== UPLOAD_ERR_OK) {
		$message .= "Sorry, the upload failed. ";
		$uploadOk = 0;
	}

	// Check file size
	if ($FileSize > 2000000) {
		$message .= "Sorry, your file is too large, max 2MB. ";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if(!in_array($FileExt, $Allowed)){
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
		$uploadOk = 0;
	}

	// Isi file harus benar-benar gambar, bukan hanya ekstensinya
	if ($uploadOk == 1 && @getimagesize($TmpLocation) === false) {
		$message .= "Sorry, the file is not a valid image. ";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$message .= "Sorry, your file was not uploaded. ";
		$hasil['status'] = false;
		// if everything is ok, try to upload file
	}else{
		//Create new filename (tanggal + kode acak supaya tidak bentrok):
        $NewName = date("YmdHis") . bin2hex(random_bytes(3)) . '.' . $FileExt;
        $UploadDestination = "img/". $NewName;

		if (move_uploaded_file($TmpLocation, $UploadDestination)) {
			//echo "The file has been uploaded.";
			$message .= $NewName;
			$hasil['status'] = true;
		}else{
			$message .= "Sorry, there was an error uploading your file. ";
			$hasil['status'] = false;
		}
	}

	$hasil['message'] = $message;
	return $hasil;
}

// Hapus file gambar hanya jika sudah tidak dipakai oleh article, gallery, maupun foto profil.
// Panggil SETELAH baris database diubah atau dihapus.
function hapus_gambar($conn, $nama){
	$nama = basename((string)$nama); // cegah path traversal
	if ($nama === '' || !is_file("img/" . $nama)) {
		return;
	}

	$stmt = $conn->prepare("SELECT (SELECT COUNT(*) FROM article WHERE gambar = ?)
	                             + (SELECT COUNT(*) FROM gallery WHERE gambar = ?)
	                             + (SELECT COUNT(*) FROM user WHERE foto = ?)");
	$stmt->execute([$nama, $nama, $nama]);

	if ((int)$stmt->fetchColumn() === 0) {
		unlink("img/" . $nama);
	}
}
?>
