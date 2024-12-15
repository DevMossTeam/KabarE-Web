<?php
// Memasukkan koneksi database
include 'config.php';

// Mendapatkan parameter user_id dan berita_id dari request
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$berita_id = isset($_GET['berita_id']) ? $_GET['berita_id'] : null;

// Validasi parameter
if (!$user_id || !$berita_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'user_id dan berita_id harus disertakan.'
    ]);
    exit;
}

// Query untuk memeriksa reaksi (like atau dislike) dari user pada berita tertentu
$sql = "SELECT jenis_reaksi FROM reaksi WHERE user_id = :user_id AND berita_id = :berita_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':berita_id', $berita_id);
$stmt->execute();

// Cek apakah ada hasil
if ($stmt->rowCount() > 0) {
    $reaksi = $stmt->fetch(PDO::FETCH_ASSOC);

    // Menentukan nilai like dan dislike berdasarkan jenis reaksi
    $is_like = ($reaksi['jenis_reaksi'] === 'Suka') ? 1 : 0;
    $is_dislike = ($reaksi['jenis_reaksi'] === 'Tidak Suka') ? 1 : 0;

    // Mengirimkan respon sukses dengan status like dan dislike
    echo json_encode([
        'status' => 'success',
        'message' => 'Status reaksi berhasil diperiksa.',
        'is_like' => $is_like,
        'is_dislike' => $is_dislike
    ]);
} else {
    // Jika tidak ada reaksi yang ditemukan
    echo json_encode([
        'status' => 'success',
        'message' => 'Belum ada reaksi untuk berita ini.',
        'is_like' => 0,
        'is_dislike' => 0
    ]);
}
?>
