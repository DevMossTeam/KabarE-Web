<?php
require_once 'config.php'; 
// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json'); // Mengatur header respons
    http_response_code($status); // Mengatur kode status HTTP
    echo json_encode($data); // Mengembalikan data dalam format JSON
    exit; // Menghentikan eksekusi skrip setelah mengirim respons
}

// Fungsi untuk mendapatkan berita rekomendasi berdasarkan kategori atau tag terkait
function getBeritaRekomendasi($conn, $user_id, $limit = 10) {
    // Ambil tag atau kategori dari berita yang di-like atau di-bookmark pengguna
    $query_tags = "
    SELECT DISTINCT tag.nama_tag 
    FROM tag
    JOIN berita ON tag.berita_id = berita.id
    LEFT JOIN bookmark ON bookmark.berita_id = berita.id
    LEFT JOIN reaksi ON reaksi.berita_id = berita.id
    WHERE bookmark.user_id = :user_id OR reaksi.user_id = :user_id
    ";
    $stmt_tags = $conn->prepare($query_tags);
    $stmt_tags->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt_tags->execute();
    $tags = $stmt_tags->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($tags)) {
        // Query untuk berita berdasarkan tag yang relevan
        $tags_str = implode("','", array_map('addslashes', $tags));
        $query_rekomendasi = "
        SELECT 
            berita.*, 
            user.nama_pengguna,
            user.profile_pic,
            GROUP_CONCAT(DISTINCT tag.nama_tag SEPARATOR ',') AS tags,
            (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Suka' AND reaksi.berita_id = berita.id) AS jumlah_suka,
            (SELECT COUNT(*) FROM komentar WHERE komentar.berita_id = berita.id) AS jumlah_komentar
        FROM berita
        LEFT JOIN tag ON berita.id = tag.berita_id
        LEFT JOIN user ON berita.user_id = user.uid
        WHERE tag.nama_tag IN ('$tags_str') 
          AND berita.visibilitas = 'public'
        GROUP BY berita.id
        ORDER BY jumlah_suka DESC, jumlah_komentar DESC, berita.view_count DESC
        LIMIT :limit
        ";
    } else {
        // Jika tidak ada tag relevan, fallback ke berita populer
        $query_rekomendasi = "
        SELECT 
            berita.*, 
            user.nama_pengguna, 
            user.profile_pic,
            GROUP_CONCAT(DISTINCT tag.nama_tag SEPARATOR ',') AS tags,
            (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Suka' AND reaksi.berita_id = berita.id) AS jumlah_suka,
            (SELECT COUNT(*) FROM komentar WHERE komentar.berita_id = berita.id) AS jumlah_komentar
        FROM berita
        LEFT JOIN tag ON berita.id = tag.berita_id
        LEFT JOIN user ON berita.user_id = user.uid
        WHERE berita.visibilitas = 'public'
        GROUP BY berita.id
        ORDER BY jumlah_suka DESC, jumlah_komentar DESC, berita.view_count DESC
        LIMIT :limit
        ";
    }

    $stmt_rekomendasi = $conn->prepare($query_rekomendasi);
    $stmt_rekomendasi->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt_rekomendasi->execute();

    $result = $stmt_rekomendasi->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        foreach ($result as &$row) {
            if (!empty($row['profile_pic'])) {
                $row['profile_pic'] = base64_encode($row['profile_pic']);
            }
        }
        response([
            "status" => "success",
            "message" => "Data berita rekomendasi ditemukan.",
            "result" => $result
        ]);
    } else {
        response([
            "status" => "success",
            "message" => "Tidak ada berita rekomendasi.",
            "result" => []
        ]);
    }
}


// Pastikan user_id dikirim melalui parameter atau session
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
if ($user_id) {
    getBeritaRekomendasi($conn, $user_id);
} else {
    response([
        "status" => "failed",
        "message" => "User ID tidak ditemukan."
    ], 400);
}
?>
