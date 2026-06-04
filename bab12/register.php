<?php
include_once("config.php");

if (isLoggedIn()) { 
    header('Location: index.php'); 
    exit(); 
}

$errors = [];
$success = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($username)) $errors[] = 'Username tidak boleh kosong';
    if (empty($email)) $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';
    
    if (empty($full_name)) $errors[] = 'Nama lengkap tidak boleh kosong';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok';

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = 'Username atau email sudah terdaftar';
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, full_name, password) VALUES ('$username', '$email', '$full_name', '$hashed')";
        
        if (mysqli_query($conn, $sql)) {
            $success = 'Registrasi berhasil! Silakan <a href="login.php">login di sini</a>.';
            $_POST = array();
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
    <title>Register - Praktikum CRUD</title>
</head>
<body>
    <div style="max-width: 450px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; font-family: sans-serif;">
        <h2>Daftar Akun Baru</h2>
        <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">
        
        <?php if (!empty($errors)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; border-radius: 4px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach($errors as $err): ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 4px;">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div style="margin-bottom: 12px;">
                <label>Nama Lengkap <span style="color: red;">*</span></label><br>
                <input type="text" name="full_name" required style="width: 100%; padding: 8px; box-sizing: border-box;" 
                       value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Username <span style="color: red;">*</span></label><br>
                <input type="text" name="username" required style="width: 100%; padding: 8px; box-sizing: border-box;" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Email <span style="color: red;">*</span></label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px; box-sizing: border-box;" 
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Password <span style="color: red;">*</span></label><br>
                <input type="password" name="password" required style="width: 100%; padding: 8px; box-sizing: border-box;" placeholder="Minimal 6 karakter">
            </div>

            <div style="margin-bottom: 20px;">
                <label>Konfirmasi Password <span style="color: red;">*</span></label><br>
                <input type="password" name="confirm_password" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <button type="submit" name="register" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Daftar Sekarang
            </button>
            
            <p style="margin-top: 15px; font-size: 14px;">
                Sudah punya akun? <a href="login.php" style="color: #007bff; text-decoration: none;">Login di sini</a>
            </p>
        </form>
    </div>
</body>
</html>