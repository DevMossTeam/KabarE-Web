<?php
require_once 'config.php';

// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Fungsi untuk mendapatkan berita berdasarkan jumlah komentar terbanyak
function getBeritaKomentarTerbanyak($conn) {
    $query = "
    SELECT 
        berita.id,
        berita.judul,
        COUNT(komentar.id) AS jumlah_komentar
    FROM berita
    LEFT JOIN komentar ON berita.id = komentar.berita_id
    WHERE 
        berita.visibilitas = 'public' -- Hanya berita dengan visibilitas public
        AND berita.tanggal_diterbitkan >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) -- Hanya berita dalam 1 bulan terakhir
    GROUP BY berita.id
    ORDER BY jumlah_komentar DESC -- Urutkan berdasarkan jumlah komentar terbanyak
    LIMIT 5"; // Batasi hasil hanya 10 berita

    $stmt = $conn->prepare($query);

    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil data sebagai array asosiatif
        if ($result) {
            response([
                "status" => "success",
                "message" => "Data berita berdasarkan jumlah komentar ditemukan.",
                "result" => $result
            ]);
        } else {
            response([
                "status" => "success",
                "message" => "Tidak ada berita yang memenuhi kriteria.",
                "result" => []
            ]);
        }
    } else {
        response([
            "status" => "failed",
            "message" => "Gagal mengambil data berita."
        ], 500);
    }
}

// Memanggil fungsi
getBeritaKomentarTerbanyak($conn);
?>
