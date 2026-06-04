<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { 
    header('Location: index.php'); 
    exit(); 
}

$id = (int)$_GET["id"];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { 
    header('Location: index.php');
    exit(); 
}

$row = mysqli_fetch_assoc($result);
$current_foto = $row["foto"];
$errors = [];
$success = "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa</title>
</head>
<body>
    <h2>Edit Data Mahasiswa</h2>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="color: green;"><p><?= $success; ?></p></div>
    <?php endif; ?>

    
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Foto Saat Ini:</label><br>
            <?php if ($current_foto): ?>
                <img src="uploads/mahasiswa/<?= $current_foto ?>" width="300" alt="Foto"><br>
            <?php else: ?>
                <span>Tidak ada foto</span>
            <?php endif; ?>
            <br><br>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label>NIM</label><br>
            <?= htmlspecialchars($row['nim']) ?>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Nama Lengkap</label><br>
            <?= htmlspecialchars($row['nama']) ?>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Jurusan</label><br>
            <?= htmlspecialchars($row['jurusan']) ?>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Email</label><br>
            <?= htmlspecialchars($row['email']) ?>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label>Alamat</label><br>
            <?= htmlspecialchars($row['alamat']) ?>
        </div>

        <a href="edit.php?id=<?php echo $id;?>" class="btn btn-secondary">Edit data</a>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
</body>
</html>