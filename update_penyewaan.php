<?php
$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penyewaan = mysqli_real_escape_string($conn, $_POST['id_penyewaan']);
    $nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $id_mobil = mysqli_real_escape_string($conn, $_POST['id_mobil']);
    $jumlah_sewa = mysqli_real_escape_string($conn, $_POST['jumlah_sewa']);
    $tanggal_sewa = mysqli_real_escape_string($conn, $_POST['tanggal_sewa']);
    $tanggal_kembali = mysqli_real_escape_string($conn, $_POST['tanggal_kembali']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE penyewaan SET 
              nama_user = '$nama_user',
              id_mobil = '$id_mobil',
              jumlah_sewa = '$jumlah_sewa',
              tanggal_sewa = '$tanggal_sewa',
              tanggal_kembali = '$tanggal_kembali',
              status = '$status'
              WHERE id_penyewaan = '$id_penyewaan'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data penyewaan berhasil diupdate!'); window.location.href='data_penyewa.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($conn) . "'); window.location.href='data_penyewa.php';</script>";
    }
} else {
    header("Location: data_penyewa.php");
}

mysqli_close($conn);
?>