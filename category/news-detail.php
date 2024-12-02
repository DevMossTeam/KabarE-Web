<?php
session_start();
include '../connection/config.php';

// Tambahkan error logging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('error_log', '../error.log');

// Definisikan fungsi timeAgo di sini, sebelum digunakan
function timeAgo($timestamp) {
    $time_ago = time() - $timestamp;
    $periods = array(
        31536000 => 'tahun',
        2592000 => 'bulan',
        604800 => 'minggu',
        86400 => 'hari',
        3600 => 'jam',
        60 => 'menit',
        1 => 'detik'
    );

    foreach ($periods as $seconds => $name) {
        $division = floor($time_ago / $seconds);
        if ($division != 0) {
            $time_ago = $division;
            $period = $name;
            break;
        }
    }

    $output = $time_ago . ' ' . $period;
    if ($time_ago != 1) {
        // Tidak perlu menambahkan 's' untuk Bahasa Indonesia
    }

    return $output . ' yang lalu';
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$user_id = $_SESSION['user_id'] ?? null;

// Fungsi untuk memanggil API menggunakan cURL
function callAPI($url) {
    try {
        $ch = curl_init();
        if (!$ch) {
            throw new Exception("Couldn't initialize cURL");
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
        // Debug: Log raw response
        error_log("Raw API Response: " . $response);
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception($error['error'] ?? 'HTTP Error: ' . $httpCode);
        }
        
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Error: " . json_last_error_msg());
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }
        
        return $decodedResponse;
    } catch (Exception $e) {
        error_log("API Call Error: " . $e->getMessage());
        error_log("URL attempted: " . $url);
        throw $e;
    }
}

try {
    if (!$id) {
        throw new Exception("ID berita tidak ditemukan");
    }

    // Debug: Print host dan path
    error_log("HTTP_HOST: " . $_SERVER['HTTP_HOST']);
    error_log("DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT']);
    
    // Ubah cara membangun URL API
    $baseUrl = sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['HTTP_HOST']
    );
    
    $apiUrl = $baseUrl . "/api/berita/detail_berita.php?action=detail&id=" . $id;
    error_log("API URL: " . $apiUrl);
    
    $data = callAPI($apiUrl);
    
    if (!$data || isset($data['error'])) {
        throw new Exception($data['error'] ?? "Data tidak ditemukan");
    }

    // Extract data
    $berita = $data['berita'];
    $reaksi = $data['reaksi'];
    $userReaction = $data['userReaction'];
    $isBookmarked = $data['isBookmarked'];
    $tags = $data['tags'];
    $komentar = $data['komentar'];

    // Ekstrak data berita
    $judul = $berita['judul'];
    $konten = $berita['konten_artikel'];
    $tanggalDiterbitkan = $berita['tanggal_diterbitkan'];
    $kategori = $berita['kategori'];
    $penulis = $berita['nama_lengkap'];
    $namaPengguna = $berita['nama_pengguna'];
    $profilePic = $berita['profile_pic'];

    // Ekstrak URL gambar pertama dari konten artikel
    preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $konten, $matches);
    $gambarPertama = $matches[1] ?? '';
    $konten = preg_replace('/<img.*?>/i', '', $konten, 1);

    // Hitung jumlah komentar
    $commentCount = count($komentar);

    // Ekstrak data dengan nilai default
    $topNews = $data['topNews'] ?? [];
    $recentNews = $data['recentNews'] ?? [];
    $relatedNews = $data['relatedNews'] ?? [];
    $randomNews = $data['randomNews'] ?? [];

    // Debug data
    error_log("Extracted data - Top News: " . print_r($topNews, true));
    error_log("Extracted data - Recent News: " . print_r($recentNews, true));
    error_log("Extracted data - Related News: " . print_r($relatedNews, true));

    // Sisanya tetap sama seperti sebelumnya...
} catch (Exception $e) {
    error_log("Error in news-detail.php: " . $e->getMessage());
    die("Error: " . $e->getMessage());
}
?>

