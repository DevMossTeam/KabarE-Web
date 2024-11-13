<?php
session_start(); // Pastikan sesi dimulai

include '../connection/config.php'; // Pastikan path ini sesuai dengan struktur folder Anda

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

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
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
} else {
    echo "ID berita tidak valid.";
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
}
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
                    <button id="mainLikeButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded" data-liked="false">
                        <i class="fas fa-thumbs-up"></i>
                        <span class="ml-1 like-count">0</span>
                    </button>
                    <button id="mainDislikeButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded" data-disliked="false">
                        <i class="fas fa-thumbs-down"></i>
                        <span class="ml-1 dislike-count">0</span>
                    </button>
                    <button id="shareButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                    <button id="bookmarkButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded" data-bookmarked="false">
                        <i class="fas fa-bookmark"></i>
                    </button>
                </div>

                <!-- Label Section -->
                <div class="mt-4">
                    <span class="block text-gray-700 font-bold">Label:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="inline-block bg-white text-blue-500 border border-blue-500 px-3 py-1 rounded-full">ruangkelas</span>
                        <span class="inline-block bg-white text-blue-500 border border-blue-500 px-3 py-1 rounded-full">ruangkelas</span>
                        <span class="inline-block bg-white text-blue-500 border border-blue-500 px-3 py-1 rounded-full">ruangkelas</span>
                    </div>
                </div>

                <!-- Komentar -->
                <div class="mt-4 w-full pr-4">
                    <span id="commentCount" class="block text-gray-700 font-bold mb-2">Komentar (0)</span>
                    <div class="flex items-center mb-4">
                        <input id="commentInput" type="text" placeholder="Tulis komentarmu disini" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button id="sendCommentButton" class="ml-2 bg-blue-500 text-white rounded-full flex items-center justify-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </button>
                    </div>
                    <div id="commentsContainer" class="border border-gray-300 rounded-lg p-4 overflow-y-auto text-left" style="height: 24rem;">
                        <div id="noComments" class="flex flex-col items-center justify-center h-full">
                            <i class="fas fa-comments text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama untuk memberikan komentar!</p>
                        </div>
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
        const commentCount = commentsContainer.children.length - 1; // Kurangi placeholder
        document.getElementById('commentCount').textContent = `Komentar (${commentCount})`;

        const noComments = document.getElementById('noComments');
        if (commentCount > 0) {
            noComments.classList.add('hidden');
        } else {
            noComments.classList.remove('hidden');
        }
    }

    function addComment() {
        const commentInput = document.getElementById('commentInput');
        const commentText = commentInput.value.trim();
        if (commentText) {
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

    document.getElementById('commentsContainer').addEventListener('click', function (e) {
        const optionsButton = e.target.closest('.options-button');

        if (optionsButton) {
            const commentDiv = optionsButton.closest('.mb-4');
            if (commentDiv.classList.contains('user-comment')) {
                commentToDelete = commentDiv;
                showPopup();
            }
        }
    });

    document.getElementById('mainLikeButton').addEventListener('click', function () {
        const likeCount = this.querySelector('.like-count');
        const dislikeButton = document.getElementById('mainDislikeButton');
        const disliked = dislikeButton.dataset.disliked === 'true';

        if (disliked) {
            const dislikeCount = dislikeButton.querySelector('.dislike-count');
            dislikeButton.dataset.disliked = false;
            dislikeCount.textContent = parseInt(dislikeCount.textContent) - 1;
            dislikeButton.classList.remove('text-blue-500');
            dislikeButton.classList.add('text-gray-500');
        }

        const liked = this.dataset.liked === 'true';
        this.dataset.liked = !liked;
        likeCount.textContent = parseInt(likeCount.textContent) + (liked ? -1 : 1);
        this.classList.toggle('text-blue-500', !liked);
        this.classList.toggle('text-gray-500', liked);
    });

    document.getElementById('mainDislikeButton').addEventListener('click', function () {
        const dislikeCount = this.querySelector('.dislike-count');
        const likeButton = document.getElementById('mainLikeButton');
        const liked = likeButton.dataset.liked === 'true';

        if (liked) {
            const likeCount = likeButton.querySelector('.like-count');
            likeButton.dataset.liked = false;
            likeCount.textContent = parseInt(likeCount.textContent) - 1;
            likeButton.classList.remove('text-blue-500');
            likeButton.classList.add('text-gray-500');
        }

        const disliked = this.dataset.disliked === 'true';
        this.dataset.disliked = !disliked;
        dislikeCount.textContent = parseInt(dislikeCount.textContent) + (disliked ? -1 : 1);
        this.classList.toggle('text-blue-500', !disliked);
        this.classList.toggle('text-gray-500', disliked);
    });

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

    let commentToDelete = null;

    function showPopup() {
        const popup = document.getElementById('popup');
        popup.classList.remove('hidden');
        document.querySelector('#popup div').classList.add('scale-100');
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (commentToDelete) {
            commentToDelete.classList.add('opacity-0'); // Animasi keluar
            setTimeout(() => {
                commentToDelete.remove();
                updateCommentCount();
                closePopup();
            }, 500); // Waktu yang sama dengan durasi animasi
        }
    });

    document.getElementById('cancelDelete').addEventListener('click', closePopup);

    function closePopup() {
        const popup = document.getElementById('popup');
        popup.classList.add('hidden');
        document.querySelector('#popup div').classList.remove('scale-100');
    }
</script>

<?php include '../header & footer/footer.php'; ?>
