<?php
session_start();
$conn = mysqli_connect("localhost","root","","rental_mobil");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = mysqli_query($conn,"SELECT * FROM mobil ORDER BY nama_mobil ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sewa Mobil</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f1f5f9;
}

/* HEADER */
.header{
    background:#0f172a;
    padding:20px 40px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
}

.logo{
    font-size:28px;
    font-weight:bold;
}

.menu{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.menu a{
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.menu a:hover{
    color:#60a5fa;
}

/* HERO */
.hero{
    background:linear-gradient(rgba(15,23,42,0.7),rgba(15,23,42,0.7)),
    url('https://images.unsplash.com/photo-1503376780353-7e6692767b70');
    background-size:cover;
    background-position:center;
    height:350px;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
    color:white;
    padding:20px;
}

.hero-content h1{
    font-size:50px;
    margin-bottom:15px;
}

.hero-content p{
    font-size:18px;
}

/* CONTAINER */
.container{
    padding:50px;
    max-width:1400px;
    margin:0 auto;
}

/* TITLE */
.title{
    text-align:center;
    margin-bottom:40px;
}

.title h2{
    font-size:35px;
    color:#0f172a;
}

/* CARD */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:30px;
}

.card{
    background:white;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 35px rgba(0,0,0,0.15);
}

.card img{
    width:100%;
    height:200px;
    object-fit:cover;
    background:#e2e8f0;
}

.card-body{
    padding:20px;
}

.card-body h3{
    margin-bottom:10px;
    color:#0f172a;
    font-size:20px;
}

.harga{
    color:#2563eb;
    font-size:24px;
    font-weight:bold;
    margin-bottom:10px;
}

.info{
    color:#64748b;
    margin-bottom:8px;
    font-size:14px;
}

.status{
    display:inline-block;
    padding:7px 14px;
    border-radius:20px;
    color:white;
    font-size:13px;
    margin-bottom:15px;
}

.tersedia{
    background:#16a34a;
}

.kosong{
    background:#ef4444;
}

/* BUTTON */
.btn{
    display:block;
    width:100%;
    text-align:center;
    background:#2563eb;
    color:white;
    padding:12px;
    border-radius:12px;
    text-decoration:none;
    transition:0.3s;
    border:none;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
}

.btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

/* FOOTER */
.footer{
    margin-top:50px;
    background:#0f172a;
    color:white;
    text-align:center;
    padding:20px;
}

/* ALERT */
.alert{
    padding:15px 20px;
    border-radius:10px;
    margin-bottom:20px;
    display:none;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
    border:1px solid #86efac;
}

.alert-danger{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
}

@media(max-width:768px){
    .header{
        flex-direction:column;
        text-align:center;
    }

    .hero-content h1{
        font-size:35px;
    }

    .container{
        padding:20px;
    }
    
    .cards{
        gap:20px;
    }
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="logo">
        🚗 Alfian Car Rental
    </div>
    <div class="menu">
        <a href="dashboard_user.php">Home</a>
        <a href="dashboard_user.php#mobil">Mobil</a>
        <a href="riwayat_sewa.php">Riwayat</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h1>Sewa Mobil Mudah & Cepat</h1>
        <p>Pilih mobil terbaik untuk perjalanan nyaman Anda</p>
    </div>
</div>

<!-- CONTENT -->
<div class="container" id="mobil">
    <div class="title">
        <h2>Daftar Mobil</h2>
    </div>

    <!-- Alert Notification -->
    <div id="alertMessage" class="alert"></div>

    <div class="cards">
    <?php 
    if(mysqli_num_rows($query) > 0) {
        while($data = mysqli_fetch_assoc($query)) : 
            // Default image jika tidak ada gambar
            $imageUrl = !empty($data['gambar']) ? $data['gambar'] : 'https://placehold.co/400x300?text=Mobil';
    ?>
        <div class="card">
            <img src="<?= $imageUrl; ?>" alt="<?= $data['nama_mobil']; ?>">
            <div class="card-body">
                <h3><?= htmlspecialchars($data['nama_mobil']); ?></h3>
                <div class="harga">
                    Rp <?= number_format($data['harga_sewa'],0,',','.'); ?><span style="font-size:14px;">/Hari</span>
                </div>
                <div class="info">
                    📊 Jumlah : <?= $data['jumlah']; ?>
                </div>
                <div class="info">
                    🔧 Kondisi : <?= htmlspecialchars($data['kondisi']); ?>
                </div>
                <?php if($data['jumlah'] > 0) : ?>
                    <span class="status tersedia">✓ Tersedia</span>
                    <a href="pembelian.php?id=<?= $data['id_mobil']; ?>" class="btn">
                        Sewa Sekarang
                    </a>
                <?php else : ?>
                    <span class="status kosong">✗ Tidak Tersedia</span>
                    <button class="btn" style="background:#94a3b8; cursor:not-allowed;" disabled>
                        Stok Habis
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php 
        endwhile;
    } else {
    ?>
        <div style="text-align:center; padding:50px; grid-column:1/-1;">
            <p>Belum ada mobil tersedia. Silakan cek kembali nanti.</p>
        </div>
    <?php } ?>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 Rental Mobil | Semua Hak Dilindungi
</div>

<script>
// Cek alert dari URL parameter
const urlParams = new URLSearchParams(window.location.search);
const message = urlParams.get('message');
const error = urlParams.get('error');

if(message) {
    const alertDiv = document.getElementById('alertMessage');
    alertDiv.textContent = decodeURIComponent(message);
    alertDiv.className = 'alert alert-success';
    alertDiv.style.display = 'block';
    
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 3000);
}

if(error) {
    const alertDiv = document.getElementById('alertMessage');
    alertDiv.textContent = decodeURIComponent(error);
    alertDiv.className = 'alert alert-danger';
    alertDiv.style.display = 'block';
    
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 3000);
}
</script>
</body>
</html>