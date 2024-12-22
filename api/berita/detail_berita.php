<?php

include '../connection/config.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Tangani permintaan komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $teks_komentar = $_POST['comment'];
    $randomId = generateRandomId();
    $tanggal_komentar = date('Y-m-d H:i:s');
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && $id) {
        $query = "INSERT INTO komentar (id, user_id, berita_id, teks_komentar, tanggal_komentar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $randomId, $user_id, $id, $teks_komentar, $tanggal_komentar);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User ID atau ID berita tidak valid.']);
    }
    exit;
}

// Hapus komentar jika ada permintaan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment_id'])) {
    $commentId = $_POST['delete_comment_id'];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $query = "DELETE FROM komentar WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $commentId, $user_id);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pengguna belum login.']);
    }
    exit;
}

// Pastikan $id valid sebelum melanjutkan
if (!$id) {
    echo "ID berita tidak valid.";
    exit;
}

// Fungsi untuk menghitung waktu yang lalu
function timeAgo($datetimeString) {
    $now = new DateTime();
    $posted = new DateTime($datetimeString);
    $interval = $now->diff($posted);

    if ($interval->y > 0) return $interval->y . " tahun yang lalu";
    if ($interval->m > 0) return $interval->m . " bulan yang lalu";
    if ($interval->d > 0) return $interval->d . " hari yang lalu";
    if ($interval->h > 0) return $interval->h . " jam yang lalu";
    if ($interval->i > 0) return $interval->i . " menit yang lalu";
    return "baru saja";
}

// Query untuk mendapatkan detail berita dan nama penulis
$query = "SELECT b.judul, b.konten_artikel, b.tanggal_diterbitkan, b.kategori, u.nama_lengkap, u.nama_pengguna, u.profile_pic 
          FROM berita b 
          JOIN user u ON b.user_id = u.uid 
          WHERE b.id = ? AND b.visibilitas = 'public'";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

if ($berita) {
    $judul = $berita['judul'];
    $konten = $berita['konten_artikel'];
    $tanggalDiterbitkan = $berita['tanggal_diterbitkan'];
    $kategori = $berita['kategori'];
    $penulis = $berita['nama_lengkap'];
    $namaPengguna = $berita['nama_pengguna'];
    $profilePic = $berita['profile_pic'];

    // Ekstrak URL gambar pertama dari konten artikel
    preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $konten, $matches);
    $gambarPertama = $matches[1] ?? ''; // Ambil URL gambar pertama jika ada
    $konten = preg_replace('/<img.*?>/i', '', $konten, 1); // Hapus hanya gambar pertama
} else {
    echo "Berita tidak ditemukan.";
    exit;
}

// Ambil ID dan kategori dari berita saat ini
$kategori = '';

// Dapatkan kategori berita saat ini
if ($id) {
    $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentNews = $result->fetch_assoc();
    $kategori = $currentNews['kategori'];
}

// Query untuk mendapatkan berita teratas dari kategori yang sama berdasarkan jumlah reaksi 'Suka' dan view_count
$topNewsQuery = "
    SELECT b.id, b.judul, b.tanggal_diterbitkan, COUNT(r.jenis_reaksi) AS like_count
    FROM berita b
    LEFT JOIN reaksi r ON b.id = r.berita_id AND r.jenis_reaksi = 'Suka'
    WHERE b.kategori = ? AND b.visibilitas = 'public'
    GROUP BY b.id
    ORDER BY like_count DESC, b.view_count DESC
    LIMIT 6
";
$stmt = $conn->prepare($topNewsQuery);
$stmt->bind_param('s', $kategori); // Gunakan kategori yang sama
$stmt->execute();
$topNewsResult = $stmt->get_result();

// Ambil ID dan kategori dari berita saat ini
$kategori = '';

// Dapatkan kategori berita saat ini
if ($id) {
    $query = "SELECT kategori FROM berita WHERE id = ? AND visibilitas = 'public'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentNews = $result->fetch_assoc();
    $kategori = $currentNews['kategori'];
}

// Query untuk mendapatkan berita terbaru dari kategori yang sama
$recentNewsQuery = "SELECT id, judul, tanggal_diterbitkan FROM berita WHERE kategori = ? AND id != ? AND visibilitas = 'public' ORDER BY tanggal_diterbitkan DESC LIMIT 4";
$stmt = $conn->prepare($recentNewsQuery);
$stmt->bind_param('ss', $kategori, $id);
$stmt->execute();
$recentNewsResult = $stmt->get_result();

