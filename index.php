<?php
require 'db.php';
require 'functions.php';

$gagal = '';
$task_edit = null;

$tasks = viewTask();
doneTask(); 
deleteTask();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                updateTask();
                break;
            case 'tambah':
                $gagal = insertTask();
                break;
        }
    }
}

if (isset($_GET['edit'])) {
    $task_edit = ambilTask();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1>Isi Kegiatan </h1>
            <?php if (!empty($gagal)): ?>
                <p style="color: red;"><?php echo $gagal; ?></p>
            <?php endif; ?>
            <form action="index.php" method="post">
                <input type="hidden" name="action" value="<?php echo $task_edit ? 'update' : 'tambah'; ?>">
                <?php if ($task_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $task_edit['id']; ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="judul" value="<?php echo $task_edit ? $task_edit['judul'] : '' ?>" placeholder="Masukkan nama tugas" autofocus>
                </div>
                <div class="input-group">
                    <input type="text" name="deskripsi" value="<?php echo $task_edit ? $task_edit['deskripsi'] : '' ?>" placeholder="Masukkan deskripsi tugas">
                </div>
                <div class="input-group">
                    <input type="date" name="tanggal" value="<?php echo $task_edit ? $task_edit['tanggal'] : '' ?>" placeholder="Masukkan tenggat tugas">
                </div>
                <div class="input-group">
                    <input type="submit" name="submit" value="<?php echo $task_edit ? 'Update' : 'Tambah' ?>">
                </div>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Judul</td>
                        <td>Deksripsi</td>
                        <td>Tenggat</td>
                        <td>Status</td>
                        <td colspan="3">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $key => $task) { 
                        $formattedDate = date('d/m/Y', strtotime($task['tanggal']));
                    ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $task['judul']; ?></td>
                            <td><?php echo $task['deskripsi']; ?></td>
                            <td><?php echo $formattedDate; ?></td>
                            <td><?php echo $task['status']; ?></td>
                            <td class="edit_btn"><a href="index.php?edit=<?php echo $task['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            </td>
                            <td>
                                <input type="checkbox"
                                    id="check_<?php echo $task['id']; ?>"
                                    class="status-checkbox"
                                    onclick="window.location.href = 'index.php?done=<?php echo $task['id']; ?>&status=<?php echo $task['status']; ?>'"
                                    <?php echo $task['status'] == 'sudah' ? 'checked' : ''; ?>>
                                <label for="check_<?php echo $task['id']; ?>">
                                    <i class="fa-solid fa-square-check check-icon"></i>
                                    <i class="fa-solid fa-circle-xmark uncheck-icon"></i>
                                </label>
                            </td>
                            <td class="delete_btn"><a href="index.php?delete=<?php echo $task['id']; ?>" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>