<?php include '../header & footer/header.php'; ?>

<!-- Tambahkan link Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container mx-auto max-w-screen-xl mt-8 mb-16 px-4 md:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row lg:space-x-4">
        <!-- Gambar Utama dan Paragraf -->
        <div class="w-full lg:w-2/3 lg:pr-4">
            <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-md my-2"><?= htmlspecialchars($kategori) ?></span>
            <h1 class="text-3xl font-bold mt-2"><?= htmlspecialchars($judul) ?></h1>
            <div class="text-gray-500 text-sm mt-2">
                <span>Penulis: <?= htmlspecialchars($penulis) ?></span> | 
                <span><?= date('d F Y', strtotime($tanggalDiterbitkan)) ?> WIB</span>
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

                <!-- Box Like, Dislike, Share, Bookmark, Report -->
                <div class="flex space-x-4 mt-4">
                    <!-- Like Button -->
                    <button onclick="handleReaction('Suka')" 
                            class="flex items-center border border-blue-500 px-4 py-2 rounded <?= $data['userReaction'] === 'Suka' ? 'text-blue-500' : 'text-gray-500' ?>">
                        <i class="fas fa-thumbs-up mr-2"></i>
                        <span id="likeCount"><?= $data['reaksi']['like_count'] ?? 0 ?></span>
                    </button>

                    <!-- Dislike Button -->
                    <button onclick="handleReaction('Tidak Suka')" 
                            class="flex items-center border border-blue-500 px-4 py-2 rounded <?= $data['userReaction'] === 'Tidak Suka' ? 'text-blue-500' : 'text-gray-500' ?>">
                        <i class="fas fa-thumbs-down mr-2"></i>
                        <span id="dislikeCount"><?= $data['reaksi']['dislike_count'] ?? 0 ?></span>
                    </button>

                    <!-- Share Button -->
                    <button onclick="handleShare()" 
                            class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>

                    <!-- Bookmark Button -->
                    <button onclick="handleBookmark()" 
                            class="flex items-center border border-blue-500 px-4 py-2 rounded <?= $data['isBookmarked'] ? 'text-blue-500' : 'text-gray-500' ?>">
                        <i class="fas fa-bookmark"></i>
                    </button>

                    <!-- Report Button -->
                    <button onclick="showReportModal()" 
                            class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                        <i class="fas fa-flag"></i>
                    </button>
                </div>

                <!-- Label Section -->
                <?php if (!empty($tags)): ?>
                <div class="mt-4">
                    <span class="block text-gray-700 font-bold">Label:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <?php foreach ($tags as $tag): ?>
                            <a href="../search.php?query=<?= urlencode($tag) ?>" class="inline-block bg-white text-blue-500 border border-blue-500 px-3 py-1 rounded-full"><?= htmlspecialchars($tag) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Komentar -->
                <div class="mt-4 w-full pr-4">
                    <span id="commentCount" class="block text-gray-700 font-bold mb-2">Komentar (<?= $commentCount ?>)</span>
                    <div class="flex items-center mb-4">
                        <input id="commentInput" type="text" placeholder="Tulis komentarmu disini" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button id="sendCommentButton" class="ml-2 bg-blue-500 text-white rounded-full flex items-center justify-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </button>
                    </div>
                    <div id="commentsContainer" class="border border-gray-300 rounded-lg p-4 overflow-y-auto text-left relative" style="height: 24rem;">
                        <?php if (empty($komentar)): ?>
                            <div id="noComments" class="flex flex-col items-center justify-center h-full">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama untuk memberikan komentar!</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php foreach ($komentar as $comment): ?>
                            <div class="mb-4 user-comment group" data-comment-id="<?= $comment['id'] ?>">
                                <div class="flex items-start">
                                    <img src="<?= !empty($comment['profile_pic']) ? 'data:image/jpeg;base64,' . $comment['profile_pic'] : '../assets/img/default-profile.png' ?>" 
                                         alt="Profile Picture" 
                                         class="w-10 h-10 rounded-full mr-2 flex-shrink-0 object-cover">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold"><?= htmlspecialchars($comment['nama_pengguna']) ?></span>
                                            <span class="text-gray-500 text-sm"><?= timeAgo(strtotime($comment['tanggal_komentar'])) ?></span>
                                            <?php if ($comment['user_id'] === $user_id): ?>
                                                <button class="options-button text-gray-500 hover:text-gray-700 hidden group-hover:inline-flex">
                                                    <i class="fas fa-ellipsis-h text-xs"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mt-1 break-words max-w-full comment-text">
                                            <?= htmlspecialchars($comment['teks_komentar']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berita Teratas Hari Ini dan Label -->
        <div class="w-full lg:w-1/3 lg:pl-4 mt-16 lg:mt-20">
            <!-- Berita Teratas Hari Ini -->
            <div class="mb-4 ml-[15px]">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">
                    Berita Teratas Hari Ini
                </span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <ul class="pl-0">
                <?php if (!empty($data['beritaTeratas'])): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($data['beritaTeratas'] as $beritaTop): ?>
                        <li class="mb-4 w-full">
                            <div class="flex items-start w-full">
                                <div class="min-w-[60px] flex justify-end">
                                    <span class="text-[#CAD2FF] font-semibold italic text-5xl"><?= $i ?></span>
                                </div>
                                <div class="flex-grow ml-4 w-full pr-0">
                                    <span class="text-gray-400 text-sm block">
                                        <?= date('d F Y', strtotime($beritaTop['tanggal_diterbitkan'])) ?>
                                    </span>
                                    <a href="news-detail.php?id=<?= $beritaTop['id'] ?>">
                                        <h3 class="text-lg font-bold mt-1 hover:text-blue-600">
                                            <?= htmlspecialchars($beritaTop['judul']) ?>
                                        </h3>
                                    </a>
                                    <div class="border-b border-gray-300 mt-2 w-full"></div>
                                </div>
                            </div>
                        </li>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-500 text-center py-4">
                        Tidak ada berita teratas saat ini.
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- Baru Baru Ini -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">
                    Baru Baru Ini
                </span>
                <div class="border-b-4 border-[#FFC300] mt-0 mb-4"></div>
                <div>
                    <?php if (!empty($data['beritaBaru'])): ?>
                        <?php foreach ($data['beritaBaru'] as $beritaBaru): ?>
                            <div class="mb-4">
                                <span class="text-gray-400 text-sm">
                                    <?= date('d F Y', strtotime($beritaBaru['tanggal_diterbitkan'])) ?>
                                </span>
                                <a href="news-detail.php?id=<?= $beritaBaru['id'] ?>">
                                    <h3 class="text-lg font-bold mt-1 hover:text-blue-600">
                                        <?= htmlspecialchars($beritaBaru['judul']) ?>
                                    </h3>
                                </a>
                                <div class="border-b border-gray-300 mt-2 w-full"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-gray-500 text-center py-4">
                            Tidak ada berita terbaru saat ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Berita dengan Topik yang Sama -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Berita dengan Topik yang Sama</span>
                <div class="border-b-4 border-[#FF3232] mt-0 mb-4"></div>
                <div class="flex flex-col gap-4">
                    <?php if (!empty($relatedNews)): ?>
                        <?php foreach ($relatedNews as $news): ?>
                            <?php
                            // Ekstrak URL gambar pertama dari konten artikel
                            preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $news['konten_artikel'], $matches);
                            $gambar = $matches[1] ?? 'https://via.placeholder.com/600x330';
                            ?>
                            <div class="relative overflow-hidden rounded-lg">
                                <a href="news-detail.php?id=<?= $news['id'] ?>">
                                    <img src="<?= htmlspecialchars($gambar) ?>" class="w-full h-auto object-cover rounded-lg">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                        <h3 class="text-white text-lg font-bold"><?= htmlspecialchars($news['judul']) ?></h3>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-gray-500">Tidak ada berita terkait saat ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto max-w-screen-xl mt-8 px-4 md:px-6 lg:px-8">
    <!-- Berita Lainnya -->
    <div class="mt-8">
        <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
        <div class="border-b-4 border-[#45C630] mt-0 mb-4"></div>
        
        <!-- Grid container untuk 4 berita -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (!empty($data['beritaLainnya'])): ?>
                <?php foreach ($data['beritaLainnya'] as $berita): ?>
                    <?php
                    // Ekstrak URL gambar pertama dari konten artikel
                    preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $berita['konten_artikel'], $matches);
                    $gambar = $matches[1] ?? 'https://via.placeholder.com/600x350';
                    ?>
                    <div class="w-full">
                        <a href="news-detail.php?id=<?= $berita['id'] ?>">
                            <img src="<?= htmlspecialchars($gambar) ?>" 
                                 class="w-full h-96 object-cover rounded-lg">
                        </a>
                        <div class="p-4" style="padding-left: 0; padding-right: 0;">
                            <span class="text-red-500 font-bold"><?= htmlspecialchars($berita['kategori']) ?></span>
                            <span class="text-gray-500"> | <?= date('d F Y', strtotime($berita['tanggal_diterbitkan'])) ?></span>
                            <a href="news-detail.php?id=<?= $berita['id'] ?>">
                                <h3 class="text-lg font-bold mt-1 hover:text-blue-600"><?= htmlspecialchars($berita['judul']) ?></h3>
                            </a>
                            <p class="text-gray-700 mt-2">
                                <?= substr(strip_tags($berita['konten_artikel']), 0, 150) ?>...
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-2 text-center text-gray-500 py-8">
                    Tidak ada berita lainnya saat ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Laporan Utama -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Laporkan Artikel</h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <!-- Radio buttons dengan dropdown -->
                <div class="space-y-3">
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="sexual" class="form-radio text-blue-500">
                            <span>Konten seksual</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="sexual-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="violence" class="form-radio text-blue-500">
                            <span>Konten kekerasan atau menjijikkan</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="violence-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="harassment" class="form-radio text-blue-500">
                            <span>Konten kebencian atau pelecehan</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="harassment-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="dangerous" class="form-radio text-blue-500">
                            <span>Tindakan berbahaya</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="dangerous-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="spam" class="form-radio text-blue-500">
                            <span>Spam atau misinformasi</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="spam-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="legal" class="form-radio text-blue-500">
                            <span>Masalah hukum</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="legal-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="reportReason" value="text" class="form-radio text-blue-500">
                            <span>Teks bermasalah</span>
                        </label>
                        <div class="hidden ml-7 mt-2" id="text-options">
                            <select class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Pilih alasan spesifik</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tombol Berikutnya -->
                <div class="flex justify-end">
                    <button id="nextButton" 
                            class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg transition-colors duration-200"
                            disabled>
                        Berikutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Tambahan -->
