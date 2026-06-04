<?php
include_once("config.php");
requireLogin();

$errors = [];
$success = "";

if (isset($_POST['submit'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $foto_filename = null;

    if (empty($nim)) $errors[] = 'NIM tidak boleh kosong';
    if (strlen($nim) < 8) $errors[] = 'NIM tidak boleh kurang dari 8 karakter';
    if (strlen($nim) > 12) $errors[] = 'NIM tidak boleh melebihi 12 karakter';
    if (!is_numeric($nim)) $errors[] = 'NIM hanya boleh mengandung huruf';
    if (empty($nama)) $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan)) $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email)) $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';

    $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
    if (mysqli_num_rows($chk) > 0) {
        $errors[] = 'NIM sudah terdaftar';
    }

    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto)
                VALUES ('$nim','$nama','$jurusan','$email','$alamat',$foto_sql)";
                
        if (mysqli_query($conn, $sql)) {
            $success = 'Data berhasil ditambahkan!';
            $_POST = array();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
            if ($foto_filename) deleteFile($foto_filename); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="email"], textarea { width: 100%; max-width: 400px; padding: 8px; box-sizing: border-box; }
        .required { color: red; }
        .btn { padding: 8px 15px; text-decoration: none; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn-submit { background-color: #28a745; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; margin-left: 5px; }
        .alert { max-width: 400px; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <h2>Tambah Data Mahasiswa</h2>
    <hr style="max-width: 400px; margin-left: 0; margin-bottom: 20px;">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach($errors as $err) echo "<li>$err</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success; ?> <a href="index.php">Lihat semua data</a>
        </div>
    <?php endif; ?>

    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Foto Profil</label>
            <input type="file" name="foto" accept="image/*">
            <br><small style="color: #666;">Format: JPG, PNG, GIF | Maks: 5MB</small>
        </div>

        <div class="form-group">
            <label>NIM <span class="required">*</span></label>
            <input type="text" name="nim" required value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="nama" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Jurusan <span class="required">*</span></label>
            <input type="text" name="jurusan" required value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="4"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-submit">Simpan Data</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>

</body>
</html>