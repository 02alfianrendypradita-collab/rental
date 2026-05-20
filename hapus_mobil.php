<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Cek apakah mobil sedang disewa
    $checkQuery = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id_mobil = '$id' AND status = 'Aktif'");
    
    if (mysqli_num_rows($checkQuery) > 0) {
        echo json_encode(['success' => false, 'message' => 'Mobil sedang disewa, tidak bisa dihapus!']);
    } else {
        $query = "DELETE FROM mobil WHERE id_mobil = '$id'";
        
        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data: ' . mysqli_error($conn)]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
}

mysqli_close($conn);
?>