<div id="additionalDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Laporan Tambahan (Opsional)</h3>
                <button onclick="closeAdditionalDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-4">
                <div class="relative">
                    <textarea id="additionalDetail" 
                              class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 resize-none"
                              rows="6"
                              maxlength="500"
                              placeholder="Berikan detail tambahan"></textarea>
                    <div class="absolute bottom-2 right-2 text-sm text-gray-500">
                        <span id="charCount">0</span>/500
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="submitReport()" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Laporkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
            <img src="https://img.icons8.com/color/96/000000/checked--v1.png" 
                 class="mx-auto mb-4" 
                 alt="Success">
            <h3 class="text-xl font-semibold mb-2">Terima kasih sudah melaporkan artikel ini</h3>
            <p class="text-gray-600 mb-4">
                Laporan Anda akan kami tinjau sesegera mungkin. Jika diperlukan, tindakan lebih lanjut akan diambil sesuai dengan kebijakan kami.
            </p>
            <button onclick="closeAllModals()" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
const reportReasons = {
    sexual: ['Konten dewasa', 'Eksploitasi seksual', 'Nuditas'],
    violence: ['Kekerasan grafis', 'Konten menjijikkan', 'Kekejaman'],
    harassment: ['Bullying', 'Pelecehan', 'Ujaran kebencian'],
    dangerous: ['Membahayakan anak', 'Aktivitas berbahaya', 'Menghasut kekerasan'],
    spam: ['Informasi palsu', 'Spam', 'Penipuan'],
    legal: ['Pelanggaran hak cipta', 'Pencemaran nama baik', 'Masalah hukum lainnya'],
    text: ['Konten tidak pantas', 'Bahasa kasar', 'Provokasi']
};

