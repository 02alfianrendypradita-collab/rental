<?php
$conn = mysqli_connect("localhost","root","","rental_mobil");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/* TOTAL MOBIL */
$queryMobil = mysqli_query($conn, "SELECT * FROM mobil");
$totalMobil = mysqli_num_rows($queryMobil);

/* MOBIL DISEWA */
$queryDisewa = mysqli_query($conn, "SELECT * FROM mobil WHERE jumlah = 0");
$mobilDisewa = mysqli_num_rows($queryDisewa);

/* PENYEWA AKTIF - Perbaikan: tanpa WHERE status */
$queryPenyewa = mysqli_query($conn, "SELECT * FROM penyewaan");
$penyewaAktif = mysqli_num_rows($queryPenyewa);

/* TOTAL PENDAPATAN */
$queryPendapatan = mysqli_query($conn, "SELECT SUM(harga_sewa) AS total FROM mobil");
$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);
$totalPendapatan = $dataPendapatan['total'] ?? 0;

// Proses Edit
if (isset($_POST['update'])) {
    $id_mobil = $_POST['id_mobil'];
    $nama_mobil = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    
    $update = mysqli_query($conn, "UPDATE mobil SET 
        nama_mobil = '$nama_mobil',
        jumlah = '$jumlah',
        kondisi = '$kondisi',
        harga_sewa = '$harga_sewa'
        WHERE id_mobil = '$id_mobil'");
    
    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='dashboard_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($conn) . "');</script>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id_mobil = $_GET['hapus'];
    
    // Cek apakah mobil sedang disewa - Perbaikan: cek berdasarkan id_mobil di tabel penyewaan
    $cek_sewa = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id_mobil = '$id_mobil'");
    
    if (mysqli_num_rows($cek_sewa) > 0) {
        echo "<script>alert('Mobil sedang dalam penyewaan, tidak bisa dihapus!');</script>";
    } else {
        $hapus = mysqli_query($conn, "DELETE FROM mobil WHERE id_mobil = '$id_mobil'");
        if ($hapus) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!');</script>";
        }
    }
}

