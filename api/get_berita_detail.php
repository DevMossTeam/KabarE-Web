<?php
require_once 'config.php';

// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Fungsi untuk mendapatkan detail artikel berdasarkan beritaId
function getDetailBerita($conn, $beritaId) {
    $query = "
    SELECT berita.*, 
           user.nama_pengguna, 
           user.profile_pic,
           GROUP_CONCAT(DISTINCT tag.nama_tag SEPARATOR ',') AS tags,
           (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Suka' AND reaksi.berita_id = berita.id) AS jumlah_suka,
           (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Tidak Suka' AND reaksi.berita_id = berita.id) AS jumlah_tidak_suka 
    FROM berita 
    LEFT JOIN user ON berita.user_id = user.uid
    LEFT JOIN tag ON berita.id = tag.berita_id
    WHERE berita.id = :beritaId
      AND berita.visibilitas = 'public'  -- Pastikan visibilitas public
    GROUP BY berita.id";

    $stmt = $conn->prepare($query); // Menyiapkan pernyataan SQL
    $stmt->bindParam(':beritaId', $beritaId, PDO::PARAM_STR); // Mengikat parameter

    if ($stmt->execute()) { // Jika query berhasil dieksekusi
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Mengambil data sebagai array asosiatif
        if ($result) {
            // Konversi field image_cover dan profile_pic ke base64 jika ada
            if (isset($result['image_cover']) && !empty($result['image_cover'])) {
                $result['image_cover'] = base64_encode($result['image_cover']);
            }
            if (isset($result['profile_pic']) && !empty($result['profile_pic'])) {
                $result['profile_pic'] = base64_encode($result['profile_pic']);
            }

            // Jika data ditemukan, kirimkan respons berhasil dalam bentuk array
            response([
                "status" => "success",
                "message" => "Detail artikel ditemukan.",
                "result" => [$result] // Bungkus dalam array untuk mengubah format
            ]);
        } else {
            // Jika tidak ada data, kirimkan respons berhasil tetapi kosong
            response([
                "status" => "success",
                "message" => "Detail artikel tidak ditemukan.",
                "result" => [] // Kembalikan array kosong
            ]);
        }
    } else {
        // Jika query gagal dieksekusi, kirimkan respons gagal
        response([
            "status" => "failed",
            "message" => "Gagal mengambil detail artikel."
        ], 500);
    }
}

// Mendapatkan parameter beritaId dari permintaan
if (isset($_GET['beritaId']) && !empty($_GET['beritaId'])) {
    $beritaId = $_GET['beritaId'];
    getDetailBerita($conn, $beritaId); // Memanggil fungsi untuk mengambil detail artikel
} else {
    // Jika parameter beritaId tidak ditemukan, kirimkan respons gagal
    response([
        "status" => "failed",
        "message" => "Parameter beritaId diperlukan."
    ], 400); // 400 menandakan bad request
}
