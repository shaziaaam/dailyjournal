<?php
date_default_timezone_set("Asia/Jakarta");

//file database SQLite, dibuat otomatis dari schema.sql jika belum ada
$db_file = __DIR__ . "/webdailyjournal.db";
$baru = !file_exists($db_file);

//create connection
try {
    $conn = new PDO("sqlite:" . $db_file);
} catch (PDOException $e) {
    die("Connection failed :" . $e->getMessage());
}
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

if ($baru) {
    $conn->exec(file_get_contents(__DIR__ . "/schema.sql"));
}

?>
