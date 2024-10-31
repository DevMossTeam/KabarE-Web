<?php include 'header & footer/header.php'; ?>

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
        </div>

        <!-- Baru Baru Ini dan Label -->
        <div class="w-full lg:w-1/3 pl-4 mt-8 lg:mt-12">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
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

            <!-- Label Section -->
            <div class="mt-8 lg:ml-4">
                <span class="inline-block bg-green-500 text-white px-6 py-1 rounded-t-md">Label</span>
                <div class="border-b-4 border-green-500 mt-0 mb-4" style="width: 480px;"></div>
                <div class="flex flex-wrap gap-2" style="max-width: 480px;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="inline-block bg-white text-black border border-blue-500 px-3 py-1 rounded-full">Tag <?= $i ?></span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Box Like, Dislike, Share -->
    <div class="flex space-x-4 mt-8">
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

    <!-- Label dan Komentar -->
    <div class="mt-4">
        <span id="commentCount" class="block text-gray-700 font-bold">Komentar (2)</span>
        <div class="flex items-center mt-2 mb-4">
            <div class="bg-blue-500 px-2 py-1 rounded-l-md">
                <i class="fas fa-comment-dots text-white text-2xl"></i>
            </div>
            <input id="commentInput" type="text" placeholder="Tulis Komentar" class="flex-1 border-t border-r border-b border-gray-300 rounded-r-md px-4 py-2">
        </div>
        <div id="commentsContainer" class="border border-gray-300 rounded p-4 max-h-96 overflow-y-auto">
            <?php for ($j = 0; $j < 4; $j++): ?>
                <div class="mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-2xl text-gray-500 mr-2"></i>
                        <div>
                            <span class="font-semibold">Clairina</span> · <span class="text-gray-500 text-sm">28 Sept</span>
                        </div>
                    </div>
                    <p class="mt-2">Kerenn</p>
                    <div class="flex items-center text-gray-500 text-sm mt-1">
                        <button class="like-button flex items-center mr-4 transition-colors duration-300" data-liked="false">
                            <i class="fas fa-thumbs-up mr-1"></i> <span class="like-count">20</span>
                        </button>
                        <button class="dislike-button flex items-center transition-colors duration-300" data-disliked="false">
                            <i class="fas fa-thumbs-down mr-1"></i> <span class="dislike-count">2</span>
                        </button>
                        <span class="ml-4">Balasan</span>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Berita Lainnya -->
    <div class="mt-8">
        <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
        <div class="border-b-4 border-[#FF3232] mt-0 mb-4"></div>
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

    <script>
        function updateCommentCount() {
            const commentCount = document.getElementById('commentsContainer').children.length;
            document.getElementById('commentCount').textContent = `Komentar (${commentCount})`;
        }

        document.getElementById('commentInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const commentText = this.value.trim();
                if (commentText) {
                    const userName = 'NamaUser'; // Ganti dengan nama user yang login
                    const commentDate = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });

                    const commentHtml = `
                        <div class="mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-2xl text-gray-500 mr-2"></i>
                                <div>
                                    <span class="font-semibold">${userName}</span> · <span class="text-gray-500 text-sm">${commentDate}</span>
                                </div>
                            </div>
                            <p class="mt-2">${commentText}</p>
                            <div class="flex items-center text-gray-500 text-sm mt-1">
                                <button class="like-button flex items-center mr-4 transition-colors duration-300" data-liked="false">
                                    <i class="fas fa-thumbs-up mr-1"></i> <span class="like-count">0</span>
                                </button>
                                <button class="dislike-button flex items-center transition-colors duration-300" data-disliked="false">
                                    <i class="fas fa-thumbs-down mr-1"></i> <span class="dislike-count">0</span>
                                </button>
                                <span class="ml-4">Balasan</span>
                            </div>
                        </div>
                    `;

                    const commentsContainer = document.getElementById('commentsContainer');
                    commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
                    this.value = '';
                    updateCommentCount();
                }
            }
        });

        document.getElementById('commentsContainer').addEventListener('click', function (e) {
            const likeButton = e.target.closest('.like-button');
            const dislikeButton = e.target.closest('.dislike-button');

            if (likeButton) {
                const likeCount = likeButton.querySelector('.like-count');
                const dislikeButton = likeButton.nextElementSibling;
                const disliked = dislikeButton.dataset.disliked === 'true';

                if (!disliked) {
                    const liked = likeButton.dataset.liked === 'true';
                    likeButton.dataset.liked = !liked;
                    likeCount.textContent = parseInt(likeCount.textContent) + (liked ? -1 : 1);
                    likeButton.classList.toggle('text-blue-500', !liked);
                    likeButton.classList.toggle('text-gray-500', liked);
                }
            }

            if (dislikeButton) {
                const dislikeCount = dislikeButton.querySelector('.dislike-count');
                const likeButton = dislikeButton.previousElementSibling;
                const liked = likeButton.dataset.liked === 'true';

                if (liked) {
                    const likeCount = likeButton.querySelector('.like-count');
                    likeButton.dataset.liked = false;
                    likeCount.textContent = parseInt(likeCount.textContent) - 1;
                    likeButton.classList.remove('text-blue-500');
                    likeButton.classList.add('text-gray-500');
                }

                const disliked = dislikeButton.dataset.disliked === 'true';
                dislikeButton.dataset.disliked = !disliked;
                dislikeCount.textContent = parseInt(dislikeCount.textContent) + (disliked ? -1 : 1);
                dislikeButton.classList.toggle('text-blue-500', !disliked);
                dislikeButton.classList.toggle('text-gray-500', disliked);
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
    </script>
</div>

<?php include 'header & footer/footer.php'; ?>