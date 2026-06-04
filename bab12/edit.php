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

if (isset($_POST['update'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $foto_filename = $current_foto; 

    if (empty($nim)) $errors[] = 'NIM tidak boleh kosong';
    if (strlen($nim) < 8) $errors[] = 'NIM tidak boleh kurang dari 8 karakter';
    if (strlen($nim) > 12) $errors[] = 'NIM tidak boleh melebihi 12 karakter';
    if (!is_numeric($nim)) $errors[] = 'NIM hanya boleh mengandung huruf';
    if (empty($nama)) $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan)) $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email)) $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';

    if ($nim != $row['nim']) {
        $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
        if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah terdaftar';
    }
   
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto) deleteFile($current_foto);
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
        if ($current_foto) deleteFile($current_foto);
        $foto_filename = null;
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET
                nim='$nim', nama='$nama', jurusan='$jurusan',
                email='$email', alamat='$alamat', foto=$foto_sql
                WHERE id=$id";
                
        if (mysqli_query($conn, $sql)) {
            $success = 'Data berhasil diperbarui!';
            $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
            $row = mysqli_fetch_assoc($result);
            $current_foto = $row["foto"];
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mahasiswa</title>
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

    <form action="edit.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Foto Saat Ini:</label><br>
            <?php if ($current_foto): ?>
                <img src="uploads/mahasiswa/<?= $current_foto ?>" width="100" alt="Foto"><br>
                <input type="checkbox" name="hapus_foto" value="1"> Centang untuk hapus foto saat ini
            <?php else: ?>
                <span>Tidak ada foto</span>
            <?php endif; ?>
            <br><br>
            <label>Ganti Foto Profil (Opsional)</label><br>
            <input type="file" name="foto" accept="image/*">
            <small>Format: JPG, PNG, GIF | Maks: 5MB</small>
        </div>

        <div class="form-group" style="margin-bottom: 10px;">
            <label>NIM *</label><br>
            <input type="text" name="nim" required value="<?= htmlspecialchars($row['nim']) ?>">
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Nama Lengkap *</label><br>
            <input type="text" name="nama" required value="<?= htmlspecialchars($row['nama']) ?>">
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Jurusan *</label><br>
            <input type="text" name="jurusan" required value="<?= htmlspecialchars($row['jurusan']) ?>">
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
            <label>Email *</label><br>
            <input type="email" name="email" required value="<?= htmlspecialchars($row['email']) ?>">
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label>Alamat</label><br>
            <textarea name="alamat" rows="4" style="width: 300px;"><?= htmlspecialchars($row['alamat']) ?></textarea>
        </div>

        <button type="submit" name="update" class="btn">Perbarui Data</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>