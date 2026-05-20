<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mobil = mysqli_real_escape_string($conn, $_POST['id_mobil']);
    $nama_mobil = mysqli_real_escape_string($conn, $_POST['nama_mobil']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    
    $query = "UPDATE mobil SET 
              nama_mobil = '$nama_mobil',
              jumlah = '$jumlah',
              kondisi = '$kondisi',
              harga_sewa = '$harga_sewa'
              WHERE id_mobil = '$id_mobil'";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil diupdate']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate data: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
}

mysqli_close($conn);
?>