// Proses Tambah
if (isset($_POST['tambah'])) {
    $nama_mobil = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    
    $tambah = mysqli_query($conn, "INSERT INTO mobil (nama_mobil, jumlah, kondisi, harga_sewa) 
                                   VALUES ('$nama_mobil', '$jumlah', '$kondisi', '$harga_sewa')");
    
    if ($tambah) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='dashboard_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}

// Ambil data untuk edit (jika ada parameter edit)
$data_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $query_edit = mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = '$id_edit'");
    $data_edit = mysqli_fetch_assoc($query_edit);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin Rental Mobil</title>

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family: Arial, sans-serif;
    }

    body{
      display:flex;
      background:#f1f5f9;
      min-height:100vh;
    }

    /* SIDEBAR */
    .sidebar{
      width:250px;
      background:#0f172a;
      color:white;
      padding:20px;
      min-height:100vh;
    }

    .sidebar h2{
      text-align:center;
      margin-bottom:40px;
      font-size:24px;
    }

    .menu{
      list-style:none;
    }

    .menu li{
      margin:18px 0;
    }

    .menu a{
      text-decoration:none;
      color:white;
      padding:12px;
      display:block;
      border-radius:8px;
      transition:0.3s;
    }

    .menu a:hover{
      background:#1e293b;
    }

    /* MAIN */
    .main{
      flex:1;
      padding:25px;
    }

    .header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:30px;
    }

    .header h1{
      color:#0f172a;
    }

    .admin-box{
      background:white;
      padding:10px 18px;
      border-radius:10px;
      box-shadow:0 2px 8px rgba(0,0,0,0.1);
    }

    /* CARD */
    .cards{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:20px;
      margin-bottom:30px;
    }

    .card{
      background:white;
      padding:25px;
      border-radius:15px;
      box-shadow:0 4px 10px rgba(0,0,0,0.08);
      transition:transform 0.3s;
    }

    .card:hover{
      transform:translateY(-5px);
    }

    .card h3{
      margin-bottom:10px;
      color:#334155;
      font-size:16px;
    }

    .card p{
      font-size:28px;
      font-weight:bold;
      color:#2563eb;
    }

    /* TABLE */
    .table-container{
      background:white;
      padding:20px;
      border-radius:15px;
      box-shadow:0 4px 10px rgba(0,0,0,0.08);
      margin-bottom:20px;
    }

    .table-container h2{
      margin-bottom:20px;
      color:#0f172a;
    }

    table{
      width:100%;
      border-collapse:collapse;
    }

    table th,
    table td{
      padding:14px;
      text-align:left;
      border-bottom:1px solid #e2e8f0;
    }

    table th{
      background:#2563eb;
      color:white;
    }

    table tr:hover{
      background:#f8fafc;
    }

    .status{
      padding:6px 12px;
      border-radius:20px;
      font-size:12px;
      color:white;
      display:inline-block;
    }

    .tersedia{
      background:#16a34a;
    }

    .disewa{
      background:#dc2626;
    }

    /* BUTTON */
    .btn{
      padding:8px 14px;
      border:none;
      border-radius:8px;
      cursor:pointer;
      color:white;
      margin:0 3px;
      text-decoration:none;
      display:inline-block;
      font-size:13px;
    }

    .edit{
      background:#f59e0b;
    }

    .edit:hover{
      background:#d97706;
    }

    .hapus{
      background:#ef4444;
    }

    .hapus:hover{
      background:#dc2626;
    }

    .tambah{
      background:#2563eb;
      margin-bottom:15px;
    }

    .btn:hover{
      opacity:0.9;
    }

    /* FORM */
    .form-container{
      background:white;
      padding:20px;
      border-radius:15px;
      box-shadow:0 4px 10px rgba(0,0,0,0.08);
      margin-bottom:20px;
    }

    .form-group{
      margin-bottom:15px;
    }

    .form-group label{
      display:block;
      margin-bottom:5px;
      color:#334155;
      font-weight:bold;
    }

    .form-group input,
    .form-group select{
      width:100%;
      padding:10px;
      border:1px solid #cbd5e1;
      border-radius:8px;
      font-size:14px;
    }

    .form-group input:focus,
    .form-group select:focus{
      outline:none;
      border-color:#2563eb;
    }

    .form-row{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
      gap:15px;
    }

    .btn-submit{
      background:#2563eb;
      color:white;
      padding:10px 20px;
      border:none;
      border-radius:8px;
      cursor:pointer;
      font-size:16px;
    }

    .btn-submit:hover{
      background:#1e40af;
    }

    .btn-cancel{
      background:#64748b;
      color:white;
      padding:10px 20px;
      border:none;
      border-radius:8px;
      cursor:pointer;
      text-decoration:none;
      display:inline-block;
    }

    .btn-cancel:hover{
      background:#475569;
    }

    .btn-group{
      display:flex;
      gap:10px;
      margin-top:10px;
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <h2>🚗 Rental Admin</h2>

    <ul class="menu">
      <li><a href="dashboard_admin.php">🏠 Dashboard</a></li>
      <li><a href="kelola_mobil.php">🚘 Kelola Mobil</a></li>
      <li><a href="data_penyewa.php">👥 Data Penyewa</a></li>
      <li><a href="laporan.php">📄 Laporan</a></li>
      <li><a href="pengaturan.php">⚙ Pengaturan</a></li>
      <li><a href="login_admin.php">🚪 Logout</a></li>
    </ul>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main">

    <!-- HEADER -->
    <div class="header">
      <h1>Dashboard Admin</h1>
      <div class="admin-box">Admin</div>
    </div>

    <!-- CARDS -->
    <div class="cards">
      <div class="card">
        <h3>Total Mobil</h3>
        <p><?= $totalMobil; ?></p>
      </div>

      <div class="card">
        <h3>Mobil Disewa</h3>
        <p><?= $mobilDisewa; ?></p>
      </div>

      <div class="card">
        <h3>Total Penyewaan</h3>
        <p><?= $penyewaAktif; ?></p>
      </div>

      <div class="card">
        <h3>Total Pendapatan</h3>
        <p>Rp <?= number_format($totalPendapatan,0,',','.'); ?></p>
      </div>
    </div>

    <!-- FORM TAMBAH / EDIT MOBIL -->
    <div class="form-container">
      <h2 style="margin-bottom:20px;"><?= isset($data_edit) ? '✏️ Edit Mobil' : '➕ Tambah Mobil Baru'; ?></h2>
      
      <form method="POST">
        <?php if(isset($data_edit)): ?>
          <input type="hidden" name="id_mobil" value="<?= $data_edit['id_mobil']; ?>">
        <?php endif; ?>
        
        <div class="form-row">
          <div class="form-group">
            <label>Nama Mobil</label>
            <input type="text" name="nama_mobil" required value="<?= isset($data_edit) ? htmlspecialchars($data_edit['nama_mobil']) : ''; ?>" placeholder="Contoh: Toyota Avanza">
          </div>

          <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" required value="<?= isset($data_edit) ? $data_edit['jumlah'] : ''; ?>" min="0">
          </div>

          <div class="form-group">
            <label>Kondisi</label>
            <select name="kondisi" required>
              <option value="">Pilih Kondisi</option>
              <option value="Baru" <?= (isset($data_edit) && $data_edit['kondisi'] == 'Baru') ? 'selected' : ''; ?>>Baru</option>
              <option value="Bekas" <?= (isset($data_edit) && $data_edit['kondisi'] == 'Bekas') ? 'selected' : ''; ?>>Bekas</option>
              <option value="Mulus" <?= (isset($data_edit) && $data_edit['kondisi'] == 'Mulus') ? 'selected' : ''; ?>>Mulus</option>
            </select>
          </div>

          <div class="form-group">
            <label>Harga Sewa / Hari</label>
            <input type="number" name="harga_sewa" required value="<?= isset($data_edit) ? $data_edit['harga_sewa'] : ''; ?>" min="0">
          </div>
        </div>

        <div class="btn-group">
          <?php if(isset($data_edit)): ?>
            <button type="submit" name="update" class="btn-submit">Update Data</button>
            <a href="dashboard_admin.php" class="btn-cancel">Batal</a>
          <?php else: ?>
            <button type="submit" name="tambah" class="btn-submit">Tambah Mobil</button>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- TABLE DATA MOBIL -->
    <div class="table-container">
      <h2>📋 Data Mobil</h2>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Mobil</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Harga Sewa/Hari</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $query = mysqli_query($conn, "SELECT * FROM mobil ORDER BY id_mobil DESC");
          $no = 1;
          while($data = mysqli_fetch_assoc($query)) :
          ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nama_mobil']); ?></td>
            <td><?= $data['jumlah']; ?></td>
            <td><?= htmlspecialchars($data['kondisi']); ?></td>
            <td>Rp <?= number_format($data['harga_sewa'],0,',','.'); ?></td>
            <td>
              <?php if($data['jumlah'] > 0) : ?>
                <span class="status tersedia">✓ Tersedia</span>
              <?php else : ?>
                <span class="status disewa">✗ Disewa</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="?edit=<?= $data['id_mobil']; ?>" class="btn edit">Edit</a>
              <a href="?hapus=<?= $data['id_mobil']; ?>" class="btn hapus" onclick="return confirm('Yakin ingin menghapus mobil ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
          
          <?php if(mysqli_num_rows($query) == 0): ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:40px; color:#64748b;">
              📭 Belum ada data mobil. Silakan tambah mobil baru!
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>