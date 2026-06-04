<?php
include_once("config.php");
requireLogin();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $result = mysqli_query($conn, "SELECT foto FROM mahasiswa WHERE id=$id");
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $foto = $row["foto"];

        if (mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id")) {
            if ($foto) {
                deleteFile($foto);
            }
            $_SESSION['message'] = 'Data mahasiswa berhasil dihapus dari sistem!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus data: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = 'Data mahasiswa tidak ditemukan!';
    }
} else {
    $_SESSION['error'] = 'ID tidak valid atau tidak disertakan!';
}

header('Location: index.php');
exit();
?>