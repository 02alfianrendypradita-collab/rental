<?php
$conn = mysqli_connect("localhost","root","","rental_mobil");

$pesan = "";
$error = "";

if(isset($_POST['simpan'])){
    $nama_penyewa  = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $no_telp       = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nama_mobil    = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $harga         = $_POST['harga'];
    $jumlah_sewa   = $_POST['jumlah_sewa'];
    $tanggal_sewa  = $_POST['tanggal_sewa'];

    $result = mysqli_query($conn,"INSERT INTO penyewaan
        (nama_penyewa, no_telp, alamat, nama_mobil, harga, jumlah_sewa, tanggal_sewa)
        VALUES(
            '$nama_penyewa',
            '$no_telp',
            '$alamat',
            '$nama_mobil',
            '$harga',
            '$jumlah_sewa',
            '$tanggal_sewa'
        )");

    if($result){
        $pesan = "Data penyewaan berhasil ditambahkan!";
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Penyewaan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --blue-dark:   #0f2d6b;
    --blue-mid:    #1a4db5;
    --blue-light:  #3b7ef8;
    --accent:      #f59e0b;
    --white:       #ffffff;
    --gray-100:    #f1f5f9;
    --gray-300:    #cbd5e1;
    --gray-500:    #64748b;
    --gray-700:    #334155;
    --gray-900:    #0f172a;
    --success:     #10b981;
    --danger:      #ef4444;
    --radius-sm:   10px;
    --radius-md:   16px;
    --radius-lg:   24px;
    --shadow:      0 20px 60px rgba(15,45,107,0.18);
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 55%, var(--blue-light) 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
}

/* Decorative circles */
body::before, body::after {
    content: '';
    position: fixed;
    border-radius: 50%;
    opacity: 0.08;
    pointer-events: none;
}
body::before {
    width: 500px; height: 500px;
    background: white;
    top: -150px; right: -100px;
}
body::after {
    width: 350px; height: 350px;
    background: white;
    bottom: -100px; left: -80px;
}

/* ---- CARD ---- */
.card {
    width: 100%;
    max-width: 580px;
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(32px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ---- HEADER ---- */
.card-header {
    background: linear-gradient(130deg, var(--blue-dark), var(--blue-mid));
    padding: 32px 36px 28px;
    position: relative;
    overflow: hidden;
}
.card-header::after {
    content: '🚗';
    position: absolute;
    right: 28px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 64px;
    opacity: 0.18;
    pointer-events: none;
}
.card-header h1 {
    color: var(--white);
    font-size: 1.55rem;
    font-weight: 800;
    letter-spacing: -0.5px;
}
.card-header p {
    color: rgba(255,255,255,0.65);
    font-size: 0.85rem;
    margin-top: 4px;
}

/* ---- BODY ---- */
.card-body {
    padding: 32px 36px 28px;
}

/* ---- SECTION DIVIDER ---- */
.section-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--blue-mid);
    margin: 24px 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-300);
}
.section-label:first-of-type {
    margin-top: 0;
}

/* ---- GRID ---- */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ---- INPUT GROUP ---- */
.input-group {
    margin-bottom: 16px;
}
.input-group label {
    display: block;
    margin-bottom: 6px;
    color: var(--gray-700);
    font-size: 0.88rem;
    font-weight: 600;
}
.input-group label span.req {
    color: var(--danger);
    margin-left: 2px;
}

.input-group input,
.input-group select,
.input-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid var(--gray-300);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.93rem;
    color: var(--gray-900);
    background: var(--gray-100);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    border-color: var(--blue-light);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(59,126,248,0.12);
}
.input-group textarea {
    resize: vertical;
    min-height: 80px;
}

/* prefix input (Rp) */
.input-prefix-wrap {
    position: relative;
}
.input-prefix-wrap .prefix {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-weight: 600;
    font-size: 0.9rem;
    pointer-events: none;
}
.input-prefix-wrap input {
    padding-left: 42px;
}

/* ---- TOTAL DISPLAY ---- */
.total-box {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
    border-radius: var(--radius-md);
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    margin-top: 4px;
}
.total-box .label {
    color: rgba(255,255,255,0.75);
    font-size: 0.85rem;
    font-weight: 600;
}
.total-box .amount {
    color: var(--accent);
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -0.5px;
}

