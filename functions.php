<?php 

require 'db.php';

function insertTask() {
    global $db;
    
    $gagal = '';
    if(isset($_POST['submit'])) {
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];
        
        if (empty($tanggal)) {
            $tanggal = date('d/m/Y');
        } else {
            $tanggal = date('d/m/Y', strtotime($tanggal));
        }

        if (empty($judul) && empty($deskripsi) && empty($tanggal)) {
            $gagal = "Silakan isi form tugas.";
        } elseif (empty($judul)) {
            $gagal = "Judul harus diisi.";
        } else {
            $db->query("INSERT INTO tasks (judul, deskripsi, tanggal) VALUES ('$judul', '$deskripsi', '$tanggal')");
            header('Location: index.php');
            exit;
        }
    }
    return $gagal;
}

function viewTask() {
    global $db;
    
    $result = $db->query("SELECT * FROM tasks");
    $data = [];
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

function doneTask(){
    global $db;

    if(isset($_GET['done'])) {
        $status = 'belum';
        $id = $_GET['done'];
        
        if($_GET['status'] == 'belum') {
            $status = 'sudah';
        } else {
            $status = 'belum';
        }
        $db->query("UPDATE tasks SET status = '$status' WHERE id = '$id'");
        header('Location: index.php');
        exit;
    }
}
function ambilTask() {
    global $db;

    if (!isset($_GET['edit'])) {
        return null; 
    }

    $id = $_GET['edit'];
    $ambil = $db->query("SELECT * FROM tasks WHERE id = '$id'");
    
    $task = $ambil->fetchArray(SQLITE3_ASSOC);
    if ($task) {
        $task['tanggal'] = date('d/m/Y', strtotime($task['tanggal']));
    }
    return $task;
}   

function updateTask() {
    global $db;

    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];

        if (empty($tanggal)) {
            $tanggal = date('d/m/Y');
        } else {
            $tanggal = date('d/m/Y', strtotime($tanggal));
        }
        
        if (!empty($judul)) {
            $db->query("UPDATE tasks SET judul = '$judul', deskripsi = '$deskripsi', tanggal = '$tanggal' WHERE id = '$id'");
        }
    }
}

function deleteTask() {
    global $db;

    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $db->query("DELETE FROM tasks WHERE id = '$id'");
        header('Location: index.php');
        exit;
    }
}