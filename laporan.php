<?php

$conn = mysqli_connect("localhost","root","","rental_mobil");

// Perbaikan query - menghitung total bayar dari harga_sewa dan jumlah_sewa
$query = mysqli_query($conn, "
SELECT 
    penyewaan.*,
    mobil.nama_mobil,
    mobil.harga_sewa
FROM penyewaan
JOIN mobil 
ON penyewaan.id_mobil = mobil.id_mobil
ORDER BY penyewaan.tanggal_sewa DESC
");

// Cek apakah query berhasil
if(!$query) {
    die("Query Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Penyewaan</title>

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

/* CONTAINER */
.container{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:10px;
}

.header h1{
    color:#0f172a;
    font-size:30px;
}

/* BUTTON */
.btn-dashboard{
    background:#0f172a;
    color:white;
    padding:12px 20px;
    border-radius:12px;
    text-decoration:none;
    transition:0.3s;
}

.btn-dashboard:hover{
    background:#1e293b;
}

.btn-print{
    background:#16a34a;
    color:white;
    padding:12px 20px;
    border-radius:12px;
    text-decoration:none;
    transition:0.3s;
}

.btn-print:hover{
    background:#15803d;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:15px;
}

table thead{
    background:linear-gradient(90deg,#2563eb,#1e40af);
    color:white;
}

table th{
    padding:18px;
    text-align:left;
}

table td{
    padding:18px;
    border-bottom:1px solid #e2e8f0;
}

table tbody tr{
    transition:0.3s;
}

table tbody tr:hover{
    background:#eff6ff;
}

/* BADGE */
.badge{
    padding:7px 14px;
    border-radius:20px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.aktif{
    background:#f59e0b;
}

.selesai{
    background:#16a34a;
}

/* TOTAL */
.total-box{
    margin-top:20px;
    text-align:right;
    font-size:20px;
    font-weight:bold;
    color:#0f172a;
}

/* PESAN KOSONG */
.empty-message{
    text-align:center;
    padding:40px;
    color:#64748b;
    font-size:16px;
}

@media(max-width:768px){

    table{
        font-size:13px;
    }

    table th,
    table td{
        padding:12px 10px;
    }

}

/* STYLE UNTUK PRINT */
@media print {

    body{
        background:white;
        padding:0;
    }

    .btn-dashboard,
    .btn-print{
        display:none;
    }

    .container{
        box-shadow:none;
        padding:0;
    }

    .badge{
        border:1px solid #ccc;
        color:black;
    }

    .aktif{
        background:#f59e0b;
        color:white;
    }

    .selesai{
        background:#16a34a;
        color:white;
    }

}

</style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">

        <h1>📄 Laporan Penyewaan Mobil</h1>

        <div style="display:flex; gap:10px;">

            <a href="dashboard_admin.php" class="btn-dashboard">
                ← Dashboard
            </a>

            <a href="#" onclick="window.print()" class="btn-print">
                🖨 Cetak
            </a>

        </div>

    </div>

    <!-- TABLE -->
    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>Nama Mobil</th>
                <th>Harga/Hari</th>
                <th>Jumlah Hari</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $no = 1;
        $grandTotal = 0;

        if(mysqli_num_rows($query) > 0) {

            while($data = mysqli_fetch_assoc($query)) :
                
                // Hitung total bayar dari harga_sewa * jumlah_sewa
                $totalBayar = ($data['harga_sewa'] ?? 0) * ($data['jumlah_sewa'] ?? 0);
                $grandTotal += $totalBayar;
        ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= htmlspecialchars($data['nama_user'] ?? '-'); ?></td>

                <td><?= htmlspecialchars($data['nama_mobil'] ?? '-'); ?></td>

                <td>
                    Rp <?= number_format($data['harga_sewa'] ?? 0,0,',','.'); ?>
                </td>

                <td><?= $data['jumlah_sewa'] ?? '-'; ?></td>

                <td><?= $data['tanggal_sewa'] ?? '-'; ?></td>

                <td><?= $data['tanggal_kembali'] ?? '-'; ?></td>

                <td>
                    Rp <?= number_format($totalBayar,0,',','.'); ?>
                </td>

                <td>

                    <?php if(isset($data['status']) && $data['status'] == 'Aktif') : ?>

                        <span class="badge aktif">
                            Aktif
                        </span>

                    <?php else : ?>

                        <span class="badge selesai">
                            Selesai
                        </span>

                    <?php endif; ?>

                </td>

            </tr>

        <?php 
            endwhile;
        } else { 
        ?>

            <tr>
                <td colspan="9" class="empty-message">
                    ⚠️ Belum ada data penyewaan
                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

    <!-- TOTAL -->
    <div class="total-box">

        Total Pendapatan :
        Rp <?= number_format($grandTotal,0,',','.'); ?>

    </div>

</div>

</body>
</html>