let currentOpenOptions = null;

document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Sembunyikan opsi yang sebelumnya terbuka
        if (currentOpenOptions) {
            currentOpenOptions.classList.add('hidden');
        }

        // Tampilkan opsi untuk radio button yang dipilih
        const optionsDiv = document.getElementById(`${this.value}-options`);
        const select = optionsDiv.querySelector('select');
        
        // Reset dan isi ulang opsi dropdown
        select.innerHTML = '<option value="">Pilih alasan spesifik</option>';
        reportReasons[this.value].forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.textContent = option;
            select.appendChild(optionElement);
        });

        // Tampilkan dropdown
        optionsDiv.classList.remove('hidden');
        currentOpenOptions = optionsDiv;

        // Reset tombol Berikutnya
        const nextButton = document.getElementById('nextButton');
        nextButton.classList.add('bg-gray-300', 'text-gray-500');
        nextButton.classList.remove('bg-blue-500', 'text-white', 'hover:bg-blue-600');
        nextButton.disabled = true;
    });
});

// Event listener untuk semua dropdown
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        const nextButton = document.getElementById('nextButton');
        if (this.value) {
            nextButton.classList.remove('bg-gray-300', 'text-gray-500');
            nextButton.classList.add('bg-blue-500', 'text-white', 'hover:bg-blue-600');
            nextButton.disabled = false;
        } else {
            nextButton.classList.add('bg-gray-300', 'text-gray-500');
            nextButton.classList.remove('bg-blue-500', 'text-white', 'hover:bg-blue-600');
            nextButton.disabled = true;
        }
    });
});

