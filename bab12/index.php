<?php
include_once("config.php");
requireLogin();

$limit = 5;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET["search"]) ? mysqli_real_escape_string($conn, $_GET["search"]) : "";
$where = "";

if (!empty($search)) {
    $where = "WHERE nim LIKE '%$search%'
              OR nama LIKE '%$search%'
              OR jurusan LIKE '%$search%'
              OR email LIKE '%$search%'";
}

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa $where");
$total_data = mysqli_fetch_assoc($count_result)["total"];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mahasiswa - Praktikum CRUD</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .header-status { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .action-bar { display: flex; justify-content: space-between; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .photo { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
        .no-photo { width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 12px; border-radius: 50%; color: #777; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn-primary { background-color: #28a745; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .pagination { margin-top: 15px; }
        .pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: black; margin-right: 5px; }
        .pagination a.current { background-color: #007bff; color: white; border-color: #007bff; }
        .alert { padding: 10px; margin-top: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="header-status">
        <div>
            Halo, <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong> (<?= htmlspecialchars($_SESSION['username']) ?>)
        </div>
        <div>
            <a href="logout.php" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
        </div>
    </div>

    <h2>Data Mahasiswa</h2>
    <hr>

    <div class="action-bar">
        <div>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Mahasiswa</a>
        </div>
        <div>
            <form action="index.php" method="GET">
                <input type="text" name="search" placeholder="Cari NIM, Nama, Jurusan..." value="<?= htmlspecialchars($search) ?>" style="padding: 6px; width: 200px;">
                <button type="submit" class="btn btn-secondary" style="padding: 6px 12px;">Cari</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="btn btn-secondary" style="padding: 6px 12px;">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php if ($row['foto']): ?>
                                <img src="uploads/mahasiswa/<?= $row["foto"] ?>" class="photo" alt="Foto">
                            <?php else: ?>
                                <div class="no-photo">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['jurusan']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $row["id"] ?>" class="btn btn-primary">Detail</a>    
                            <a href="edit.php?id=<?= $row["id"] ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?= $row["id"] ?>" onclick="return confirm('Yakin hapus data <?= htmlspecialchars($row['nama']) ?>?')" class="btn btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">Data tidak ditemukan atau masih kosong.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'current' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</body>
</html>