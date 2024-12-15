<?php
require_once 'config.php'; // Memuat file koneksi database

// Atur timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mengembalikan respons JSON
function response($data, $status = 200) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Fungsi untuk generate ID bookmark otomatis
function generateBookmarkId($length = 12) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Mendapatkan metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // Menambah atau menghapus bookmark
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['user_id']) && isset($data['berita_id'])) {
        $user_id = $data['user_id'];
        $berita_id = $data['berita_id'];
        $bookmark_id = generateBookmarkId();

        // Cek apakah berita sudah di-bookmark
        $checkSql = "SELECT * FROM bookmark WHERE user_id = :user_id AND berita_id = :berita_id";
        $stmt = $conn->prepare($checkSql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':berita_id', $berita_id);
        $stmt->execute();

        // Jika sudah ada bookmark, maka hapus
        if ($stmt->rowCount() > 0) {
            $deleteSql = "DELETE FROM bookmark WHERE user_id = :user_id AND berita_id = :berita_id";
            $stmt = $conn->prepare($deleteSql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':berita_id', $berita_id);

            if ($stmt->execute()) {
                response([
                    'status' => 'success',
                    'message' => 'Bookmark berhasil dihapus.',
                ]);
            } else {
                response([
                    'status' => 'error',
                    'message' => 'Gagal menghapus bookmark.'
                ], 500);
            }
        } else {
             // Jika belum ada bookmark, maka tambah bookmark
    $sql = "INSERT INTO bookmark (id, user_id, berita_id, tanggal_bookmark) VALUES (:bookmark_id, :user_id, :berita_id, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bookmark_id', $bookmark_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':berita_id', $berita_id);

    if ($stmt->execute()) {
        response([
            'status' => 'success',
            'message' => 'Bookmark berhasil ditambahkan.',
            'result' => [
                'user_id' => $user_id,
                'berita_id' => $berita_id,
                'bookmark_id' => $bookmark_id,
                'is_bookmarked' => true
            ]
        ]);
    } else {
        response([
            'status' => 'error',
            'message' => 'Gagal menambahkan bookmark.'
        ], 500);
    }
}
    } else {
        response([
            'status' => 'error',
            'message' => 'User ID dan Berita ID harus disertakan.'
        ], 400);
    }
} elseif ($method == 'GET') {
    if (isset($_GET['user_id']) && isset($_GET['berita_id'])) {
        // Memeriksa status bookmark untuk user_id dan berita_id tertentu
        $user_id = $_GET['user_id'];
        $berita_id = $_GET['berita_id'];

        $sql = "SELECT COUNT(*) as is_bookmarked 
                FROM bookmark 
                WHERE user_id = :user_id AND berita_id = :berita_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':berita_id', $berita_id);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $is_bookmarked = $result['is_bookmarked'] > 0 ? 1 : 0;

            response([
                'status' => 'success',
                'message' => 'Status bookmark berhasil diperiksa.',
                'is_bookmarked' => $is_bookmarked
            ]);
        } else {
            response([
                'status' => 'error',
                'message' => 'Gagal memeriksa status bookmark.'
            ], 500);
        }
    } elseif (isset($_GET['user_id'])) {
        // Mendapatkan berita yang di-bookmark oleh user tertentu
        $user_id = $_GET['user_id'];

        $query = "
        SELECT berita.*, 
               user.uid AS penulis_user_id, 
               user.nama_pengguna, 
               user.profile_pic,
               GROUP_CONCAT(DISTINCT tag.nama_tag SEPARATOR ',') AS tags,
               COUNT(DISTINCT bookmark.id) AS bookmark_count,
               IF(bookmark.user_id IS NOT NULL, 1, 0) AS is_bookmarked, 
               (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Suka' AND reaksi.berita_id = berita.id) AS jumlah_suka,
               (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = 'Tidak Suka' AND reaksi.berita_id = berita.id) AS jumlah_tidak_suka,
               bookmark.user_id AS bookmark_user_id,
               MAX(bookmark.tanggal_bookmark) AS tanggal_bookmark
        FROM berita 
        LEFT JOIN user ON berita.user_id = user.uid
        LEFT JOIN tag ON berita.id = tag.berita_id
        LEFT JOIN bookmark ON berita.id = bookmark.berita_id 
        WHERE bookmark.user_id = :user_id
          AND berita.visibilitas = 'public'
        GROUP BY berita.id
        ORDER BY tanggal_bookmark DESC";
    

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                foreach ($result as &$row) {
                    if (isset($row['image_cover']) && !empty($row['image_cover'])) {
                        $row['image_cover'] = base64_encode($row['image_cover']);
                    }
                    if (isset($row['profile_pic']) && !empty($row['profile_pic'])) {
                        $row['profile_pic'] = base64_encode($row['profile_pic']);
                    }
                    $row['bookmark_user_id'] = $user_id;
                }
                response([
                    'status' => 'success',
                    'message' => 'Data berita yang di-bookmark ditemukan.',
                    'result' => $result
                ]);
            } else {
                response([
                    'status' => 'success',
                    'message' => 'Tidak ada berita yang di-bookmark oleh user.',
                    'result' => []
                ]);
            }
        } else {
            response([
                'status' => 'failed',
                'message' => 'Gagal mengambil data berita yang di-bookmark.'
            ], 500);
        }
    } else {
        response([
            'status' => 'error',
            'message' => 'Parameter user_id atau berita_id harus disertakan.'
        ], 400);
    }
} else {
    response([
        'status' => 'error',
        'message' => 'Metode HTTP tidak valid.'
    ], 405);
}
?>