document.getElementById('nextButton').addEventListener('click', function() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('additionalDetailModal').classList.remove('hidden');
});

document.getElementById('additionalDetail').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

function submitReport() {
    document.getElementById('additionalDetailModal').classList.add('hidden');
    document.getElementById('confirmationModal').classList.remove('hidden');
    
    // Setelah beberapa detik, tutup modal konfirmasi
    setTimeout(() => {
        closeAllModals();
        // Reset form setelah submit
        resetReportForm();
    }, 3000); // Tutup setelah 3 detik
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

function closeAdditionalDetailModal() {
    document.getElementById('additionalDetailModal').classList.add('hidden');
}

function closeAllModals() {
    const modals = [
        'reportModal',
        'additionalDetailModal',
        'confirmationModal'
    ];
    
    modals.forEach(modalId => {
        document.getElementById(modalId).classList.add('hidden');
    });
    
    // Reset form saat menutup semua modal
    resetReportForm();
}

// Modifikasi event listener untuk modal
document.addEventListener('click', function(event) {
    const reportModal = document.getElementById('reportModal');
    const additionalDetailModal = document.getElementById('additionalDetailModal');
    const confirmationModal = document.getElementById('confirmationModal');
    
    const modals = [
        {element: reportModal, contentClass: 'bg-white'},
        {element: additionalDetailModal, contentClass: 'bg-white'},
        {element: confirmationModal, contentClass: 'bg-white'}
    ];
    
    modals.forEach(modal => {
        if (event.target === modal.element) {
            // Klik terjadi pada overlay (di luar konten modal)
            modal.element.classList.add('hidden');
        }
        
        // Periksa apakah klik terjadi pada konten modal
        const isClickInsideContent = event.target.closest(`.${modal.contentClass}`);
        if (!isClickInsideContent && modal.element.contains(event.target)) {
            // Klik terjadi di luar konten modal tapi masih dalam modal overlay
            modal.element.classList.add('hidden');
        }
    });
});
</script>

<script>
    function timeAgo($timestamp) {
        $time_ago = time() - $timestamp;
        $periods = array(
            31536000 => 'tahun',
            2592000 => 'bulan',
            604800 => 'minggu',
            86400 => 'hari',
            3600 => 'jam',
            60 => 'menit',
            1 => 'detik'
        );

        foreach ($periods as $seconds => $name) {
            $division = floor($time_ago / $seconds);
            if ($division != 0) {
                $time_ago = $division;
                $period = $name;
                break;
            }
        }

        $output = $time_ago . ' ' . $period;
        if ($time_ago != 1) {
            // Tidak perlu menambahkan 's' untuk Bahasa Indonesia
        }

        return $output . ' yang lalu';
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
    }

    document.addEventListener('DOMContentLoaded', function() {
        const komentarIsiElements = document.querySelectorAll('.comment-text');
        const maxLength = 100; // Panjang maksimum sebelum "Baca Selengkapnya"

        komentarIsiElements.forEach(isiElement => {
            const fullText = isiElement.textContent.trim();
            if (fullText.length > maxLength) {
                const truncatedText = fullText.substring(0, maxLength) + '... ';
                const readMoreLink = document.createElement('span');
                readMoreLink.textContent = 'Baca Selengkapnya';
                readMoreLink.classList.add('read-more', 'text-blue-500', 'hover:underline', 'cursor-pointer', 'text-sm');
                
                let isExpanded = false;

                readMoreLink.onclick = function() {
                    if (isExpanded) {
                        isiElement.textContent = truncatedText;
                        isiElement.appendChild(readMoreLink);
                        readMoreLink.textContent = 'Baca Selengkapnya';
                    } else {
                        isiElement.textContent = fullText;
                        isiElement.appendChild(readMoreLink);
                        readMoreLink.textContent = 'Sembunyikan';
                    }
                    isExpanded = !isExpanded;
                };

                isiElement.textContent = truncatedText;
                isiElement.appendChild(readMoreLink);
            }
        });
    });

    function deleteComment(commentId) {
        fetch('news-detail.php?id=<?= $id ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                delete_comment_id: commentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                if (commentElement) {
                    commentElement.remove();
                    updateCommentCount();
                    // Check if there are no comments left
                    if (document.querySelectorAll('.user-comment').length === 0) {
                        document.getElementById('noComments').classList.remove('hidden');
                    }
                }
            } else {
                alert(data.message || 'Gagal menghapus komentar.');
            }
        });
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
                        <div class="mb-4 user-comment opacity-0 transition-opacity duration-500 group" data-comment-id="${data.commentId}">
                            <div class="flex items-start">
                                <img src="${profilePic}" alt="Profile Picture" class="w-10 h-10 rounded-full mr-2 flex-shrink-0 object-cover">
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

                    // Hide the "no comments" message
                    document.getElementById('noComments').classList.add('hidden');
                } else {
                    alert(data.message || 'Gagal menambahkan komentar.');
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

    // Fungsi untuk menampilkan modal
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.querySelector('div').classList.add('scale-100');
    }

    // Fungsi untuk menutup modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.querySelector('div').classList.remove('scale-100');
    }

    // Tambahkan event listener untuk menutup modal saat mengklik di luar modal
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('modal');
        const modalContent = modal.querySelector('div');
        if (!modalContent.contains(event.target) && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Tambahkan event listener untuk menutup modal saat menekan tombol Esc
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Event listener untuk menampilkan modal saat klik tombol opsi
    document.getElementById('commentsContainer').addEventListener('click', function (e) {
        const optionsButton = e.target.closest('.options-button');

        if (optionsButton) {
            e.stopPropagation(); // Mencegah event bubbling yang dapat menutup modal
            const commentDiv = optionsButton.closest('.user-comment');
            commentToDelete = commentDiv;
            showModal('deleteModal');
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (commentToDelete) {
            const commentId = commentToDelete.dataset.commentId;
            deleteComment(commentId);
            closeModal('deleteModal');
        }
    });

    document.getElementById('cancelDelete').addEventListener('click', function () {
        closeModal('deleteModal');
    });

    document.getElementById('shareButton').addEventListener('click', function () {
        const url = window.location.href; // Mendapatkan URL lengkap halaman
        navigator.clipboard.writeText(url).then(() => {
            alert('URL berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin URL: ', err);
        });
    });

    document.getElementById('bookmarkButton').addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');
        form.submit();
        this.classList.toggle('bg-blue-100');
        this.classList.toggle('text-blue-500');
        this.classList.toggle('bg-white');
        this.classList.toggle('text-gray-500');
    });

    // Initial update of comment count
    updateCommentCount();

    document.addEventListener('DOMContentLoaded', function() {
        updateCommentCount();
    });

    // Event listener untuk menampilkan modal saat klik tombol "Report"
    document.getElementById('reportButton').addEventListener('click', function () {
        showModal('reportModal');
    });

    // Event listener untuk menutup modal "Report"
    document.getElementById('cancelReport').addEventListener('click', function () {
        closeModal('reportModal');
    });

    // Event listener untuk mengirim laporan
    document.getElementById('reportForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const reason = document.getElementById('reportReason').value.trim();
        if (reason) {
            // Kirim laporan ke server (implementasi tergantung pada backend Anda)
            alert('Laporan telah dikirim.');
            closeModal('reportModal');
        } else {
            alert('Silakan masukkan alasan laporan.');
        }
    });

    async function handleReaction(type) {
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert('Silakan login terlebih dahulu untuk memberikan reaksi.');
            return;
        <?php endif; ?>

        try {
            const response = await fetch('<?= $baseUrl ?>/api/berita/detail_berita.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=detail&id=<?= $id ?>&reaction=${type}`
            });

            const data = await response.json();
            if (data.success) {
                // Refresh halaman untuk memperbarui tampilan
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function handleBookmark() {
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert('Silakan login terlebih dahulu untuk menambahkan bookmark.');
            return;
        <?php endif; ?>

        try {
            const response = await fetch('<?= $baseUrl ?>/api/berita/detail_berita.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=detail&id=<?= $id ?>&bookmark=toggle`
            });

            const data = await response.json();
            if (data.success) {
                // Refresh halaman untuk memperbarui tampilan
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function handleShare() {
        const url = window.location.href;
        navigator.clipboard.writeText(url)
            .then(() => {
                alert('Link berhasil disalin!');
            })
            .catch(err => {
                console.error('Gagal menyalin link:', err);
                alert('Gagal menyalin link');
            });
    }

    function resetReportForm() {
        // Reset radio buttons
        document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
            radio.checked = false;
        });

        // Sembunyikan semua dropdown
        document.querySelectorAll('[id$="-options"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });

        // Reset dropdown selections
        document.querySelectorAll('select').forEach(select => {
            select.value = '';
        });

        // Reset textarea di modal detail tambahan
        document.getElementById('additionalDetail').value = '';
        document.getElementById('charCount').textContent = '0';

        // Reset tombol Berikutnya
        const nextButton = document.getElementById('nextButton');
        nextButton.classList.add('bg-gray-300', 'text-gray-500');
        nextButton.classList.remove('bg-blue-500', 'text-white', 'hover:bg-blue-600');
        nextButton.disabled = true;
    }

    function showReportModal() {
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert('Silakan login terlebih dahulu untuk melaporkan konten.');
            return;
        <?php endif; ?>
        
        // Reset form sebelum menampilkan modal
        resetReportForm();
        document.getElementById('reportModal').classList.remove('hidden');
    }
</script>

<?php include '../header & footer/footer.php'; ?>