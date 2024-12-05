<?php
session_start(); // Pastikan sesi dimulai

include '../connection/config.php'; // Pastikan path ini sesuai dengan struktur folder Anda
include '../header & footer/header.php'; 
include '../api/berita/detail_berita.php'; 
?>

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

                <!-- Box Like, Dislike, Share, Bookmark -->
                <div class="flex space-x-4 mt-4">
                    <form method="post" action="">
                        <input type="hidden" name="reaction" value="Suka">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded <?= $userReaction === 'Suka' ? 'text-blue-500' : '' ?>">
                            <i class="fas fa-thumbs-up"></i> <?= $likeCount ?>
                        </button>
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="reaction" value="Tidak Suka">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded <?= $userReaction === 'Tidak Suka' ? 'text-blue-500' : '' ?>">
                            <i class="fas fa-thumbs-down"></i> <?= $dislikeCount ?>
                        </button>
                    </form>
                    <button id="shareButton" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                    <form method="post" action="">
                        <input type="hidden" name="bookmark" value="toggle">
                        <button type="submit" class="flex items-center border border-blue-500 text-gray-500 px-4 py-3 rounded <?= $isBookmarked ? 'text-blue-500' : '' ?>">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </form>
                    <button id="reportButton" onclick="showcode()" class="flex items-center border border-blue-500 text-gray-500 px-4 py-2 rounded">
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
                                        <p class="mt-1 break-words max-w-full comment-text"><?= htmlspecialchars($comment['teks_komentar']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berita Teratas Hari Ini dan Label -->
        <div class="w-full lg:w-1/3 lg:pl-4 mt-16 lg:mt-20">
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
                                <span class="text-gray-400 text-sm"><?= date('d F Y', strtotime($topNews['tanggal_diterbitkan'])) ?></span>
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
                                <span class="text-gray-400 text-sm"><?= date('d F Y', strtotime($recentNews['tanggal_diterbitkan'])) ?></span>
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
</div>

<div class="container mx-auto max-w-screen-xl mt-8 px-4 md:px-6 lg:px-8">
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
                        <span class="text-gray-500">| <?= date('d F Y', strtotime($news['tanggal_diterbitkan'])) ?></span>
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

<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg transform scale-95 transition-transform duration-300">
        <p class="mb-4">Apakah Anda yakin ingin menghapus komentar ini?</p>
        <div class="flex justify-end">
            <button id="cancelDelete" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Batal</button>
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
        </div>
    </div>
</div>

<!-- Modal Pelaporan -->
<div id="reportModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-20">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4">Laporkan Artikel</h2>
        <form id="reportForm">
            <div class="mb-4">
                <label class="block mb-2">Pilih alasan pelaporan:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Konten seksual" class="form-radio">
                        <span class="ml-2">Konten seksual</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Konten kekerasan atau menjijikkan" class="form-radio">
                        <span class="ml-2">Konten kekerasan atau menjijikkan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Konten kebencian atau pelecehan" class="form-radio">
                        <span class="ml-2">Konten kebencian atau pelecehan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Tindakan berbahaya" class="form-radio">
                        <span class="ml-2">Tindakan berbahaya</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Spam atau misinformasi" class="form-radio">
                        <span class="ml-2">Spam atau misinformasi</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Masalah hukum" class="form-radio">
                        <span class="ml-2">Masalah hukum</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="reportReason" value="Teks bermasalah" class="form-radio">
                        <span class="ml-2">Teks bermasalah</span>
                    </label>
                </div>
            </div>
            <div class="mb-4 hidden" id="additionalOptions">
                <label class="block mb-2">Pilih satu:</label>
                <select class="form-select w-full">
                    <option>Penyalahgunaan obat-obatan atau narkoba</option>
                    <option>Penyalahgunaan api atau bahan peledak</option>
                    <option>Bunuh diri atau menyakiti diri sendiri</option>
                    <option>Tindakan berbahaya lainnya</option>
                </select>
            </div>
            <button type="button" id="nextButton" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mt-4" disabled>Berikutnya</button>
        </form>
    </div>
</div>

<!-- Modal Laporan Tambahan -->
<div id="additionalReportModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4">Laporan Tambahan Opsional</h2>
        <textarea class="w-full border border-gray-300 rounded p-2" placeholder="Berikan detail tambahan" maxlength="500"></textarea>
        <div class="text-right text-sm text-gray-500">0/500</div>
        <button type="button" id="submitReportButton" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Laporkan</button>
    </div>
</div>

<!-- Modal Terima Kasih -->
<div id="thankYouModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <img src="https://img.icons8.com/ios-filled/50/000000/thank-you.png" alt="Thank You" class="mx-auto mb-4">
        <p>Terima kasih telah melaporkan artikel ini. Laporan Anda akan kami tinjau sesegera mungkin. Jika diperlukan, tindakan lebih lanjut akan diambil sesuai dengan kebijakan kami.</p>
        <button type="button" id="closeThankYouModal" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Tutup</button>
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
    function showModal() {
        const modal = document.getElementById('modal');
        modal.classList.remove('hidden');
        document.querySelector('#modal div').classList.add('scale-100');
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        const modal = document.getElementById('modal');
        modal.classList.add('hidden');
        document.querySelector('#modal div').classList.remove('scale-100');
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
            showModal();
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (commentToDelete) {
            const commentId = commentToDelete.dataset.commentId;
            deleteComment(commentId);
            closeModal();
        }
    });

    document.getElementById('cancelDelete').addEventListener('click', closeModal);

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

    document.addEventListener('DOMContentLoaded', function() {
        const reportButton = document.getElementById('reportButton');
        const reportModal = document.getElementById('reportModal');

        reportButton.addEventListener('click', function () {
            reportModal.classList.remove('hidden');
        });

        document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.getElementById('additionalOptions').classList.remove('hidden');
                document.getElementById('nextButton').classList.remove('bg-gray-300');
                document.getElementById('nextButton').classList.add('bg-blue-500');
                document.getElementById('nextButton').disabled = false;
            });
        });

        document.getElementById('nextButton').addEventListener('click', function () {
            reportModal.classList.add('hidden');
            document.getElementById('additionalReportModal').classList.remove('hidden');
        });

        document.getElementById('submitReportButton').addEventListener('click', function () {
            document.getElementById('additionalReportModal').classList.add('hidden');
            document.getElementById('thankYouModal').classList.remove('hidden');
        });

        document.getElementById('closeThankYouModal').addEventListener('click', function () {
            document.getElementById('thankYouModal').classList.add('hidden');
        });
    });
</script>

<?php include '../header & footer/footer.php'; ?>