if (!$recentNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Query untuk mendapatkan berita acak dari kategori yang sama
$sameTopicNewsQuery = "SELECT id, judul, konten_artikel FROM berita WHERE kategori = ? AND id != ? AND visibilitas = 'public' ORDER BY RAND() LIMIT 3";
$stmt = $conn->prepare($sameTopicNewsQuery);
$stmt->bind_param('ss', $kategori, $id);
$stmt->execute();
$sameTopicNewsResult = $stmt->get_result();

if (!$sameTopicNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Query untuk mendapatkan berita acak
$randomNewsQuery = "SELECT id, judul, konten_artikel, tanggal_diterbitkan, kategori FROM berita WHERE visibilitas = 'public' ORDER BY RAND() LIMIT 4";
$randomNewsResult = $conn->query($randomNewsQuery);

if (!$randomNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'] ?? null;
$namaPengguna = '';
$profilePic = '';
$userReaction = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT nama_pengguna, profile_pic FROM user WHERE uid = ?");
    if ($stmt) {
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->bind_result($namaPengguna, $profilePic);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Query gagal: " . $conn->error);
    }

    // Cek reaksi pengguna saat ini
    $stmt = $conn->prepare("SELECT jenis_reaksi FROM reaksi WHERE user_id = ? AND berita_id = ?");
    $stmt->bind_param('ss', $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userReaction = $result->fetch_assoc()['jenis_reaksi'];
    }
}

// Fungsi untuk menghasilkan ID acak
function generateRandomId($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Fungsi untuk mengelola reaksi
function toggleReaction($conn, $user_id, $berita_id, $jenis_reaksi) {
    // Cek apakah reaksi sudah ada
    $query = "SELECT * FROM reaksi WHERE user_id = ? AND berita_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $user_id, $berita_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingReaction = $result->fetch_assoc();

    if ($existingReaction) {
        // Jika reaksi sudah ada, hapus atau ubah jenis reaksi
        if ($existingReaction['jenis_reaksi'] === $jenis_reaksi) {
            // Hapus reaksi jika jenisnya sama
            $query = "DELETE FROM reaksi WHERE user_id = ? AND berita_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $user_id, $berita_id);
            $stmt->execute();
        } else {
            // Ubah jenis reaksi
            $query = "UPDATE reaksi SET jenis_reaksi = ?, tanggal_reaksi = NOW() WHERE user_id = ? AND berita_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sss', $jenis_reaksi, $user_id, $berita_id);
            $stmt->execute();
        }
    } else {
        // Tambahkan reaksi baru dengan ID acak
        $randomId = generateRandomId();
        $query = "INSERT INTO reaksi (id, user_id, berita_id, jenis_reaksi, tanggal_reaksi) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $randomId, $user_id, $berita_id, $jenis_reaksi);
        $stmt->execute();
    }
}

// Fungsi untuk mengelola bookmark
function toggleBookmark($conn, $user_id, $berita_id) {
    // Cek apakah bookmark sudah ada
    $query = "SELECT * FROM bookmark WHERE user_id = ? AND berita_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $user_id, $berita_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingBookmark = $result->fetch_assoc();

    if ($existingBookmark) {
        // Hapus bookmark jika sudah ada
        $query = "DELETE FROM bookmark WHERE user_id = ? AND berita_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $user_id, $berita_id);
        $stmt->execute();
    } else {
        // Tambahkan bookmark baru dengan ID acak dan tanggal bookmark
        $randomId = generateRandomId();
        $tanggal_bookmark = date('Y-m-d H:i:s');
        $query = "INSERT INTO bookmark (id, user_id, berita_id, tanggal_bookmark) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $randomId, $user_id, $berita_id, $tanggal_bookmark);
        $stmt->execute();
    }
}

// Tangani permintaan reaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reaction'])) {
    $jenis_reaksi = $_POST['reaction'];
    toggleReaction($conn, $user_id, $id, $jenis_reaksi);
    // Refresh halaman untuk memperbarui tampilan
    header("Location: news-detail.php?id=$id");
    exit;
}

// Tangani permintaan bookmark
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookmark'])) {
    toggleBookmark($conn, $user_id, $id);
    // Refresh halaman untuk memperbarui tampilan
    header("Location: news-detail.php?id=$id");
    exit;
}

// Hitung jumlah like dan dislike
$likeQuery = "SELECT COUNT(*) as like_count FROM reaksi WHERE berita_id = ? AND jenis_reaksi = 'Suka'";
$stmt = $conn->prepare($likeQuery);
$stmt->bind_param('s', $id);
$stmt->execute();
$likeResult = $stmt->get_result();
$likeCount = $likeResult->fetch_assoc()['like_count'] ?? 0;

$dislikeQuery = "SELECT COUNT(*) as dislike_count FROM reaksi WHERE berita_id = ? AND jenis_reaksi = 'Tidak Suka'";
$stmt = $conn->prepare($dislikeQuery);
$stmt->bind_param('s', $id);
$stmt->execute();
$dislikeResult = $stmt->get_result();
$dislikeCount = $dislikeResult->fetch_assoc()['dislike_count'] ?? 0;

// Cek status bookmark pengguna saat ini
$isBookmarked = false;
if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM bookmark WHERE user_id = ? AND berita_id = ?");
    $stmt->bind_param('ss', $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $isBookmarked = $result->num_rows > 0;
}

// Query untuk mendapatkan tag berdasarkan berita_id
$tagQuery = "SELECT nama_tag FROM tag WHERE berita_id = ?";
$stmt = $conn->prepare($tagQuery);
$stmt->bind_param('s', $id);
$stmt->execute();
$tagResult = $stmt->get_result();

$tags = [];
while ($tag = $tagResult->fetch_assoc()) {
    $tags[] = $tag['nama_tag'];
}

// Ambil komentar dari database
$commentQuery = "SELECT k.id, k.teks_komentar, k.tanggal_komentar, u.nama_pengguna, u.profile_pic, k.user_id 
                 FROM komentar k 
                 JOIN user u ON k.user_id = u.uid 
                 JOIN berita b ON k.berita_id = b.id
                 WHERE k.berita_id = ? AND b.visibilitas = 'public'
                 ORDER BY k.tanggal_komentar DESC";
$stmt = $conn->prepare($commentQuery);
$stmt->bind_param('s', $id);
$stmt->execute();
$commentResult = $stmt->get_result();
$commentCount = $commentResult->num_rows;
?>