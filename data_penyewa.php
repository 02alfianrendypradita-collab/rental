<?php
$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses Hapus Data Penyewaan - Menggunakan kombinasi nama_user, id_mobil, tanggal_sewa
if (isset($_GET['hapus']) && isset($_GET['nama']) && isset($_GET['mobil']) && isset($_GET['tgl'])) {
    $nama_user = $_GET['nama'];
    $id_mobil = $_GET['mobil'];
    $tanggal_sewa = $_GET['tgl'];
    
    // Hapus data penyewaan
    $hapus = mysqli_query($conn, "DELETE FROM penyewaan WHERE nama_user = '$nama_user' AND id_mobil = '$id_mobil' AND tanggal_sewa = '$tanggal_sewa'");
    
    if ($hapus) {
        echo "<script>alert('Data penyewaan berhasil dihapus!'); window.location.href='data_penyewa.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "');</script>";
    }
}

// Proses Update Status - Menggunakan kombinasi nama_user, id_mobil, tanggal_sewa
if (isset($_GET['status']) && isset($_GET['nama']) && isset($_GET['mobil']) && isset($_GET['tgl'])) {
    $nama_user = $_GET['nama'];
    $id_mobil = $_GET['mobil'];
    $tanggal_sewa = $_GET['tgl'];
    $status = $_GET['status'];
    
    $update = mysqli_query($conn, "UPDATE penyewaan SET status = '$status' WHERE nama_user = '$nama_user' AND id_mobil = '$id_mobil' AND tanggal_sewa = '$tanggal_sewa'");
    
    if ($update) {
        $message = ($status == 'Selesai') ? 'Penyewaan sudah selesai!' : 'Status diubah menjadi Aktif';
        echo "<script>alert('$message'); window.location.href='data_penyewa.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate status!');</script>";
    }
}

// Query ambil data penyewaan
$query = mysqli_query($conn, "
SELECT 
    penyewaan.*,
    mobil.nama_mobil,
    mobil.harga_sewa,
    mobil.id_mobil as mobil_id
FROM penyewaan
JOIN mobil 
ON penyewaan.id_mobil = mobil.id_mobil
ORDER BY penyewaan.tanggal_sewa DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Penyewaan</title>

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
    gap:15px;
}

.header h1{
    color:#0f172a;
    font-size:30px;
}

/* BUTTON */
.btn-tambah{
    background:#2563eb;
    color:white;
    padding:12px 20px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    font-size:15px;
    transition:0.3s;
    text-decoration:none;
    display:inline-block;
}

.btn-tambah:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-kembali{
    background:#f1f5f9;
    color:#475569;
    padding:12px 20px;
    border:2px solid #e2e8f0;
    border-radius:12px;
    cursor:pointer;
    font-size:15px;
    text-decoration:none;
    transition:0.3s;
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-weight:600;
}

.btn-kembali:hover{
    background:#e2e8f0;
    border-color:#cbd5e1;
    transform:translateY(-2px);
}

/* TABLE */
.table-wrapper{
    overflow-x: auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:800px;
}

table thead{
    background:linear-gradient(90deg,#2563eb,#1e40af);
    color:white;
}

table th{
    padding:18px;
    text-align:left;
    font-size:15px;
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
    display:inline-block;
}

.selesai{
    background:#16a34a;
}

.aktif{
    background:#f59e0b;
}

/* BUTTON ACTION */
.btn{
    border:none;
    padding:8px 12px;
    border-radius:8px;
    color:white;
    cursor:pointer;
    margin-right:5px;
    transition:0.3s;
    font-size:13px;
    text-decoration:none;
    display:inline-block;
}

.edit{
    background:#f59e0b;
}

.hapus{
    background:#ef4444;
}

.status-btn{
    background:#2563eb;
}

.btn:hover{
    transform:translateY(-2px);
    opacity:0.9;
}

/* MODAL EDIT */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 25px;
    width: 90%;
    max-width: 500px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.modal-header h3 {
    color: #0f172a;
}

.close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #64748b;
}

.close:hover {
    color: #0f172a;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #334155;
    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #2563eb;
}

