<?php

require 'db.php';
require 'functions.php';

$gagal = '';

$datas = viewTask();

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

$task_edit = null;
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Poppins;
            background-color: #603F26;
            color: #FFEAC5;
        }

        a {
            color: #FFEAC5;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid;
        }

        td {
            padding: 10px;

        }

        .container {
            width: 100%;
            display: flex;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 30px 25px;
            background-color: #6C4E31;
            border-radius: 10px;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-container {
            width: 100%;
            padding: 20px;
            overflow-x: auto;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            margin: 10px 0;
        }

        ::placeholder {
            color: #6C4E31;
            opacity: 1;
        }

        input[type="text"],
        input[type="date"] {
            padding: 15px 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-weight: 700;
            background-color: #FFDBB5;
            color: #603F26;
        }

        input[type="submit"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 800;
            background-color: #FFDBB5;
            color: #603F26;
        }

        .status-checkbox {
            display: none;
        }

        .status-checkbox+label {
            cursor: pointer;
        }

        .status-checkbox+label .check-icon {
            display: inline-block;
        }

        .status-checkbox+label .uncheck-icon {
            display: none;
        }

        .status-checkbox:checked+label .check-icon {
            display: none;
        }

        .status-checkbox:checked+label .uncheck-icon {
            display: inline-block;
        }

        @media only screen and (max-width: 600px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .form-container {
                width: 100%;
            }

            .table-container {
                width: 100%;
                padding: 0;
                margin: 20px;
            }

            table {
                width: 100%;
            }
        }
    </style>
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
                    <input type="text" name="judul" value="<?php echo $task_edit ? $task_edit['judul'] : '' ?>" placeholder="Masukkan nama tugas">
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
                    <?php foreach ($datas as $key => $data) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $data['judul']; ?></td>
                            <td><?php echo $data['deskripsi']; ?></td>
                            <td><?php echo $data['tanggal']; ?></td>
                            <td><?php echo $data['status']; ?></td>
                            </td>
                            <td class="edit_btn"><a href="index.php?edit=<?php echo $data['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            </td>
                            <td>
                                <input type="checkbox"
                                    id="check_<?php echo $data['id']; ?>"
                                    class="status-checkbox"
                                    onclick="window.location.href = 'index.php?done=<?php echo $data['id']; ?>&status=<?php echo $data['status']; ?>'"
                                    <?php echo $data['status'] == 'sudah' ? 'checked' : ''; ?>>
                                <label for="check_<?php echo $data['id']; ?>">
                                    <i class="fa-solid fa-square-check check-icon"></i>
                                    <i class="fa-solid fa-circle-xmark uncheck-icon"></i>
                                </label>
                            </td>
                            <td class="delete_btn"><a href="index.php?delete=<?php echo $data['id']; ?>" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>