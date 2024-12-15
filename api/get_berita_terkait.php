<?php
require_once 'config.php'; 
// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json'); // Mengatur header respons
    http_response_code($status); // Mengatur kode status HTTP
    echo json_encode($data); // Mengembalikan data dalam format JSON
    exit; // Menghentikan eksekusi skrip setelah mengirim respons
}

// Fungsi untuk mendapatkan berita terkait berdasarkan kategori
function getBeritaTerkait($conn, $berita_id, $kategori) {
    // Query untuk mendapatkan berita terkait berdasarkan kategori
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
    WHERE berita.visibilitas = 'public'
      AND berita.kategori = :kategori  -- Menyaring berdasarkan kategori yang sama
      AND berita.id != :berita_id  -- Menghindari berita yang sama dengan berita yang sedang ditampilkan
    GROUP BY berita.id";

    $stmt = $conn->prepare($query); // Menyiapkan pernyataan SQL
    $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR); // Mengikat parameter kategori
    $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_STR); // Mengikat parameter berita_id

    // Debugging: cek query dan parameter
    error_log("Kategori: " . $kategori);
    error_log("Berita ID: " . $berita_id);

    // Eksekusi query
    if ($stmt->execute()) { // Jika query berhasil dieksekusi
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua data sebagai array asosiatif
        error_log("Jumlah Berita Terkait: " . count($result)); // Log jumlah berita terkait

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
                "message" => "Data berita terkait ditemukan.",
                "result" => $result
            ]);
        } else {
            // Jika tidak ada data, kirimkan respons berhasil tetapi kosong
            response([
                "status" => "success",
                "message" => "Tidak ada berita terkait.",
                "result" => []
            ]);
        }
    } else {
        // Jika query gagal dieksekusi, kirimkan respons gagal
        response([
            "status" => "failed",
            "message" => "Gagal mengambil data berita terkait."
        ], 500); // 500 menandakan error pada server
    }
}

// Memeriksa apakah parameter berita_id dan kategori ada
$berita_id = isset($_GET['berita_id']) ? $_GET['berita_id'] : ''; // Ambil ID berita yang sedang ditampilkan
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : ''; // Ambil kategori berita yang sedang ditampilkan

// Debugging untuk memeriksa nilai parameter yang diterima
error_log("Received berita_id: " . $berita_id);
error_log("Received kategori: " . $kategori);

// Memeriksa apakah kedua parameter valid
if ($berita_id && $kategori) {
    getBeritaTerkait($conn, $berita_id, $kategori); // Memanggil fungsi untuk mendapatkan berita terkait
} else {
    // Jika parameter tidak valid, beri pesan error yang lebih jelas
    response([
        "status" => "failed",
        "message" => "ID berita atau kategori tidak diberikan. Pastikan parameter berita_id dan kategori ada dalam URL."
    ], 400); // 400 menandakan parameter tidak valid
}
?>
     