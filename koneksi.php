<?php
date_default_timezone_set("Asia/Jakarta");

//sembunyikan pesan error PHP dari pengunjung, kecuali saat debugging (set env JOURNAL_DEBUG=1)
if (!getenv("JOURNAL_DEBUG")) {
    ini_set("display_errors", "0");
}

//file database SQLite disimpan di folder data/ (diblokir dari akses web lewat .htaccess)
//lokasi bisa diganti lewat env JOURNAL_DB, misalnya ke folder di luar web root
$db_file = getenv("JOURNAL_DB") ?: __DIR__ . "/data/webdailyjournal.db";
if (!is_dir(dirname($db_file))) {
    mkdir(dirname($db_file), 0755, true);
}
$baru = !file_exists($db_file);

//create connection
try {
    $conn = new PDO("sqlite:" . $db_file);
} catch (PDOException $e) {
    die("Connection failed");
}
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

//database baru dibuat otomatis dari data/schema.sql
if ($baru) {
    $conn->exec(file_get_contents(__DIR__ . "/data/schema.sql"));
}

?>