/* ---- ALERT ---- */
.alert {
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.alert-success { background: #d1fae5; color: #065f46; }
.alert-danger   { background: #fee2e2; color: #991b1b; }

/* ---- BUTTON ---- */
.btn-submit {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: var(--radius-sm);
    background: linear-gradient(90deg, var(--blue-mid), var(--blue-light));
    color: var(--white);
    font-family: inherit;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(26,77,181,0.35);
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26,77,181,0.4);
}
.btn-submit:active {
    transform: translateY(0);
}

/* ---- BACK LINK ---- */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 16px;
    color: var(--blue-mid);
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: gap 0.2s;
}
.back-link:hover { gap: 10px; text-decoration: underline; }

@media(max-width: 520px){
    .card-header, .card-body { padding-left: 20px; padding-right: 20px; }
    .grid-2 { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="card">

    <div class="card-header">
        <h1>Tambah Penyewaan</h1>
        <p>Lengkapi data penyewa dan detail sewa mobil</p>
    </div>

    <div class="card-body">

        <?php if($pesan): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($pesan) ?></div>
        <script>
            setTimeout(function(){ window.location='data_penyewa.php'; }, 1500);
        </script>
        <?php endif; ?>

        <?php if($error): ?>
        <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <!-- DATA PENYEWA -->
            <div class="section-label">Data Penyewa</div>

            <div class="input-group">
                <label>Nama Penyewa <span class="req">*</span></label>
                <input type="text" name="nama_penyewa"
                       placeholder="Contoh: Budi Santoso"
                       value="<?= isset($_POST['nama_penyewa']) ? htmlspecialchars($_POST['nama_penyewa']) : '' ?>"
                       required>
            </div>

            <div class="grid-2">
                <div class="input-group">
                    <label>No. Telepon <span class="req">*</span></label>
                    <input type="tel" name="no_telp"
                           placeholder="08xxxxxxxxxx"
                           value="<?= isset($_POST['no_telp']) ? htmlspecialchars($_POST['no_telp']) : '' ?>"
                           required>
                </div>
                <div class="input-group">
                    <label>Tanggal Sewa <span class="req">*</span></label>
                    <input type="date" name="tanggal_sewa"
                           value="<?= isset($_POST['tanggal_sewa']) ? htmlspecialchars($_POST['tanggal_sewa']) : date('Y-m-d') ?>"
                           required>
                </div>
            </div>

            <div class="input-group">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Alamat lengkap penyewa..."><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
            </div>

            <!-- DETAIL MOBIL -->
            <div class="section-label">Detail Mobil</div>

            <div class="input-group">
                <label>Nama Mobil <span class="req">*</span></label>
                <input type="text" name="nama_mobil"
                       placeholder="Contoh: Toyota Avanza"
                       value="<?= isset($_POST['nama_mobil']) ? htmlspecialchars($_POST['nama_mobil']) : '' ?>"
                       required>
            </div>

            <div class="grid-2">
                <div class="input-group">
                    <label>Harga / Hari <span class="req">*</span></label>
                    <div class="input-prefix-wrap">
                        <span class="prefix">Rp</span>
                        <input type="number" name="harga" id="harga"
                               placeholder="150000"
                               value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : '' ?>"
                               min="0" oninput="hitungTotal()" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Jumlah Hari <span class="req">*</span></label>
                    <input type="number" name="jumlah_sewa" id="jumlah_sewa"
                           placeholder="1"
                           value="<?= isset($_POST['jumlah_sewa']) ? htmlspecialchars($_POST['jumlah_sewa']) : '' ?>"
                           min="1" oninput="hitungTotal()" required>
                </div>
            </div>

            <!-- TOTAL -->
            <div class="total-box">
                <span class="label">Total Biaya Sewa</span>
                <span class="amount" id="total-display">Rp 0</span>
            </div>

            <button type="submit" name="simpan" class="btn-submit">
                🚀 Simpan Data Penyewaan
            </button>

        </form>

        <a href="data_penyewa.php" class="back-link">
            ← Kembali ke Data Penyewaan
        </a>

    </div>
</div>

<script>
function hitungTotal(){
    const harga  = parseFloat(document.getElementById('harga').value) || 0;
    const jumlah = parseFloat(document.getElementById('jumlah_sewa').value) || 0;
    const total  = harga * jumlah;
    document.getElementById('total-display').textContent =
        'Rp ' + total.toLocaleString('id-ID');
}
</script>

</body>
</html>