<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('error_log', '../../error.log');

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
        try {
            $query = "SELECT b.*, u.nama_lengkap, u.nama_pengguna, u.profile_pic 
                     FROM berita b 
                     JOIN user u ON b.user_id = u.uid 
                     WHERE b.id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return null;
            }
            
            $stmt->bind_param('s', $this->id);
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return null;
            }
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            return $this->sanitizeData($data);
        } catch (Exception $e) {
            error_log("Error in getBeritaDetail: " . $e->getMessage());
            return null;
        }
    }

    public function getReaksi() {
        try {
            $query = "SELECT 
                        COUNT(CASE WHEN jenis_reaksi = 'Suka' THEN 1 END) as like_count,
                        COUNT(CASE WHEN jenis_reaksi = 'Tidak Suka' THEN 1 END) as dislike_count
                     FROM reaksi 
                     WHERE berita_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getReaksi: " . $e->getMessage());
            return ['like_count' => 0, 'dislike_count' => 0];
        }
    }

    public function getUserReaction() {
        if (!isset($_SESSION['user_id'])) return null;
        try {
            $query = "SELECT jenis_reaksi FROM reaksi WHERE user_id = ? AND berita_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $_SESSION['user_id'], $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0 ? $result->fetch_assoc()['jenis_reaksi'] : null;
        } catch (Exception $e) {
            error_log("Error in getUserReaction: " . $e->getMessage());
            return null;
        }
    }

    public function getBookmarkStatus() {
        if (!isset($_SESSION['user_id'])) return false;
        try {
            $query = "SELECT 1 FROM bookmark WHERE user_id = ? AND berita_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $_SESSION['user_id'], $this->id);
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error in getBookmarkStatus: " . $e->getMessage());
            return false;
        }
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
        try {
            $query = "SELECT k.id, k.teks_komentar, k.tanggal_komentar, 
                            u.nama_pengguna, u.profile_pic, k.user_id 
                     FROM komentar k 
                     JOIN user u ON k.user_id = u.uid 
                     WHERE k.berita_id = ? 
                     ORDER BY k.tanggal_komentar DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getKomentar: " . $e->getMessage());
            return [];
        }
    }

    public function getTopNews() {
        try {
            $query = "SELECT id, judul, tanggal_diterbitkan 
                     FROM berita 
                     WHERE visibilitas = 'public' 
                     ORDER BY view_count DESC 
                     LIMIT 5";
            $result = $this->conn->query($query);
            return $this->sanitizeData($result->fetch_all(MYSQLI_ASSOC));
        } catch (Exception $e) {
            error_log("Error in getTopNews: " . $e->getMessage());
            return [];
        }
    }

    public function getRecentNews() {
        try {
            $query = "SELECT id, judul, tanggal_diterbitkan 
                     FROM berita 
                     WHERE visibilitas = 'public' 
                     ORDER BY tanggal_diterbitkan DESC 
                     LIMIT 5";
            $result = $this->conn->query($query);
            return $this->sanitizeData($result->fetch_all(MYSQLI_ASSOC));
        } catch (Exception $e) {
            error_log("Error in getRecentNews: " . $e->getMessage());
            return [];
        }
    }

    public function getRelatedNews() {
        try {
            // Ambil kategori dari berita saat ini
            $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentBerita = $result->fetch_assoc();

            if (!$currentBerita) {
                return [];
            }

            // Ambil 3 berita dengan kategori yang sama secara random
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
        } catch (Exception $e) {
            error_log("Error in getRelatedNews: " . $e->getMessage());
            return [];
        }
    }

    public function getRandomNews() {
        try {
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
        } catch (Exception $e) {
            error_log("Error in getRandomNews: " . $e->getMessage());
            return [];
        }
    }

    public function getBeritaTeratas() {
        try {
            // Ambil kategori dari berita saat ini
            $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentBerita = $result->fetch_assoc();

            if (!$currentBerita) {
                return [];
            }

            // Ambil berita teratas dari kategori yang sama
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
        } catch (Exception $e) {
            error_log("Error in getBeritaTeratas: " . $e->getMessage());
            return [];
        }
    }

    public function getBeritaBaru() {
        try {
            // Ambil kategori dari berita saat ini
            $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentBerita = $result->fetch_assoc();

            if (!$currentBerita) {
                return [];
            }

            // Ambil berita terbaru dari kategori yang sama
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
        } catch (Exception $e) {
            error_log("Error in getBeritaBaru: " . $e->getMessage());
            return [];
        }
    }

    public function getBeritaLainnya() {
        try {
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
        } catch (Exception $e) {
            error_log("Error in getBeritaLainnya: " . $e->getMessage());
            return [];
        }
    }

    public function toggleReaction($userId, $jenisReaksi) {
        try {
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
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in toggleReaction: " . $e->getMessage());
            return false;
        }
    }

    public function toggleBookmark($userId) {
        try {
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
        } catch (Exception $e) {
            error_log("Error in toggleBookmark: " . $e->getMessage());
            return false;
        }
    }

    private function generateRandomId($length = 12) {
        return bin2hex(random_bytes($length));
    }
}

try {
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    $action = $_GET['action'] ?? '';
    $id = $_GET['id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$id) {
        throw new Exception("ID berita tidak valid");
    }

    $beritaDetail = new BeritaDetail($conn, $id, $user_id);
    $detailBerita = $beritaDetail->getBeritaDetail();

    if (!$detailBerita) {
        throw new Exception("Berita tidak ditemukan");
    }

    switch ($action) {
        case 'detail':
            // Handle reactions
            if (isset($_POST['reaction']) && isset($_SESSION['user_id'])) {
                $success = $beritaDetail->toggleReaction($_SESSION['user_id'], $_POST['reaction']);
                echo json_encode(['success' => $success]);
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
                echo json_encode(['success' => true, 'commentId' => $commentId]);
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
                error_log("JSON Error: " . json_last_error_msg());
                error_log("Data causing error: " . print_r($response, true));
                throw new Exception("JSON encoding error: " . json_last_error_msg());
            }
            
            echo $jsonResponse;
            break;
            
        default:
            throw new Exception("Action tidak valid");
    }
} catch (Exception $e) {
    ob_end_clean();
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'detail' => 'Silakan cek error.log untuk informasi lebih lanjut'
    ]);
}
?>