<?php
$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = mysqli_query($conn, "
SELECT 
    penyewaan.*,
    mobil.nama_mobil,
    mobil.harga_sewa
FROM penyewaan
JOIN mobil ON penyewaan.id_mobil = mobil.id_mobil
ORDER BY penyewaan.tanggal_sewa DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Sewa</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f1f5f9;
    padding:30px;
}

.container{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.header h1{
    color:#0f172a;
}

.btn-back{
    background:#0f172a;
    color:white;
    padding:12px 20px;
    border-radius:12px;
    text-decoration:none;
    transition:0.3s;
}

.btn-back:hover{
    background:#1e293b;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2563eb;
    color:white;
    padding:15px;
    text-align:left;
}

table td{
    padding:15px;
    border-bottom:1px solid #e2e8f0;
}

table tr:hover{
    background:#eff6ff;
}

@media(max-width:768px){
    body{
        padding:15px;
    }
    
    .table-wrapper{
        overflow-x:auto;
    }
    
    table{
        min-width:600px;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📜 Riwayat Penyewaan</h1>
        <a href="sewa_mobil.php" class="btn-back">← Kembali</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penyewa</th>
                    <th>Mobil</th>
                    <th>Jumlah Hari</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if(mysqli_num_rows($query) > 0) {
                    while($data = mysqli_fetch_assoc($query)) :
                        $totalBayar = ($data['harga_sewa'] ?? 0) * ($data['jumlah_sewa'] ?? 0);
                        $tanggal_sewa = date('d/m/Y', strtotime($data['tanggal_sewa']));
                        $tanggal_kembali = date('d/m/Y', strtotime($data['tanggal_kembali']));
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama_user']); ?></td>
                    <td><?= htmlspecialchars($data['nama_mobil']); ?></td>
                    <td><?= $data['jumlah_sewa']; ?> hari</td>
                    <td><?= $tanggal_sewa; ?></td>
                    <td><?= $tanggal_kembali; ?></td>
                    <td>Rp <?= number_format($totalBayar,0,',','.'); ?></td>
                </tr>
                <?php 
                    endwhile;
                } else {
                ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px;">
                        Belum ada riwayat penyewaan
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>