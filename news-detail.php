<?php
function timeAgo($datetime) {
    $now = new DateTime();
    $interval = $now->diff($datetime);
    
    if ($interval->y > 0) return $interval->y . " tahun yang lalu";
    if ($interval->m > 0) return $interval->m . " bulan yang lalu";
    if ($interval->d > 0) return $interval->d . " hari yang lalu";
    if ($interval->h > 0) return $interval->h . " jam yang lalu";
    if ($interval->i > 0) return $interval->i . " menit yang lalu";
    return "baru saja";
}
?>

<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

<?php
$userId = 1; // Ganti dengan ID pengguna yang sesuai
$query = "SELECT nama_lengkap FROM user WHERE uid = $userId";
$result = mysqli_query($conn, $query);
$userName = mysqli_fetch_assoc($result)['nama_lengkap'];
?>

<!-- Tambahkan link Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container mx-auto mt-8 mb-16">
    <div class="flex flex-col lg:flex-row">
        <!-- Gambar Utama dan Paragraf -->
        <div class="w-full lg:w-2/3 pr-4">
            <span class="inline-block bg-red-500 text-white px-4 py-1 rounded-md">Kampus</span>
            <a href="news-detail.php">
                <h1 class="text-3xl font-bold mt-2">Tim Bridge Polije Raih Juara 2 SEABF Cup 2024</h1>
            </a>
            <span class="text-gray-500 text-sm">KabarE - 27 Januari 2025, 10:24 WIB</span>
            <a href="news-detail.php">
                <img src="https://via.placeholder.com/800x450" class="w-full h-auto object-cover rounded-lg my-4">
            </a>
            <div class="flex items-start mt-4">
                <div class="border-l-4 border-[#FF842D] mr-2">
                <p class="text-gray-700 italic pl-4">
                    Tim Bridge Polije berhasil meraih Juara 2 dalam 7th South East Asia Bridge Federation (SEABF) Cup dan 40th ASEAN Bridge Club Championship, yang diselenggarakan oleh South East Asia Bridge Federation.
                </p>
                </div>
            </div>
            <p class="text-gray-500 mt-2">Penulis: Abi</p>
            <p class="mt-4 text-gray-700">
                KBRN,Jember: Tim Bridge Polije berhasil meraih Juara 2 dalam 7th South East Asia Bridge Federation (SEABF) Cup dan 40th ASEAN Bridge Club Championship, yang diselenggarakan oleh South East Asia Bridge Federation.
                <br><br>
                Kegiatan bergengsi ini berlangsung dari tanggal 25 hingga 29 September 2024 di The Margo Hotel Depok, Jawa Barat.
                <br><br>
                Tim Bridge Polije terdiri dari Muhammad Sulaiman, Muhammad Surul, Nurisnawati, dan Firdatus Sholehah. Atlet Bridge tersebut menunjukkan kualitas permainan yang sangat baik dan berhasil melewati berbagai tantangan di turnamen yang diikuti oleh tim-tim dari negara-negara ASEAN lainnya.
                <br><br>
                Muhammad Sulaiman menyampaikan Senin (30/9/2024) bahwa mereka mendominasi permainan dan mengalami dua kali kekalahan.
                <br><br>
                “Di babak penyisihan, kami mendominasi permainan dengan hanya mengalami dua kali kekalahan dari total pertandingan. Kami merasa percaya diri dan bersemangat untuk melanjutkan ke babak berikutnya,” ujar Sulaiman Senin (30/9/2024).
                <br><br>
                Ia juga menambahkan bahwa strategi dan kerjasama tim yang solid menjadi kunci sukses mereka selama babak penyisihan. Namun, babak final menjadi tantangan yang lebih berat.
                <br><br>
                Tim Polije harus berhadapan dengan Timnas Indonesia U-26, Metaforsa, yang dikenal sebagai salah satu tim terbaik di Asia Tenggara. Meskipun mengalami kekalahan yang cukup telak di final, Muhammad Sulaiman tetap merasa bangga dengan pencapaian timnya.
                <br><br>
                “Kami kalah dari tim yang sangat kuat, namun kami mampu menunjukkan performa terbaik kami dengan mengalahkan Tim Thailand A dengan skor 48-12 IMPs (17,59-2,41 VP)" lanjut Sulaiman.
                <br><br>
                Tanpa pendampingan dari pelatih, tim Bridge Polije berhasil memaksimalkan kemampuan masing-masing anggota. Hal ini menunjukkan dedikasi dan komitmen mereka untuk berprestasi.
                <br><br>
                “Kami belajar banyak dari pengalaman ini. Kemenangan melawan Tim Thailand A menjadi momen yang sangat membanggakan dan memberi kami motivasi lebih untuk terus berlatih,” ungkap Sulaiman.
                <br><br>
                Prestasi ini menjadi sorotan tidak hanya bagi Polije tetapi juga bagi dunia bridge di Indonesia. Muhammad Sulaiman berharap agar kampus dapat memberikan dukungan lebih untuk tim bridge Polije di kejuaraan-kejuaraan mendatang.
                <br><br>
                “Kami berharap Polije bisa memberikan lebih banyak dukungan, baik dalam hal fasilitas latihan maupun pendanaan untuk mengikuti kejuaraan nasional dan internasional lainnya" ujar Sulaiman.
                <br><br>
                Prestasi tim Bridge Polije dalam ajang internasional ini tidak hanya mengangkat nama kampus, tetapi juga menjadi inspirasi bagi mahasiswa lainnya untuk mengejar prestasi di berbagai bidang. Diharapkan, dengan semangat dan kerja keras, tim Bridge Polije dapat terus meraih prestasi di pentas internasional dan membanggakan nama Indonesia di dunia olahraga.
            </p>

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
                <span id="commentCount" class="block text-gray-700 font-bold mb-2">Komentar (5)</span>
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
                                        <span class="font-semibold">Chiquita</span> · <span class="text-gray-500 text-sm time-ago"><?= timeAgo(new DateTime('@' . (time() - ($j * 60)))) ?></span>
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

            <!-- Label Terpopuler -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-[#FF5391] text-white px-6 py-1 rounded-t-md">Label Terpopuler</span>
                <div class="border-b-4 border-[#FF5391] mt-0 mb-4" style="width: 480px;"></div>
                <div class="flex flex-wrap gap-2" style="max-width: 480px;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="inline-block bg-white text-black border border-blue-500 px-3 py-1 rounded-full">Tag <?= $i ?></span>
                    <?php endfor; ?>
                </div>
            </div>

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

    <!-- Popup Konfirmasi Hapus Komentar -->
    <div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-2"></i>
                <h2 class="text-xl font-bold" id="popupTitle">Konfirmasi</h2>
            </div>
            <p class="mb-4" id="popupMessage">Apakah Anda yakin ingin menghapus komentar ini?</p>
            <div class="flex justify-end">
                <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Hapus</button>
                <button id="cancelDelete" class="bg-gray-300 text-black px-4 py-2 rounded">Batal</button>
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
                const userName = '<?= $userName ?>'; // Gunakan nama pengguna dari database
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
</div>

<?php include 'header & footer/footer.php'; ?>