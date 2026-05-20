<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "rental_mobil");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = mysqli_query($conn, "SELECT * FROM penyewaan WHERE id_penyewaan = '$id'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
}

mysqli_close($conn);
?>