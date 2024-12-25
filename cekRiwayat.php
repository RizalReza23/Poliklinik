<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $no_rm = $_POST['no_rm'];
        $alamat = $_POST['alamat']; // Ambil alamat dari input form

        $query = "SELECT * FROM pasien WHERE no_rm = '$no_rm' AND alamat = '$alamat'";
        $result = $mysqli->query($query);

        if (!$result) {
            die("Query error: " . $mysqli->error);
        }

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['nama'] = $row['nama']; // Perbaikan: Ambil 'nama' dari hasil query
            $_SESSION['id_pasien'] = $row['id'];
            header("Location: index.php?page=cekRiwayat&no_rm=$no_rm");
        } else {
            $error = "No. Rekam Medis atau Alamat tidak ditemukan";
        }
    }
?>

<main id="riwayat-periksa-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row">
            <h2 class="ps-0">Riwayat Periksa Pasien</h2>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>No. RM</th>
                            <th>Keluhan</th>
                            <th>Hari</th>
                            <th>Tanggal Diperiksa</th>
                            <th>Catatan Dokter</th>
                            <th>Biaya Periksa</th>
                            <th>Nama Obat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil ID pasien dari sesi login
                        $id_pasien = $_SESSION['id_pasien'];

                        // Query untuk mendapatkan data riwayat periksa pasien
                        $query = "
                            SELECT 
                                pasien.nama, pasien.alamat, pasien.no_hp, pasien.no_rm, 
                                daftar_poli.keluhan, jadwal_periksa.hari, 
                                periksa.tgl_periksa, periksa.catatan, periksa.biaya_periksa, 
                                GROUP_CONCAT(obat.nama_obat SEPARATOR ', ') AS nama_obat
                            FROM pasien
                            JOIN daftar_poli ON pasien.id = daftar_poli.id_pasien
                            JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                            LEFT JOIN periksa ON daftar_poli.id = periksa.id_daftar_poli
                            LEFT JOIN detail_periksa ON periksa.id = detail_periksa.id_periksa
                            LEFT JOIN obat ON detail_periksa.id_obat = obat.id
                            WHERE pasien.id = '$id_pasien'
                            GROUP BY 
                                pasien.nama, pasien.alamat, pasien.no_hp, pasien.no_rm, 
                                daftar_poli.keluhan, jadwal_periksa.hari, 
                                periksa.tgl_periksa, periksa.catatan, periksa.biaya_periksa
                        ";
                        
                        $result = mysqli_query($mysqli, $query);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($data = mysqli_fetch_assoc($result)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($data['no_hp']); ?></td>
                                    <td><?php echo htmlspecialchars($data['no_rm']); ?></td>
                                    <td><?php echo htmlspecialchars($data['keluhan']); ?></td>
                                    <td><?php echo htmlspecialchars($data['hari']); ?></td>
                                    <td><?php echo htmlspecialchars($data['tgl_periksa']); ?></td>
                                    <td><?php echo htmlspecialchars($data['catatan']); ?></td>
                                    <td><?php echo htmlspecialchars($data['biaya_periksa']); ?></td>
                                    <td><?php echo htmlspecialchars($data['nama_obat']); ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="11">Tidak ada data riwayat periksa.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
