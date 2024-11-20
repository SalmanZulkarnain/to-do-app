<?php 
$db = new SQLite3('db_task.sqlite');

if (!$db) {
    echo $db->lastErrorMsg();
}

$db->query("CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY,
    judul TEXT NOT NULL,
    deskripsi TEXT NULL,
    status TEXT CHECK( status IN ('sudah', 'belum') ) DEFAULT 'belum',
    tanggal DATETIME
)");