.btn-submit {
    background: #2563eb;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

.btn-submit:hover {
    background: #1e40af;
}

/* RESPONSIVE */
@media(max-width:768px){
    body{
        padding:15px;
    }
    
    .header{
        flex-direction:column;
        align-items:flex-start;
    }
    
    table th,
    table td{
        padding:12px 10px;
        font-size:13px;
    }
    
    .btn{
        padding:6px 10px;
        font-size:11px;
    }
}
</style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>📋 Data Penyewaan Mobil</h1>

        <div style="display:flex; gap:10px;">
            <a href="dashboard_admin.php" class="btn-kembali">
                ← Dashboard
            </a>
            <a href="tambah_penyewa.php" class="btn-tambah">
                + Tambah Penyewaan
            </a>
        </div>
    </div>

    <div class="table-wrapper">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if(mysqli_num_rows($query) > 0) {
                while($data = mysqli_fetch_assoc($query)) :
                    // Menggunakan isset untuk menghindari undefined array key
                    $nama_user = isset($data['nama_user']) ? htmlspecialchars($data['nama_user']) : '-';
                    $nama_mobil = isset($data['nama_mobil']) ? htmlspecialchars($data['nama_mobil']) : '-';
                    $harga_sewa = isset($data['harga_sewa']) ? $data['harga_sewa'] : 0;
                    $jumlah_sewa = isset($data['jumlah_sewa']) ? $data['jumlah_sewa'] : 0;
                    $totalBayar = $harga_sewa * $jumlah_sewa;
                    $status = isset($data['status']) ? $data['status'] : 'Aktif';
                    $id_mobil = isset($data['id_mobil']) ? $data['id_mobil'] : 0;
                    $tanggal_sewa_raw = isset($data['tanggal_sewa']) ? $data['tanggal_sewa'] : '';
                    
                    // Format tanggal dengan pengecekan
                    $tanggal_sewa = !empty($tanggal_sewa_raw) && $tanggal_sewa_raw != '0000-00-00' 
                        ? date('d/m/Y', strtotime($tanggal_sewa_raw)) 
                        : '-';
                    $tanggal_kembali = isset($data['tanggal_kembali']) && !empty($data['tanggal_kembali']) && $data['tanggal_kembali'] != '0000-00-00' 
                        ? date('d/m/Y', strtotime($data['tanggal_kembali'])) 
                        : '-';
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $nama_user; ?></td>
                    <td><?= $nama_mobil; ?></td>
                    <td>Rp <?= number_format($harga_sewa,0,',','.'); ?></td>
                    <td><?= $jumlah_sewa; ?> hari</td>
                    <td><?= $tanggal_sewa; ?></td>
                    <td><?= $tanggal_kembali; ?></td>
                    <td>Rp <?= number_format($totalBayar,0,',','.'); ?></td>
                    <td>
                        <span class="badge <?= ($status == 'Aktif') ? 'aktif' : 'selesai'; ?>">
                            <?= $status; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn edit" onclick="editPenyewaan('<?= $nama_user; ?>', '<?= $id_mobil; ?>', '<?= $tanggal_sewa_raw; ?>')">Edit</button>
                        <a href="?hapus=1&nama=<?= urlencode($nama_user); ?>&mobil=<?= $id_mobil; ?>&tgl=<?= urlencode($tanggal_sewa_raw); ?>" class="btn hapus" onclick="return confirm('Yakin ingin menghapus data penyewaan ini?')">Hapus</a>
                        <?php if($status == 'Aktif'): ?>
                            <a href="?status=Selesai&nama=<?= urlencode($nama_user); ?>&mobil=<?= $id_mobil; ?>&tgl=<?= urlencode($tanggal_sewa_raw); ?>" class="btn status-btn" onclick="return confirm('Tandai penyewaan ini sebagai selesai?')">Selesai</a>
                        <?php else: ?>
                            <a href="?status=Aktif&nama=<?= urlencode($nama_user); ?>&mobil=<?= $id_mobil; ?>&tgl=<?= urlencode($tanggal_sewa_raw); ?>" class="btn edit" onclick="return confirm('Ubah status menjadi aktif?')">Aktifkan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                endwhile;
            } else {
            ?>
                <tr>
                    <td colspan="10" style="text-align:center; padding:40px; color:#64748b;">
                        📭 Belum ada data penyewaan. Silakan tambah penyewaan baru!
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✏️ Edit Data Penyewaan</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="editForm" method="POST" action="update_penyewaan.php">
            <input type="hidden" id="edit_nama_old" name="nama_user_old">
            <input type="hidden" id="edit_mobil_old" name="id_mobil_old">
            <input type="hidden" id="edit_tgl_old" name="tanggal_sewa_old">
            
            <div class="form-group">
                <label>Nama Penyewa</label>
                <input type="text" id="edit_nama" name="nama_user" required>
            </div>

            <div class="form-group">
                <label>Nama Mobil</label>
                <select id="edit_mobil" name="id_mobil" required>
                    <option value="">Pilih Mobil</option>
                    <?php
                    $mobilQuery = mysqli_query($conn, "SELECT * FROM mobil");
                    while($mobil = mysqli_fetch_assoc($mobilQuery)) {
                        echo "<option value='{$mobil['id_mobil']}'>{$mobil['nama_mobil']} - Rp " . number_format($mobil['harga_sewa'],0,',','.') . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah Hari Sewa</label>
                <input type="number" id="edit_jumlah" name="jumlah_sewa" required min="1">
            </div>

            <div class="form-group">
                <label>Tanggal Sewa</label>
                <input type="date" id="edit_tgl_sewa" name="tanggal_sewa" required>
            </div>

            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" id="edit_tgl_kembali" name="tanggal_kembali" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select id="edit_status" name="status" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Update Data</button>
        </form>
    </div>
</div>

<script>
// Function untuk edit penyewaan
function editPenyewaan(namaUser, idMobil, tanggalSewa) {
    // Fetch data penyewaan berdasarkan kombinasi data
    fetch(`get_penyewaan.php?nama=${encodeURIComponent(namaUser)}&mobil=${idMobil}&tgl=${encodeURIComponent(tanggalSewa)}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('edit_nama_old').value = data.data.nama_user;
                document.getElementById('edit_mobil_old').value = data.data.id_mobil;
                document.getElementById('edit_tgl_old').value = data.data.tanggal_sewa;
                document.getElementById('edit_nama').value = data.data.nama_user || '';
                document.getElementById('edit_jumlah').value = data.data.jumlah_sewa || 1;
                document.getElementById('edit_tgl_sewa').value = data.data.tanggal_sewa || '';
                document.getElementById('edit_tgl_kembali').value = data.data.tanggal_kembali || '';
                document.getElementById('edit_status').value = data.data.status || 'Aktif';
                
                // Set pilihan mobil
                const mobilSelect = document.getElementById('edit_mobil');
                for(let i = 0; i < mobilSelect.options.length; i++) {
                    if(mobilSelect.options[i].value == data.data.id_mobil) {
                        mobilSelect.selectedIndex = i;
                        break;
                    }
                }
                
                document.getElementById('editModal').style.display = 'block';
            } else {
                alert('Gagal mengambil data penyewaan: ' + (data.message || 'Data tidak ditemukan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data');
        });
}

// Close modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

</body>
</html>