<?php
$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat folder uploads jika belum ada
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mobil = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    
    // Proses upload gambar
    $gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . "_" . preg_replace('/[^a-zA-Z0-9]/', '_', $nama_mobil) . "." . $ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $destination)) {
                $gambar = $destination;
            } else {
                $error = "Gagal upload gambar";
            }
        } else {
            $error = "Format gambar tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP";
        }
    }
    
    if (empty($error)) {
        $query = "INSERT INTO mobil (nama_mobil, jumlah, kondisi, harga_sewa, gambar) 
                  VALUES ('$nama_mobil', '$jumlah', '$kondisi', '$harga_sewa', '$gambar')";
        
        if (mysqli_query($conn, $query)) {
            $message = "Mobil berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan: " . mysqli_error($conn);
        }
    }
}

// Ambil daftar mobil
$queryMobil = mysqli_query($conn, "SELECT * FROM mobil ORDER BY id_mobil DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Mobil</title>
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
    max-width:1200px;
    margin:0 auto;
}

.form-container{
    background:white;
    border-radius:20px;
    padding:30px;
    margin-bottom:30px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#334155;
}

.form-group input, .form-group select{
    width:100%;
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    font-size:16px;
}

.form-row{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.btn-submit{
    background:#2563eb;
    color:white;
    padding:12px 24px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
}

.btn-submit:hover{
    background:#1d4ed8;
}

.message{
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
}

.success{
    background:#dcfce7;
    color:#166534;
    border:1px solid #86efac;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
}

table{
    width:100%;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
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

table img{
    width:80px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}

@media(max-width:768px){
    body{
        padding:15px;
    }
    
    .form-container{
        padding:20px;
    }
}
</style>
</head>
<body>

<div class="container">
    <h1 style="margin-bottom:20px;">➕ Tambah Mobil Baru</h1>
    
    <div class="form-container">
        <?php if($message): ?>
            <div class="message success">✓ <?= $message; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="message error">⚠️ <?= $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Mobil</label>
                    <input type="text" name="nama_mobil" required placeholder="Contoh: Toyota Avanza">
                </div>
                
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" required min="0">
                </div>
                
                <div class="form-group">
                    <label>Kondisi</label>
                    <select name="kondisi" required>
                        <option value="Baru">Baru</option>
                        <option value="Bekas">Bekas</option>
                        <option value="Mulus">Mulus</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Harga Sewa/Hari</label>
                    <input type="number" name="harga_sewa" required min="0">
                </div>
                
                <div class="form-group">
                    <label>Gambar Mobil</label>
                    <input type="file" name="gambar" accept="image/*">
                    <small style="color:#64748b;">Format: JPG, PNG, GIF, WEBP (Max 5MB)</small>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Simpan Mobil</button>
        </form>
    </div>
    
    <h2 style="margin:30px 0 20px;">📋 Daftar Mobil</h2>
    
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama Mobil</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while($mobil = mysqli_fetch_assoc($queryMobil)): ?>
                <tr>
                    <td><?= $mobil['id_mobil']; ?></td>
                    <td>
                        <?php if(!empty($mobil['gambar']) && file_exists($mobil['gambar'])): ?>
                            <img src="<?= $mobil['gambar']; ?>" alt="<?= $mobil['nama_mobil']; ?>">
                        <?php else: ?>
                            <img src="https://placehold.co/80x60/64748b/white?text=No+Image" alt="No Image">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($mobil['nama_mobil']); ?></td>
                    <td><?= $mobil['jumlah']; ?></td>
                    <td><?= htmlspecialchars($mobil['kondisi']); ?></td>
                    <td>Rp <?= number_format($mobil['harga_sewa'],0,',','.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>