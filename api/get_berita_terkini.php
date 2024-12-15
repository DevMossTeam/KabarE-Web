<?php
require_once 'config.php';
// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json'); // Mengatur header respons
    http_response_code($status); // Mengatur kode status HTTP
    echo json_encode($data); // Mengembalikan data dalam format JSON
    exit; // Menghentikan eksekusi skrip setelah mengirim respons
}

// Fungsi untuk mendapatkan berita terkini beserta jumlah suka dan tidak suka
function getBeritaTerkini($conn) {
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
    LEFT JOIN bookmark ON berita.id = bookmark.berita_id 
    WHERE berita.visibilitas = 'public'  -- Pastikan visibilitas public
    AND user.role = 'penulis'  -- Pastikan hanya penulis yang dapat membuat berita
    AND berita.tanggal_diterbitkan >= DATE_SUB(NOW(), INTERVAL 30 DAY) -- Filter 7 hari terakhir
    GROUP BY berita.id 
    ORDER BY berita.tanggal_diterbitkan DESC "; // Mengambil 10 berita terkini berdasarkan tanggal diterbitkan


    $stmt = $conn->prepare($query); // Menyiapkan pernyataan SQL

    if ($stmt->execute()) { // Jika query berhasil dieksekusi
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua data sebagai array asosiatif
        if ($result) {
            // Konversi field image_cover ke base64 jika ada
            foreach ($result as &$row) {
                if (isset($row['image_cover']) && !empty($row['image_cover'])) {
                    $row['image_cover'] = base64_encode($row['image_cover']); // Mengonversi data binary image ke base64
                }
                if (isset($row['profile_pic']) && !empty($row['profile_pic'])) {
                    $row['profile_pic'] = base64_encode($row['profile_pic']); // Mengonversi data binary profile_pic ke base64
                }
            }
            // Jika data ditemukan, kirimkan respons berhasil
            response([
                "status" => "success",
                "message" => "Data berita terkini ditemukan.",
                "result" => $result
            ]);
        } else {
            // Jika tidak ada data, kirimkan respons berhasil tetapi kosong
            response([
                "status" => "success",
                "message" => "Tidak ada berita terkini.",
                "result" => []
            ]);
        }
    } else {
        // Jika query gagal dieksekusi, kirimkan respons gagal
        response([
            "status" => "failed",
            "message" => "Gagal mengambil data berita terkini."
        ], 500); // 500 menandakan error pada server
    }
}
// Memanggil fungsi
getBeritaTerkini($conn); // Memanggil fungsi untuk mengambil berita terkini
?>   