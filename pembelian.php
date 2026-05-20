<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah ada id mobil yang dipilih
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: sewa_mobil.php?error=" . urlencode("Pilih mobil terlebih dahulu"));
    exit;
}

$id_mobil = mysqli_real_escape_string($conn, $_GET['id']);
$queryMobil = mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = '$id_mobil'");
$mobil = mysqli_fetch_assoc($queryMobil);

if (!$mobil) {
    header("Location: sewa_mobil.php?error=" . urlencode("Mobil tidak ditemukan"));
    exit;
}

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $id_mobil = mysqli_real_escape_string($conn, $_POST['id_mobil']);
    $jumlah_sewa = mysqli_real_escape_string($conn, $_POST['jumlah_sewa']);
    $tanggal_sewa = mysqli_real_escape_string($conn, $_POST['tanggal_sewa']);
    $tanggal_kembali = mysqli_real_escape_string($conn, $_POST['tanggal_kembali']);
    
    // Validasi tanggal
    if ($tanggal_sewa > $tanggal_kembali) {
        $error = "Tanggal kembali harus setelah tanggal sewa";
    } else {
        // Cek apakah kolom tanggal_kembali ada di database
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM penyewaan LIKE 'tanggal_kembali'");
        $hasTanggalKembali = mysqli_num_rows($checkColumn) > 0;
        
        // Cek ketersediaan mobil (jika kolom tanggal_kembali ada)
        if ($hasTanggalKembali) {
            $queryCek = mysqli_query($conn, "SELECT * FROM penyewaan 
                                             WHERE id_mobil = '$id_mobil' 
                                             AND ((tanggal_sewa BETWEEN '$tanggal_sewa' AND '$tanggal_kembali')
                                             OR (tanggal_kembali BETWEEN '$tanggal_sewa' AND '$tanggal_kembali'))");
            
            if (mysqli_num_rows($queryCek) > 0) {
                $error = "Mobil sudah disewa pada tanggal tersebut";
            } else {
                // Insert ke database dengan tanggal_kembali
                $queryInsert = "INSERT INTO penyewaan (nama_user, id_mobil, jumlah_sewa, tanggal_sewa, tanggal_kembali) 
                                VALUES ('$nama_user', '$id_mobil', '$jumlah_sewa', '$tanggal_sewa', '$tanggal_kembali')";
                
                if (mysqli_query($conn, $queryInsert)) {
                    // Kurangi stok mobil
                    mysqli_query($conn, "UPDATE mobil SET jumlah = jumlah - 1 WHERE id_mobil = '$id_mobil'");
                    
                    header("Location: sewa_mobil.php?message=" . urlencode("Pemesanan berhasil! Terima kasih sudah menyewa."));
                    exit;
                } else {
                    $error = "Gagal memesan: " . mysqli_error($conn);
                }
            }
        } else {
            // Jika kolom tanggal_kembali tidak ada, insert tanpa tanggal_kembali
            $queryInsert = "INSERT INTO penyewaan (nama_user, id_mobil, jumlah_sewa, tanggal_sewa) 
                            VALUES ('$nama_user', '$id_mobil', '$jumlah_sewa', '$tanggal_sewa')";
            
            if (mysqli_query($conn, $queryInsert)) {
                // Kurangi stok mobil
                mysqli_query($conn, "UPDATE mobil SET jumlah = jumlah - 1 WHERE id_mobil = '$id_mobil'");
                
                header("Location: sewa_mobil.php?message=" . urlencode("Pemesanan berhasil! Terima kasih sudah menyewa."));
                exit;
            } else {
                $error = "Gagal memesan: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembelian - Rental Mobil</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f1f5f9;
    padding:50px;
}

.container{
    max-width:800px;
    margin:0 auto;
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.header{
    text-align:center;
    margin-bottom:30px;
}

.header h1{
    color:#0f172a;
    margin-bottom:10px;
}

.header p{
    color:#64748b;
}

.info-mobil{
    background:#f8fafc;
    padding:20px;
    border-radius:15px;
    margin-bottom:25px;
    border:1px solid #e2e8f0;
}

.info-mobil h3{
    color:#0f172a;
    margin-bottom:10px;
}

.info-mobil .harga{
    color:#2563eb;
    font-size:24px;
    font-weight:bold;
    margin:10px 0;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-weight:bold;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    font-size:16px;
}

.form-group input:focus{
    outline:none;
    border-color:#2563eb;
}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.btn-submit{
    width:100%;
    background:#2563eb;
    color:white;
    padding:14px;
    border:none;
    border-radius:10px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn-submit:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-back{
    display:inline-block;
    margin-top:15px;
    text-align:center;
    width:100%;
    color:#64748b;
    text-decoration:none;
}

.btn-back:hover{
    color:#0f172a;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    border:1px solid #fecaca;
}

@media(max-width:768px){
    body{
        padding:20px;
    }
    
    .form-row{
        grid-template-columns:1fr;
        gap:15px;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Form Pemesanan</h1>
        <p>Isi data berikut untuk menyewa mobil</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="error">
            ⚠️ <?= $error; ?>
        </div>
    <?php endif; ?>

    <div class="info-mobil">
        <h3>🚗 <?= htmlspecialchars($mobil['nama_mobil']); ?></h3>
        <div class="harga">Rp <?= number_format($mobil['harga_sewa'],0,',','.'); ?> / Hari</div>
        <div>Kondisi: <?= htmlspecialchars($mobil['kondisi']); ?></div>
        <div>Tersedia: <?= $mobil['jumlah']; ?> unit</div>
    </div>

    <form method="POST">
        <input type="hidden" name="id_mobil" value="<?= $mobil['id_mobil']; ?>">
        
        <div class="form-group">
            <label>Nama Penyewa</label>
            <input type="text" name="nama_user" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group">
            <label>Jumlah Hari Sewa</label>
            <input type="number" name="jumlah_sewa" required min="1" max="30" placeholder="Berapa hari?">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Sewa</label>
                <input type="date" name="tanggal_sewa" required>
            </div>

            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">Konfirmasi Pemesanan</button>
        <a href="dashboard_user.php" class="btn-back">← Kembali ke Daftar Mobil</a>
    </form>
</div>

<script>
// Set minimal tanggal sewa ke hari ini
const today = new Date().toISOString().split('T')[0];
const tanggalSewaInput = document.querySelector('input[name="tanggal_sewa"]');
const tanggalKembaliInput = document.querySelector('input[name="tanggal_kembali"]');

tanggalSewaInput.min = today;
tanggalKembaliInput.min = today;

// Update minimal tanggal kembali berdasarkan tanggal sewa
tanggalSewaInput.addEventListener('change', function() {
    tanggalKembaliInput.min = this.value;
    if (tanggalKembaliInput.value < this.value) {
        tanggalKembaliInput.value = this.value;
    }
});
</script>
</body>
</html>