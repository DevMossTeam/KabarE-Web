<?php
session_start(); // Pastikan sesi dimulai

include '../connection/config.php'; // Pastikan path ini sesuai dengan struktur folder Anda

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

        header("Location: news-detail.php?id=$id");
        exit;
    } else {
        echo "Pengguna belum login.";
        exit;
    }
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
$query = "SELECT b.judul, b.konten_artikel, b.tanggal_dibuat, b.kategori, u.nama_lengkap, u.nama_pengguna, u.profile_pic 
          FROM berita b 
          JOIN user u ON b.user_id = u.uid 
          WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

if ($berita) {
    $judul = $berita['judul'];
    $konten = $berita['konten_artikel'];
    $tanggalDibuat = $berita['tanggal_dibuat'];
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

// Query untuk mendapatkan berita teratas secara acak
$topNewsQuery = "SELECT id, judul, tanggal_dibuat FROM berita ORDER BY RAND() LIMIT 6";
$topNewsResult = $conn->query($topNewsQuery);

if (!$topNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Ambil ID dan kategori dari berita saat ini
$kategori = '';

// Dapatkan kategori berita saat ini
if ($id) {
    $query = "SELECT kategori FROM berita WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentNews = $result->fetch_assoc();
    $kategori = $currentNews['kategori'];
}

// Query untuk mendapatkan berita terbaru dari kategori yang sama
$recentNewsQuery = "SELECT id, judul, tanggal_dibuat FROM berita WHERE kategori = ? AND id != ? ORDER BY tanggal_dibuat DESC LIMIT 4";
$stmt = $conn->prepare($recentNewsQuery);
$stmt->bind_param('ss', $kategori, $id);
$stmt->execute();
$recentNewsResult = $stmt->get_result();

if (!$recentNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Query untuk mendapatkan berita acak dari kategori yang sama
$sameTopicNewsQuery = "SELECT id, judul, konten_artikel FROM berita WHERE kategori = ? AND id != ? ORDER BY RAND() LIMIT 3";
$stmt = $conn->prepare($sameTopicNewsQuery);
$stmt->bind_param('ss', $kategori, $id);
$stmt->execute();
$sameTopicNewsResult = $stmt->get_result();

if (!$sameTopicNewsResult) {
    die("Query gagal: " . $conn->error);
}

// Query untuk mendapatkan berita acak
$randomNewsQuery = "SELECT id, judul, konten_artikel, tanggal_dibuat, kategori FROM berita ORDER BY RAND() LIMIT 4";
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
        // Tambahkan bookmark baru dengan ID acak
        $randomId = generateRandomId();
        $query = "INSERT INTO bookmark (id, user_id, berita_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $randomId, $user_id, $berita_id);
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
                 WHERE k.berita_id = ? 
                 ORDER BY k.tanggal_komentar DESC";
$stmt = $conn->prepare($commentQuery);
$stmt->bind_param('s', $id);
$stmt->execute();
$commentResult = $stmt->get_result();
$commentCount = $commentResult->num_rows;
?>

<?php include '../header & footer/header.php'; ?>

<!-- Tambahkan link Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container mx-auto mt-8 mb-16">
    <div class="flex flex-col lg:flex-row">
        <!-- Gambar Utama dan Paragraf -->
        <div class="w-full lg:w-2/3 pr-4">
            <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-md my-2"><?= htmlspecialchars($kategori) ?></span>
            <h1 class="text-3xl font-bold mt-2"><?= htmlspecialchars($judul) ?></h1>
            <div class="text-gray-500 text-sm mt-2">
                <span>Penulis: <?= htmlspecialchars($penulis) ?></span> | 
                <span><?= date('d F Y, H:i', strtotime($tanggalDibuat)) ?> WIB</span>
            </div>
            <div class="mt-4">
                <!-- Tampilkan gambar pertama dengan ukuran besar -->
                <?php if ($gambarPertama): ?>
                    <img src="<?= htmlspecialchars($gambarPertama) ?>" class="w-full h-auto object-cover rounded-lg my-4" style="max-width: 100%; height: auto;">
                <?php endif; ?>
                <!-- Tampilkan konten artikel tanpa gambar pertama -->
                <div class="mt-4">
                    <?= $konten ?>
                </div>

                <!-- Box Like, Dislike, Share, Bookmark -->
                <div class="flex space-x-4 mt-4">
                    <form method="post" action="">
                        <input type="hidden" name="reaction" value="Suka">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded <?= $userReaction === 'Suka' ? 'bg-blue-100' : '' ?>">
                            <i class="fas fa-thumbs-up"></i> <?= $likeCount ?>
                        </button>
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="reaction" value="Tidak Suka">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded <?= $userReaction === 'Tidak Suka' ? 'bg-blue-100' : '' ?>">
                            <i class="fas fa-thumbs-down"></i> <?= $dislikeCount ?>
                        </button>
                    </form>
                    <button id="shareButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                    <form method="post" action="">
                        <input type="hidden" name="bookmark" value="toggle">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-3 rounded <?= $isBookmarked ? 'bg-blue-100' : '' ?>">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </form>
                </div>

                <!-- Label Section -->
                <div class="mt-4">
                    <span class="block text-gray-700 font-bold">Label:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <?php foreach ($tags as $tag): ?>
                            <span class="inline-block bg-white text-blue-500 border border-blue-500 px-3 py-1 rounded-full"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Komentar -->
                <div class="mt-4 w-full pr-4">
                    <span id="commentCount" class="block text-gray-700 font-bold mb-2">Komentar (<?= $commentCount ?>)</span>
                    <div class="flex items-center mb-4">
                        <input id="commentInput" type="text" placeholder="Tulis komentarmu disini" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button id="sendCommentButton" class="ml-2 bg-blue-500 text-white rounded-full flex items-center justify-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </button>
                    </div>
                    <div id="commentsContainer" class="border border-gray-300 rounded-lg p-4 overflow-y-auto text-left" style="height: 24rem;">
                        <?php if ($commentCount > 1): ?>
                            <div class="relative mb-4">
                                <button id="filterButton" class="flex items-center text-gray-700 font-bold">
                                    Terbaru <i class="fas fa-chevron-down ml-2"></i>
                                </button>
                                <div id="filterMenu" class="absolute left-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                                    <div class="p-4">
                                        <button id="filterNewest" class="block w-full text-left font-bold">Terbaru</button>
                                        <p class="text-sm text-gray-500">Tampilkan semua komentar, yang terbaru lebih dahulu</p>
                                    </div>
                                    <div class="p-4">
                                        <button id="filterOldest" class="block w-full text-left font-bold">Terlama</button>
                                        <p class="text-sm text-gray-500">Tampilkan semua komentar, yang terlama lebih dahulu</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($commentResult->num_rows === 0): ?>
                            <div id="noComments" class="flex flex-col items-center justify-center h-full">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama untuk memberikan komentar!</p>
                            </div>
                        <?php endif; ?>
                        <?php while ($comment = $commentResult->fetch_assoc()): ?>
                            <div class="mb-4 user-comment group" data-comment-id="<?= $comment['id'] ?>">
                                <div class="flex items-start">
                                    <img src="data:image/jpeg;base64,<?= base64_encode($comment['profile_pic']) ?>" alt="Profile Picture" class="w-10 h-10 rounded-full mr-2 flex-shrink-0">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold"><?= htmlspecialchars($comment['nama_pengguna']) ?></span>
                                            <span class="text-gray-500 text-sm"><?= timeAgo($comment['tanggal_komentar']) ?></span>
                                            <?php if ($comment['user_id'] === $user_id): ?>
                                                <button class="options-button text-gray-500 hover:text-gray-700 hidden group-hover:inline-flex">
                                                    <i class="fas fa-ellipsis-h text-xs"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mt-1 break-words max-w-full"><?= htmlspecialchars($comment['teks_komentar']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berita Teratas Hari Ini dan Label -->
        <div class="w-full lg:w-1/3 pl-4 mt-28 lg:mt-32">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Berita Teratas Hari Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <ul class="pl-4">
                <?php $i = 1; ?>
                <?php while ($topNews = $topNewsResult->fetch_assoc()): ?>
                    <li class="mb-4">
                        <div class="flex items-center">
                            <span class="text-[#CAD2FF] font-semibold italic text-5xl mr-4"><?= $i ?></span>
                            <div>
                                <span class="text-gray-400 text-sm"><?= date('d F Y', strtotime($topNews['tanggal_dibuat'])) ?></span>
                                <a href="news-detail.php?id=<?= $topNews['id'] ?>">
                                    <h3 class="text-lg font-bold mt-1"><?= htmlspecialchars($topNews['judul']) ?></h3>
                                </a>
                                <div class="border-b border-gray-300 mt-2"></div>
                            </div>
                        </div>
                    </li>
                    <?php $i++; ?>
                <?php endwhile; ?>
            </ul>
            
            <!-- Baru Baru Ini -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0 mb-4"></div>
                <ul class="pl-4">
                    <?php while ($recentNews = $recentNewsResult->fetch_assoc()): ?>
                        <li class="mb-4">
                            <div>
                                <span class="text-gray-400 text-sm"><?= date('d F Y', strtotime($recentNews['tanggal_dibuat'])) ?></span>
                                <a href="news-detail.php?id=<?= $recentNews['id'] ?>">
                                    <h3 class="text-lg font-bold mt-1"><?= htmlspecialchars($recentNews['judul']) ?></h3>
                                </a>
                                <div class="border-b border-gray-300 mt-2"></div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Berita dengan Topik yang Sama -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Berita dengan Topik yang Sama</span>
                <div class="border-b-4 border-[#FF3232] mt-0 mb-4"></div>
                <div class="flex flex-col gap-4">
                    <?php while ($sameTopicNews = $sameTopicNewsResult->fetch_assoc()): ?>
                        <?php
                        // Ekstrak URL gambar pertama dari konten artikel
                        preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $sameTopicNews['konten_artikel'], $matches);
                        $gambar = $matches[1] ?? 'https://via.placeholder.com/600x330'; // Gunakan placeholder jika tidak ada gambar
                        ?>
                        <div class="relative overflow-hidden rounded-lg">
                            <a href="news-detail.php?id=<?= $sameTopicNews['id'] ?>">
                                <img src="<?= htmlspecialchars($gambar) ?>" class="w-full h-auto object-cover rounded-lg">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                    <h3 class="text-white text-lg font-bold"><?= htmlspecialchars($sameTopicNews['judul']) ?></h3>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Berita Lainnya -->
    <div class="mt-8">
        <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
        <div class="border-b-4 border-[#45C630] mt-0 mb-4"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php while ($news = $randomNewsResult->fetch_assoc()): ?>
                <?php
                // Ekstrak URL gambar pertama dari konten artikel
                preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $news['konten_artikel'], $matches);
                $gambar = $matches[1] ?? 'https://via.placeholder.com/600x330'; // Gunakan placeholder jika tidak ada gambar

                // Ambil deskripsi singkat dari konten_artikel dan hapus &nbsp;
                $description = strip_tags($news['konten_artikel']);
                $description = str_replace('&nbsp;', ' ', $description);
                $description = substr($description, 0, 150) . '...'; // Potong deskripsi
                ?>
                <div class="relative">
                    <a href="news-detail.php?id=<?= $news['id'] ?>">
                        <img src="<?= htmlspecialchars($gambar) ?>" class="w-full h-96 object-cover rounded-lg">
                    </a>
                    <div class="p-4">
                        <span class="text-red-500 font-bold"><?= htmlspecialchars($news['kategori']) ?></span> 
                        <span class="text-gray-500">| <?= date('d F Y', strtotime($news['tanggal_dibuat'])) ?></span>
                        <a href="news-detail.php?id=<?= $news['id'] ?>">
                            <h3 class="text-lg font-bold mt-1"><?= htmlspecialchars($news['judul']) ?></h3>
                        </a>
                        <p class="text-gray-700 mt-2"><?= htmlspecialchars($description) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg transform scale-95 transition-transform duration-300">
        <p class="mb-4">Apakah Anda yakin ingin menghapus komentar ini?</p>
        <div class="flex justify-end">
            <button id="cancelDelete" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Batal</button>
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
        </div>
    </div>
</div>

<script>
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 31536000);

        if (interval > 1) return interval + " tahun yang lalu";
        interval = Math.floor(seconds / 2592000);
        if (interval > 1) return interval + " bulan yang lalu";
        interval = Math.floor(seconds / 86400);
        if (interval > 1) return interval + " hari yang lalu";
        interval = Math.floor(seconds / 3600);
        if (interval > 1) return interval + " jam yang lalu";
        interval = Math.floor(seconds / 60);
        if (interval > 1) return interval + " menit yang lalu";
        return "baru saja";
    }

    function updateCommentCount() {
        const commentsContainer = document.getElementById('commentsContainer');
        const commentCount = commentsContainer.querySelectorAll('.user-comment').length;
        document.getElementById('commentCount').textContent = `Komentar (${commentCount})`;

        const noComments = document.getElementById('noComments');
        if (commentCount > 0) {
            noComments.classList.add('hidden');
        } else {
            noComments.classList.remove('hidden');
        }

        const filterButton = document.getElementById('filterButton');
        if (commentCount > 1) {
            filterButton.classList.remove('hidden');
        } else {
            filterButton.classList.add('hidden');
        }
    }

    function addComment() {
        const commentInput = document.getElementById('commentInput');
        const commentText = commentInput.value.trim();
        if (commentText) {
            fetch('news-detail.php?id=<?= $id ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    comment: commentText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const userName = '<?= htmlspecialchars($namaPengguna) ?>';
                    const profilePic = 'data:image/jpeg;base64,<?= base64_encode($profilePic) ?>';
                    const commentDate = new Date();

                    const commentHtml = `
                        <div class="mb-4 user-comment opacity-0 transition-opacity duration-500 group">
                            <div class="flex items-start">
                                <img src="${profilePic}" alt="Profile Picture" class="w-10 h-10 rounded-full mr-2 flex-shrink-0">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold">${userName}</span>
                                        <span class="text-gray-500 text-sm">${timeAgo(commentDate)}</span>
                                        <button class="options-button hidden group-hover:inline-flex text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-ellipsis-h text-xs"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 break-words max-w-full comment-text">${commentText}</p>
                                    <button class="read-more text-blue-500 hover:underline text-sm hidden">Baca Selengkapnya</button>
                                </div>
                            </div>
                        </div>
                    `;

                    commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
                    const newComment = commentsContainer.firstElementChild;
                    setTimeout(() => newComment.classList.remove('opacity-0'), 10);
                    commentInput.value = '';
                    updateCommentCount();
                    handleReadMore(newComment.querySelector('.comment-text'), newComment.querySelector('.read-more'));
                } else {
                    alert(data.message || 'Gagal menambahkan komentar.');
                }
            });
        }
    }

    function handleReadMore(commentTextElement, readMoreButton) {
        const maxLength = 100; // Set the maximum length for the comment preview
        const fullText = commentTextElement.textContent;
        if (fullText.length > maxLength) {
            const previewText = fullText.substring(0, maxLength) + '...';
            commentTextElement.textContent = previewText;
            readMoreButton.classList.remove('hidden');

            readMoreButton.addEventListener('click', function () {
                if (commentTextElement.textContent === previewText) {
                    commentTextElement.textContent = fullText;
                    readMoreButton.textContent = 'Sembunyikan';
                } else {
                    commentTextElement.textContent = previewText;
                    readMoreButton.textContent = 'Baca Selengkapnya';
                }
            });
        }
    }

    document.getElementById('sendCommentButton').addEventListener('click', addComment);

    document.getElementById('commentInput').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addComment();
        }
    });

    let commentToDelete = null;

    function showPopup() {
        const popup = document.getElementById('popup');
        popup.classList.remove('hidden');
        document.querySelector('#popup div').classList.add('scale-100');
    }

    function closePopup() {
        const popup = document.getElementById('popup');
        popup.classList.add('hidden');
        document.querySelector('#popup div').classList.remove('scale-100');
    }

    document.getElementById('commentsContainer').addEventListener('click', function (e) {
        const optionsButton = e.target.closest('.options-button');

        if (optionsButton) {
            const commentDiv = optionsButton.closest('.user-comment');
            commentToDelete = commentDiv;
            showPopup();
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (commentToDelete) {
            const commentId = commentToDelete.dataset.commentId;

            // Buat form untuk mengirim permintaan POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_comment_id';
            input.value = commentId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

            // Setelah form submit, hapus elemen komentar dari DOM
            commentToDelete.remove();
            updateCommentCount();
        }
    });

    document.getElementById('cancelDelete').addEventListener('click', closePopup);

    document.getElementById('shareButton').addEventListener('click', function () {
        const url = window.location.href; // Mendapatkan URL lengkap halaman
        navigator.clipboard.writeText(url).then(() => {
            alert('URL berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin URL: ', err);
        });
    });

    document.getElementById('bookmarkButton').addEventListener('click', function () {
        const bookmarked = this.dataset.bookmarked === 'true';
        this.dataset.bookmarked = !bookmarked;
        this.classList.toggle('text-blue-500', !bookmarked);
        this.classList.toggle('text-gray-500', bookmarked);
        alert(bookmarked ? 'Bookmark dihapus' : 'Ditambahkan ke bookmark');
    });

    // Initial update of comment count
    updateCommentCount();

    document.addEventListener('DOMContentLoaded', function() {
        updateCommentCount();
    });

    // Filter functionality
    const filterButton = document.getElementById('filterButton');
    const filterMenu = document.getElementById('filterMenu');
    const filterNewest = document.getElementById('filterNewest');
    const filterOldest = document.getElementById('filterOldest');

    filterButton.addEventListener('click', function() {
        filterMenu.classList.toggle('hidden');
    });

    filterNewest.addEventListener('click', function() {
        sortComments('newest');
        filterButton.textContent = 'Terbaru';
        filterMenu.classList.add('hidden');
    });

    filterOldest.addEventListener('click', function() {
        sortComments('oldest');
        filterButton.textContent = 'Terlama';
        filterMenu.classList.add('hidden');
    });

    function sortComments(order) {
        const commentsContainer = document.getElementById('commentsContainer');
        const comments = Array.from(commentsContainer.querySelectorAll('.user-comment'));
        comments.sort((a, b) => {
            const dateA = new Date(a.querySelector('.text-gray-500').textContent);
            const dateB = new Date(b.querySelector('.text-gray-500').textContent);
            return order === 'newest' ? dateB - dateA : dateA - dateB;
        });
        comments.forEach(comment => commentsContainer.appendChild(comment));
    }
</script>

<?php include '../header & footer/footer.php'; ?>