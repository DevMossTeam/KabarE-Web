<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

ob_start();
require_once '../../connection/config.php';

class BeritaDetail {
    private $conn;
    private $id;
    private $user_id;

    public function __construct($conn, $id, $user_id = null) {
        $this->conn = $conn;
        $this->id = $id;
        $this->user_id = $user_id;
        
        $this->conn->set_charset("utf8mb4");
    }

    private function sanitizeData($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeData($value);
            }
            return $data;
        } else if (is_string($data)) {
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
            return htmlspecialchars_decode($data, ENT_QUOTES);
        }
        return $data;
    }

    public function getBeritaDetail() {
        $query = "SELECT b.*, u.nama_lengkap, u.nama_pengguna, u.profile_pic 
                 FROM berita b 
                 JOIN user u ON b.user_id = u.uid 
                 WHERE b.id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param('s', $this->id);
        if (!$stmt->execute()) {
            return null;
        }
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        return $this->sanitizeData($data);
    }

    public function getReaksi() {
        $query = "SELECT 
                    COUNT(CASE WHEN jenis_reaksi = 'Suka' THEN 1 END) as like_count,
                    COUNT(CASE WHEN jenis_reaksi = 'Tidak Suka' THEN 1 END) as dislike_count
                 FROM reaksi 
                 WHERE berita_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserReaction() {
        if (!isset($_SESSION['user_id'])) return null;
        $query = "SELECT jenis_reaksi FROM reaksi WHERE user_id = ? AND berita_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $_SESSION['user_id'], $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc()['jenis_reaksi'] : null;
    }

    public function getBookmarkStatus() {
        if (!isset($_SESSION['user_id'])) return false;
        $query = "SELECT 1 FROM bookmark WHERE user_id = ? AND berita_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $_SESSION['user_id'], $this->id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getTags() {
        $stmt = $this->conn->prepare("SELECT nama_tag FROM tag WHERE berita_id = ?");
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tags = [];
        while ($tag = $result->fetch_assoc()) {
            $tags[] = $tag['nama_tag'];
        }
        return $tags;
    }

    public function getKomentar() {
        $query = "SELECT k.id, k.teks_komentar, k.tanggal_komentar, 
                        u.nama_pengguna, u.uid as user_id,
                        COALESCE(u.profile_pic, '') as profile_pic  
                 FROM komentar k 
                 JOIN user u ON k.user_id = u.uid 
                 WHERE k.berita_id = ? 
                 ORDER BY k.tanggal_komentar DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $comments = [];
        
        while ($row = $result->fetch_assoc()) {
            if ($row['profile_pic']) {
                $row['profile_pic'] = base64_encode($row['profile_pic']);
            }
            $comments[] = $row;
        }
        
        return $comments;
    }

    public function getTopNews() {
        $query = "SELECT id, judul, tanggal_diterbitkan 
                 FROM berita 
                 WHERE visibilitas = 'public' 
                 ORDER BY view_count DESC 
                 LIMIT 5";
        $result = $this->conn->query($query);
        return $this->sanitizeData($result->fetch_all(MYSQLI_ASSOC));
    }

    public function getRecentNews() {
        $query = "SELECT id, judul, tanggal_diterbitkan 
                 FROM berita 
                 WHERE visibilitas = 'public' 
                 ORDER BY tanggal_diterbitkan DESC 
                 LIMIT 5";
        $result = $this->conn->query($query);
        return $this->sanitizeData($result->fetch_all(MYSQLI_ASSOC));
    }

    public function getRelatedNews() {
        $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentBerita = $result->fetch_assoc();

        if (!$currentBerita) {
            return [];
        }

        $query = "SELECT id, judul, konten_artikel, tanggal_diterbitkan 
                 FROM berita 
                 WHERE kategori = ? 
                 AND id != ? 
                 AND visibilitas = 'public' 
                 ORDER BY RAND() 
                 LIMIT 3";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $currentBerita['kategori'], $this->id);
        $stmt->execute();
        return $this->sanitizeData($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    public function getRandomNews() {
        $query = "SELECT id, judul, konten_artikel, tanggal_diterbitkan 
                 FROM berita 
                 WHERE visibilitas = 'public' 
                 AND id != ? 
                 ORDER BY RAND() 
                 LIMIT 4";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        return $this->sanitizeData($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    public function getBeritaTeratas() {
        $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentBerita = $result->fetch_assoc();

        if (!$currentBerita) {
            return [];
        }

        $query = "SELECT id, judul, tanggal_diterbitkan 
                 FROM berita 
                 WHERE kategori = ? 
                 AND id != ? 
                 AND visibilitas = 'public' 
                 ORDER BY RAND() 
                 LIMIT 6";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $currentBerita['kategori'], $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBeritaBaru() {
        $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentBerita = $result->fetch_assoc();

        if (!$currentBerita) {
            return [];
        }

        $query = "SELECT id, judul, tanggal_diterbitkan 
                 FROM berita 
                 WHERE kategori = ? 
                 AND id != ? 
                 AND visibilitas = 'public' 
                 ORDER BY tanggal_diterbitkan DESC 
                 LIMIT 4";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $currentBerita['kategori'], $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBeritaLainnya() {
        $query = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                 FROM berita 
                 WHERE id != ? 
                 AND visibilitas = 'public' 
                 ORDER BY RAND() 
                 LIMIT 4";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->id);
        $stmt->execute();
        return $this->sanitizeData($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    public function toggleReaction($userId, $jenisReaksi) {
        error_log("toggleReaction called with userId: $userId, jenisReaksi: $jenisReaksi");
        $query = "SELECT * FROM reaksi WHERE user_id = ? AND berita_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $userId, $this->id);
        $stmt->execute();
        $existingReaction = $stmt->get_result()->fetch_assoc();

        if ($existingReaction) {
            if ($existingReaction['jenis_reaksi'] === $jenisReaksi) {
                $query = "DELETE FROM reaksi WHERE user_id = ? AND berita_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('ss', $userId, $this->id);
            } else {
                $query = "UPDATE reaksi SET jenis_reaksi = ? WHERE user_id = ? AND berita_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('sss', $jenisReaksi, $userId, $this->id);
            }
        } else {
            $randomId = $this->generateRandomId();
            $query = "INSERT INTO reaksi (id, user_id, berita_id, jenis_reaksi) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssss', $randomId, $userId, $this->id, $jenisReaksi);
        }
        $stmt->execute();

        // Get updated counts
        $reaksi = $this->getReaksi();
        error_log("Updated reaksi: " . json_encode($reaksi));
        return $reaksi;
    }

    public function toggleBookmark($userId) {
        $query = "SELECT 1 FROM bookmark WHERE user_id = ? AND berita_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $userId, $this->id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;

        if ($exists) {
            $query = "DELETE FROM bookmark WHERE user_id = ? AND berita_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $userId, $this->id);
        } else {
            $randomId = $this->generateRandomId();
            $query = "INSERT INTO bookmark (id, user_id, berita_id) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sss', $randomId, $userId, $this->id);
        }
        return $stmt->execute();
    }

    public function addComment($userId, $commentText) {
        $commentId = $this->generateRandomId();
        $query = "INSERT INTO komentar (id, user_id, berita_id, teks_komentar, tanggal_komentar) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssss', $commentId, $userId, $this->id, $commentText);
        
        if ($stmt->execute()) {
            error_log("Comment added successfully: " . $commentId);
            return $commentId;
        } else {
            error_log("Failed to add comment: " . $stmt->error);
        }
        return false;
    }

    private function generateRandomId($length = 12) {
        return bin2hex(random_bytes($length / 2));
    }
}

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'error' => "Database connection failed",
        'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
    ]);
    exit;
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode([
        'error' => "ID berita tidak valid",
        'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
    ]);
    exit;
}

$beritaDetail = new BeritaDetail($conn, $id, $user_id);
$detailBerita = $beritaDetail->getBeritaDetail();

if (!$detailBerita) {
    http_response_code(404);
    echo json_encode([
        'error' => "Berita tidak ditemukan",
        'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $teks_komentar = $_POST['comment'];
    $randomId = generateRandomId();
    $timestamp = round(microtime(true) * 1000); // Timestamp dalam milidetik
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && $id) {
        $query = "INSERT INTO komentar (id, user_id, berita_id, teks_komentar, tanggal_komentar) VALUES (?, ?, ?, ?, FROM_UNIXTIME(? / 1000))";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $randomId, $user_id, $id, $teks_komentar, $timestamp);
        $stmt->execute();

        // Ambil profile_pic dari user
        $stmt = $conn->prepare("SELECT profile_pic FROM user WHERE uid = ?");
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        echo json_encode(['success' => true, 'commentId' => $randomId, 'profilePic' => base64_encode($user['profile_pic']), 'timestamp' => $timestamp]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID atau ID berita tidak valid.']);
    }
    exit;
}

switch ($action) {
    case 'detail':
        // Handle reactions
        if (isset($_POST['reaction']) && isset($_SESSION['user_id'])) {
            $reaksi = $beritaDetail->toggleReaction($_SESSION['user_id'], $_POST['reaction']);
            echo json_encode(['success' => true, 'reaksi' => $reaksi]);
            exit;
        }
        
        // Handle bookmarks
        if (isset($_POST['bookmark']) && isset($_SESSION['user_id'])) {
            $success = $beritaDetail->toggleBookmark($_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
        
        // Handle comments
        if (isset($_POST['comment']) && isset($_SESSION['user_id'])) {
            $commentId = $beritaDetail->addComment($_SESSION['user_id'], $_POST['comment']);
            if ($commentId) {
                echo json_encode(['success' => true, 'commentId' => $commentId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan komentar.']);
            }
            exit;
        }
        
        if (isset($_POST['delete_comment_id']) && isset($_SESSION['user_id'])) {
            $success = $beritaDetail->deleteComment($_POST['delete_comment_id'], $_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
        
        $response = [
            'berita' => $detailBerita,
            'reaksi' => $beritaDetail->getReaksi(),
            'userReaction' => $beritaDetail->getUserReaction(),
            'isBookmarked' => $beritaDetail->getBookmarkStatus(),
            'tags' => $beritaDetail->getTags() ?? [],
            'komentar' => $beritaDetail->getKomentar() ?? [],
            'beritaTeratas' => $beritaDetail->getBeritaTeratas() ?? [],
            'beritaBaru' => $beritaDetail->getBeritaBaru() ?? [],
            'beritaLainnya' => $beritaDetail->getBeritaLainnya() ?? [],
            'topNews' => $beritaDetail->getTopNews() ?? [],
            'recentNews' => $beritaDetail->getRecentNews() ?? [],
            'relatedNews' => $beritaDetail->getRelatedNews() ?? [],
            'randomNews' => $beritaDetail->getRandomNews() ?? []
        ];
        
        ob_end_clean();
        
        $jsonResponse = json_encode($response, 
            JSON_UNESCAPED_UNICODE | 
            JSON_UNESCAPED_SLASHES | 
            JSON_INVALID_UTF8_IGNORE
        );
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(500);
            echo json_encode([
                'error' => "JSON encoding error",
                'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
            ]);
            exit;
        }
        
        echo $jsonResponse;
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'error' => "Action tidak valid",
            'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
        ]);
}
?>