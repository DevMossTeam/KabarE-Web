<?php
require_once 'config.php'; // Pastikan file koneksi menggunakan PDO

// Atur timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Deteksi metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'POST': // Tambah komentar
        header('Content-Type: application/json');
    
        // Ambil parameter dari query string
        $user_id = $_GET['user_id'] ?? null;
        $berita_id = $_GET['berita_id'] ?? null;
        $teks_komentar = $_GET['teks_komentar'] ?? null;

        // Tanggal otomatis di-generate
        $tanggal_komentar = date('Y-m-d H:i:s');
    
        // Validasi parameter
        if (empty($user_id) || empty($berita_id) || empty($teks_komentar)) {
            echo json_encode([
                "status" => "error", 
                "message" => "Semua field harus diisi."
            ]);
            exit;
        }
    
        // Membuat ID otomatis dengan panjang 12 karakter
        $id_komentar = generateKomentarId();
    
        try {
            // Query untuk menambahkan komentar
            $query = "INSERT INTO komentar (id, user_id, berita_id, teks_komentar, tanggal_komentar) 
                      VALUES (:id_komentar, :user_id, :berita_id, :teks_komentar, :tanggal_komentar)";
            $stmt = $conn->prepare($query);
    
            // Binding parameter
            $stmt->bindParam(':id_komentar', $id_komentar, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_STR);
            $stmt->bindParam(':teks_komentar', $teks_komentar, PDO::PARAM_STR);
            $stmt->bindParam(':tanggal_komentar', $tanggal_komentar, PDO::PARAM_STR);
    
            // Eksekusi query
            if ($stmt->execute()) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "Komentar berhasil ditambahkan.",
                    "data" => [
                        "id_komentar" => $id_komentar,
                        "user_id" => $user_id,
                        "berita_id" => $berita_id,
                        "teks_komentar" => $teks_komentar,
                        "tanggal_komentar" => $tanggal_komentar
                    ]
                ]);
            } else {
                throw new Exception("Gagal menambahkan komentar.");
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error", 
                "message" => $e->getMessage()
            ]);
        }
        break;

    case 'GET': // Ambil daftar komentar
        $berita_id = $_GET['berita_id'] ?? '';

        if (empty($berita_id)) {
            echo json_encode([
                "status" => "error", 
                "message" => "ID berita tidak ditemukan."
            ]);
            exit;
        }

        // Query untuk mengambil komentar berdasarkan berita_id
        $query = "SELECT komentar.*, user.nama_pengguna, user.profile_pic
                  FROM komentar
                  JOIN user ON komentar.user_id = user.uid
                  WHERE komentar.berita_id = :berita_id
                  ORDER BY komentar.tanggal_komentar DESC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $komentar_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Jika ada gambar profil, ubah ke base64
                if (!empty($row['profile_pic'])) {
                    $row['profile_pic'] = base64_encode($row['profile_pic']);
                } else {
                    $row['profile_pic'] = null;
                }
                $komentar_data[] = $row;
            }
            echo json_encode([
                "status" => "success", 
                "message" => "Komentar ditemukan.", 
                "result" => $komentar_data
            ]);
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Tidak ada komentar untuk berita ini.", 
                "result" => []
            ]);
        }
        break;

    case 'DELETE': // Hapus komentar
        // Ambil ID komentar dari query parameter
        $id_komentar = $_GET['id_komentar'] ?? null;
    
        if (empty($id_komentar)) {
            echo json_encode([
                "status" => "error", 
                "message" => "ID komentar harus diberikan."
            ]);
            exit;
        }
    
        // Query untuk menghapus komentar berdasarkan ID
        $query = "DELETE FROM komentar WHERE id = :id_komentar";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_komentar', $id_komentar, PDO::PARAM_STR);
    
        // Eksekusi query
        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success", 
                "message" => "Komentar berhasil dihapus."
            ]);
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Gagal menghapus komentar."
            ]);
        }
        break;

    default: // Jika metode HTTP tidak valid
        echo json_encode([
            "status" => "error", 
            "message" => "Metode HTTP tidak valid."
        ]);
        break;
}

// Fungsi untuk menghasilkan ID komentar 12 karakter
function generateKomentarId() {
    // Ambil timestamp saat ini dan gabungkan dengan string acak
    $timestamp = time(); // Menggunakan timestamp sebagai bagian dari ID
    $randomString = substr(bin2hex(random_bytes(6)), 0, 6); // Menghasilkan 6 karakter acak
    return substr($timestamp . $randomString, 0, 12); // Gabungkan keduanya dan ambil 12 karakter pertama
}
?>
