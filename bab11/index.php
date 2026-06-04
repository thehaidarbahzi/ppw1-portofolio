<!-- uat file PHP yang menampilkan profil dirimu (nama, NIM, prodi, asal kota) dalam
tabel HTML yang rapi menggunakan variabel PHP.
2. Buat fungsi hitungIMT($berat, $tinggi) yang menghitung Indeks Massa Tubuh dan
mengembalikan kategorinya ('Kurus', 'Normal', 'Gemuk', 'Obesitas'). Tampilkan
hasilnya di halaman HTML.
3. Buat halaman yang menampilkan nama bulan sekarang dan berapa hari tersisa di
bulan ini menggunakan fungsi date(). -->

<?php
$nama = "Haidar Bahzi";
$nim = "25/560342/SV/26428";
$prodi = "Teknologi Rekayasa Perangkat Lunak";
$kota = "Kudus";

function hitungIMT($berat, $tinggi) {
    if ($tinggi <= 0) return "Tinggi tidak valid";
    $bmi = $berat / ($tinggi * $tinggi);
    $bmi = round($bmi, 2);
    if ($bmi < 18.5) return ["Kurus", $bmi];
    elseif ($bmi < 25) return ["Normal", $bmi];
    elseif ($bmi < 30) return ["Gemuk", $bmi];
    else return ["Obesitas", $bmi];
}

$bmiHasil = "";
$bmiKategori = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $berat = floatval($_POST["inputBeratBadan"]);
    $tinggi = floatval($_POST["inputTinggiBadan"]) / 100;
    list($bmiKategori, $bmiHasil) = hitungIMT($berat, $tinggi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas PPW1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
  <h1>Profil Mahasiswa</h1>
  <table class="table table-bordered w-50">
    <thead class="table-light">
      <tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>Prodi</th>
        <th>Asal Kota</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $nama; ?></td>
        <td><?= $nim; ?></td>
        <td><?= $prodi; ?></td>
        <td><?= $kota; ?></td>
      </tr>
    </tbody>
  </table>

  <h2>Hitung BMI</h2>
  <form method="POST" class="w-50">
    <div class="mb-3">
      <label for="inputTinggiBadan" class="form-label">Tinggi Badan (cm)</label>
      <input type="number" step="0.1" required class="form-control" id="inputTinggiBadan" name="inputTinggiBadan">
    </div>
    <div class="mb-3">
      <label for="inputBeratBadan" class="form-label">Berat Badan (kg)</label>
      <input type="number" step="0.1" required class="form-control" id="inputBeratBadan" name="inputBeratBadan">
    </div>
    <button type="submit" class="btn btn-primary">Hitung BMI</button>
  </form>

  <?php if ($bmiHasil): ?>
      <h3>Hasil BMI: <?= $bmiHasil; ?> (<?= $bmiKategori; ?>)</h3>
  <?php endif; ?>

<h1>Bulan <?php echo date("F");?> tersisa <?php echo date("t")-date("d");?> hari lagi</h1>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>