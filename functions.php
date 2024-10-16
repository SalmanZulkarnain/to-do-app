<?php 

require 'db.php';

function insertTask() {
    global $db;
    
    $gagal = '';
    if(isset($_POST['submit'])) {
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];

        $formattedDate = DateTime::createFromFormat('d/m/Y', $tanggal);

        if ($formattedDate) {
            $tanggal = $formattedDate->format('Y-m-d');
        } else {
            $gagal = "Format tanggal tidak valid.";
        }

        if (empty($judul) && empty($deskripsi) && empty($tanggal)) {
            $gagal = "Silakan isi form tugas.";
        } elseif (empty($judul)) {
            $gagal = "Judul harus diisi.";
        } else {
            $stmt = $db->prepare("INSERT INTO tasks (judul, deskripsi, tanggal) VALUES (:judul, :deskripsi, :tanggal)");
            $stmt->bindParam(':judul', $judul, SQLITE3_TEXT,);
            $stmt->bindParam(':deskripsi', $deskripsi, SQLITE3_TEXT,);
            $stmt->bindParam(':tanggal', $tanggal, SQLITE3_TEXT,);
            
            if($stmt->execute()) {
                header('Location: index.php');
            } else {
                $gagal = "Gagal menyimpan data ke database";
            }
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
        $stmt = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
        $stmt->bindParam(':status', $status, SQLITE3_TEXT);
        
        if($stmt->execute()) {
            header('Location: index.php');
            exit;
        }
    }
}
function ambilTask() {
    global $db;

    if (!isset($_GET['edit'])) {
        return null; 
    }

    $id = $_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    return $result->fetchArray(SQLITE3_ASSOC);
}

function updateTask() {
    global $db;

    $gagal = '';
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal = $_POST['tanggal'];

        $formattedDate = DateTime::createFromFormat('d/m/Y', $tanggal);

        if ($formattedDate) {
            $tanggal = $formattedDate->format('Y-m-d');
        } 
        
        if (!empty($judul)) {

            $stmt = $db->prepare("UPDATE tasks SET judul = :judul, deskripsi = :deskripsi, tanggal = :tanggal WHERE id = :id");
            $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
            $stmt->bindParam(':judul', $judul, SQLITE3_TEXT);
            $stmt->bindParam(':deskripsi', $deskripsi, SQLITE3_TEXT);
            $stmt->bindParam(':tanggal', $tanggal, SQLITE3_TEXT);

            if($stmt->execute()) {
                header('Location: index.php');
            } else {
                $gagal = "Gagal mengupdate data";
            }
        }
    }
    return $gagal;
}

function deleteTask() {
    global $db;

    $gagal = '';
    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id, SQLITE3_INTEGER);
        
        if($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $gagal = "Gagal menghapus data";
        }
    }
    return $gagal;
}