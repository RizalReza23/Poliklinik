<?php
if (!isset($_SESSION)) {
    session_start();
}

// Pastikan sudah terhubung ke database
include_once("koneksi.php");

// Ambil ID dokter dari sesi login
$id_dokter = $_SESSION['id'];

// Ambil data dokter berdasarkan ID
$sql = "SELECT nama, alamat, no_hp, nip, password FROM dokter WHERE id = '$id_dokter'";
$result = mysqli_query($mysqli, $sql);

// Jika data ditemukan, masukkan ke dalam variabel
if ($result && mysqli_num_rows($result) > 0) {
    $dokter = mysqli_fetch_assoc($result);
} else {
    die("Data dokter tidak ditemukan.");
}

// Update data dokter jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($mysqli, $_POST['nama']);
    $alamat = mysqli_real_escape_string($mysqli, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($mysqli, $_POST['no_hp']);
    $nip = mysqli_real_escape_string($mysqli, $_POST['nip']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Update data ke database
    $update_sql = "
        UPDATE dokter 
        SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp', nip = '$nip', password = '$password'
        WHERE id = '$id_dokter'
    ";

    if (mysqli_query($mysqli, $update_sql)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='berandaDokter.php?page=profildokter';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profil Dokter</title>
    <style>
        form {
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <main>
        <h1>Update Profil Dokter</h1>
        <form method="POST" action="berandaDokter.php?page=profildokter">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($dokter['nama']); ?>" required>

            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" required><?php echo htmlspecialchars($dokter['alamat']); ?></textarea>

            <label for="no_hp">No HP</label>
            <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($dokter['no_hp']); ?>" required>

            <label for="nip">NIP</label>
            <input type="text" id="nip" name="nip" value="<?php echo htmlspecialchars($dokter['nip']); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($dokter['password']); ?>" required>

            <button type="submit">Update Profil</button>
        </form>
    </main>
</body>
</html>
