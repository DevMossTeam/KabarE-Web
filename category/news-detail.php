<?php
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
    // Query untuk mendapatkan detail berita
    $query = "SELECT judul, konten_artikel, tanggal_dibuat FROM berita WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $berita = $result->fetch_assoc();

    if ($berita) {
        $judul = $berita['judul'];
        $konten = $berita['konten_artikel'];
        $tanggalDibuat = $berita['tanggal_dibuat'];

        // Ekstrak URL gambar pertama dari konten artikel dan hapus tag gambar dari konten
        preg_match('/<img.*?src=["\'](.*?)["\'].*?>/i', $konten, $matches);
        $gambar = $matches[1] ?? ''; // Ambil URL gambar pertama jika ada
        $konten = preg_replace('/<img.*?>/i', '', $konten); // Hapus semua tag gambar dari konten
    } else {
        echo "Berita tidak ditemukan.";
        exit;
    }
} else {
    echo "ID berita tidak valid.";
    exit;
}
?>

<?php include '../header & footer/header.php'; ?>

<!-- Tambahkan link Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container mx-auto mt-8 mb-16">
    <div class="flex flex-col lg:flex-row">
        <!-- Gambar Utama dan Paragraf -->
        <div class="w-full lg:w-2/3 pr-4">
            <h1 class="text-3xl font-bold mt-2"><?= htmlspecialchars($judul) ?></h1>
            <span class="text-gray-500 text-sm"><?= date('d F Y, H:i', strtotime($tanggalDibuat)) ?> WIB</span>
            <div class="mt-4">
                <!-- Tampilkan gambar utama dengan ukuran penuh jika ada -->
                <?php if ($gambar): ?>
                    <img src="<?= htmlspecialchars($gambar) ?>" class="w-full h-auto object-cover rounded-lg my-4" style="max-width: 100%; height: auto;">
                <?php endif; ?>
                <?= $konten ?>
            </div>

            <!-- Box Like, Dislike, Share -->
            <div class="flex space-x-4 mt-4">
                <button id="mainLikeButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded" data-liked="false">
                    <i class="fas fa-thumbs-up"></i>
                    <span class="ml-1 like-count">12</span>
                </button>
                <button id="mainDislikeButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded" data-disliked="false">
                    <i class="fas fa-thumbs-down"></i>
                    <span class="ml-1 dislike-count">22</span>
                </button>
                <button id="shareButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                    <i class="fa-solid fa-share-nodes"></i>
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
                <span id="commentCount" class="block text-gray-700 font-bold mb-2">Komentar (4)</span>
                <div class="flex items-center mb-4">
                    <input id="commentInput" type="text" placeholder="Tulis komentarmu disini" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="sendCommentButton" class="ml-2 bg-blue-500 text-white rounded-full flex items-center justify-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-paper-plane text-xl"></i>
                    </button>
                </div>
                <div id="commentsContainer" class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto text-left">
                    <?php for ($j = 0; $j < 4; $j++): ?>
                        <div class="mb-4 user-comment" data-timestamp="<?= time() - ($j * 60) ?>">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle text-2xl text-gray-500 mr-2"></i>
                                    <div>
                                        <span class="font-semibold">Chiquita</span> · <span class="text-gray-500 text-sm time-ago"><?= timeAgo(date('Y-m-d H:i:s', time() - ($j * 60))) ?></span>
                                        <p class="mt-2">Informasi yang sangat menarik</p>
                                    </div>
                                </div>
                                <button class="options-button">
                                    <i class="fas fa-ellipsis-v text-lg"></i>
                                </button>
                            </div>
                        </div>
                    <?php endfor; ?>
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
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <li class="mb-4">
                        <div class="flex items-center">
                            <span class="text-[#CAD2FF] font-semibold italic text-5xl mr-4"><?= $i ?></span>
                            <div>
                                <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                <a href="news-detail.php">
                                    <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                </a>
                                <div class="border-b border-gray-300 mt-2"></div>
                            </div>
                        </div>
                    </li>
                <?php endfor; ?>
            </ul>
            
            <!-- Baru Baru Ini -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0 mb-4"></div>
                <ul class="pl-4">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <li class="mb-4">
                            <div>
                                <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                <a href="news-detail.php">
                                    <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                </a>
                                <div class="border-b border-gray-300 mt-2"></div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>

            <!-- Berita dengan Topik yang Sama -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Berita dengan Topik yang Sama</span>
                <div class="border-b-4 border-[#FF3232] mt-0 mb-4"></div>
                <div class="flex flex-col gap-4">
                    <div class="relative overflow-hidden rounded-lg">
                        <a href="news-detail.php">
                            <img src="https://via.placeholder.com/600x330" class="w-full h-auto object-cover rounded-lg">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                <h3 class="text-white text-lg font-bold">Mahasiswa Polije Juara 1 Journalism Photography</h3>
                            </div>
                        </a>
                    </div>
                    <div class="relative overflow-hidden rounded-lg">
                        <a href="news-detail.php">
                            <img src="https://via.placeholder.com/600x330" class="w-full h-auto object-cover rounded-lg">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                <h3 class="text-white text-lg font-bold">Polije Selenggarakan Kick Off Program WMK</h3>
                            </div>
                        </a>
                    </div>
                    <div class="relative overflow-hidden rounded-lg">
                        <a href="news-detail.php">
                            <img src="https://via.placeholder.com/600x330" class="w-full h-auto object-cover rounded-lg">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                <h3 class="text-white text-lg font-bold">Sinergi Polije Latih dan Uji Kompetensi Barista untuk Pemula</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Berita Lainnya -->
    <div class="mt-8">
        <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
        <div class="border-b-4 border-[#45C630] mt-0 mb-4"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="flex flex-col">
                    <a href="news-detail.php">
                        <img src="https://via.placeholder.com/600x330" class="w-full h-auto object-cover rounded-lg">
                    </a>
                    <div class="mt-4">
                        <span class="text-red-500 font-bold">Kategori</span>
                        <span class="text-gray-500 text-sm ml-2">25 Januari 2025</span>
                        <a href="news-detail.php">
                            <h3 class="text-lg font-bold mt-2">Lorem Ipsum Dolor Sit Amet</h3>
                        </a>
                        <p class="text-gray-500 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            <?php endfor; ?>
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
        const commentCount = document.getElementById('commentsContainer').children.length;
        document.getElementById('commentCount').textContent = `Komentar (${commentCount})`;
    }

    function addComment() {
        const commentInput = document.getElementById('commentInput');
        const commentText = commentInput.value.trim();
        if (commentText) {
            const userName = 'Nama Pengguna'; // Ganti dengan nama pengguna yang sesuai
            const commentDate = new Date();

            const commentHtml = `
                <div class="mb-4 user-comment">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-2xl text-gray-500 mr-2"></i>
                            <div>
                                <span class="font-semibold">${userName}</span> · <span class="text-gray-500 text-sm">${timeAgo(commentDate)}</span>
                                <p class="mt-2">${commentText}</p>
                            </div>
                        </div>
                        <button class="options-button">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </button>
                    </div>
                </div>
            `;

            const commentsContainer = document.getElementById('commentsContainer');
            commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
            commentInput.value = '';
            updateCommentCount();
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

        if (!disliked) {
            const liked = this.dataset.liked === 'true';
            this.dataset.liked = !liked;
            likeCount.textContent = parseInt(likeCount.textContent) + (liked ? -1 : 1);
            this.classList.toggle('text-blue-500', !liked);
            this.classList.toggle('text-gray-500', liked);
        }
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
            commentToDelete.remove();
            updateCommentCount();
            closePopup();
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