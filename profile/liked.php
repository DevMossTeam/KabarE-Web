<?php
// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection/config.php'; // Pastikan jalur relatif ini benar

$user_id = $_SESSION['user_id'] ?? null;
$articles = [];
$sortOrder = ($_GET['sort'] ?? 'terbaru') === 'terlama' ? 'ASC' : 'DESC'; // Tentukan urutan
$searchQuery = $_GET['search'] ?? ''; // Ambil query pencarian dari URL

// Proses hapus reaksi (bukan artikel) jika ada
// Proses hapus reaksi jika ada request melalui AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reaction_id'], $_POST['berita_id'])) {
    $reactionId = $_POST['reaction_id'];
    $beritaId = $_POST['berita_id'];

    $response = ['success' => false, 'message' => 'Gagal menghapus artikel.'];

    // Pastikan reaction_id dan berita_id tidak kosong
    if (!empty($reactionId) && !empty($beritaId)) {
        $deleteQuery = "DELETE FROM reaksi WHERE id = ? AND berita_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if ($deleteStmt) {
            $deleteStmt->bind_param("ss", $reactionId, $beritaId);
            if ($deleteStmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Artikel yang disukai berhasil dihapus.';
            }
            $deleteStmt->close();
        }
    }

    // Kembalikan response JSON untuk AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// SQL Query untuk mencari artikel berdasarkan judul dan urutan
$query = "
    SELECT r.id as reaction_id, r.berita_id, b.judul, b.konten_artikel, r.tanggal_reaksi
    FROM reaksi r
    JOIN berita b ON r.berita_id = b.id
    WHERE r.user_id = ? AND r.jenis_reaksi = 'Suka'
";

// Tambahkan kondisi pencarian jika ada query pencarian
if (!empty($searchQuery)) {
    $query .= " AND b.judul LIKE ?";
}

$query .= " ORDER BY r.tanggal_reaksi $sortOrder LIMIT 20";

// Persiapkan statement SQL
$stmt = $conn->prepare($query);

if ($stmt) {
    if (!empty($searchQuery)) {
        // Bind parameter pencarian
        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $user_id, $searchParam);
    } else {
        $stmt->bind_param("s", $user_id); // Menggunakan 's' karena user_id adalah string
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    $stmt->close();
}

// Fungsi untuk mengambil gambar pertama dari artikel
function get_first_image($content)
{
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
    return $image['src'] ?? 'https://via.placeholder.com/400x200';
}
?>

<!-- HTML untuk menampilkan artikel dan dropdown hapus -->
<div class="container mx-auto px-4 lg:px-8 mb-12" style="margin-left: 0;">
    <?php if (empty($articles) && empty($searchQuery)): ?>
        <!-- Menampilkan jika tidak ada artikel yang disukai -->
        <div class="text-center mt-12">
            <i class="fas fa-folder text-6xl text-gray-400"></i>
            <p class="font-bold text-xl mt-4">Kamu belum memiliki artikel yang disukai</p>
            <p class="text-sm text-gray-500 mt-2">Cari artikelnya lalu klik icon <i class="fas fa-thumbs-up text-gray-400"></i></p>
            <a href="/index.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded">Lihat Artikel Hari Ini</a>
        </div>
    <?php elseif (empty($articles)): ?>
        <!-- Menampilkan jika pencarian tidak ditemukan -->
        <div class="text-center mt-12">
            <img src="https://img.icons8.com/ios-filled/50/808080/search.png" alt="Artikel Tidak Ditemukan" class="mx-auto w-16 h-16">
            <p class="font-bold text-xl mt-4">Artikel tidak ditemukan</p>
            <p class="text-gray-500 text-sm mt-2">Coba cari menggunakan kata kunci lain</p>
        </div>
    <?php else: ?>
        <!-- Menampilkan daftar artikel yang disukai -->
        <?php foreach ($articles as $article): ?>
            <div class="flex items-start mb-6 relative">
                <div class="flex-grow max-w-3xl pr-4">
                    <h3 class="text-2xl font-medium mt-2 mb-2">
                        <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['judul']) ?>
                        </a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-2">
                        | Disukai <?= date('d F Y', strtotime($article['tanggal_reaksi'])) ?>
                    </p>
                </div>
                <div class="relative">
                    <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>">
                        <img src="<?= get_first_image($article['konten_artikel']) ?>" alt="Gambar Disukai" class="w-56 h-28 object-cover rounded ml-2">
                    </a>
                    <div class="absolute top-0 right-[-15px] transform translate-x-1/2 -translate-y-1/2">
                        <button class="text-gray-500 open-dropdown-btn" data-id="<?= $article['reaction_id'] ?>" data-berita-id="<?= $article['berita_id'] ?>" data-title="<?= htmlspecialchars($article['judul']) ?>">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu hidden absolute right-0 mt-2 bg-white border border-gray-300 shadow-lg rounded w-48">
                            <form method="POST" action="liked.php">
                                <!-- Input hidden untuk reaction_id dan berita_id -->
                                <input type="hidden" name="reaction_id" id="dropdownReactionId_<?= $article['reaction_id'] ?>" value="<?= $article['reaction_id'] ?>">
                                <input type="hidden" name="berita_id" value="<?= $article['berita_id'] ?>"> <!-- Tambahkan berita_id -->
                                <button type="submit" class="block px-4 py-2 text-black hover:bg-gray-100 w-full text-left flex items-center">
                                    <img src="https://img.icons8.com/ios-filled/20/000000/trash.png" alt="Hapus" class="mr-2"> Hapus Artikel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-300 mb-4"></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal HTML untuk Pesan Penghapusan Artikel -->
<div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
        <div class="flex items-center">
            <img src="https://img.icons8.com/ios-filled/50/4CAF50/ok.png" alt="Success Icon" class="mr-4 w-8 h-8">
            <p id="modalMessage" class="text-lg font-semibold">Artikel berhasil dihapus!</p>
        </div>
        <div class="mt-4 flex justify-end">
            <button id="closeModal" class="text-blue-500 hover:text-blue-700">Tutup</button>
        </div>
    </div>
</div>

<!-- JavaScript untuk Menangani Dropdown -->
<script>
    document.querySelectorAll('.open-dropdown-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();

            const reactionId = this.getAttribute('data-id');
            const beritaId = this.getAttribute('data-berita-id');
            const dropdownMenu = this.nextElementSibling;

            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
            dropdownMenu.classList.toggle('hidden');

            // Tambahkan event listener untuk tombol hapus
            dropdownMenu.querySelector('button[type="submit"]').addEventListener('click', function (e) {
                e.preventDefault();

                // Kirim AJAX request untuk menghapus artikel
                fetch('liked.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        reaction_id: reactionId,
                        berita_id: beritaId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tampilkan modal dengan pesan
                            showModal(data.message);

                            // Hapus elemen artikel dari DOM
                            const articleElement = button.closest('.flex.items-start');
                            const nextSibling = articleElement?.nextElementSibling;

                            // Hapus artikel
                            articleElement.remove();

                            // Hapus garis bawah jika ada
                            if (nextSibling && nextSibling.classList.contains('border-b')) {
                                nextSibling.remove();
                            }

                            // Tampilkan pesan jika semua artikel terhapus
                            if (!document.querySelector('.flex.items-start')) {
                                document.querySelector('.container').innerHTML = `
                                    <div class="text-center mt-12">
                                        <i class="fas fa-folder text-6xl text-gray-400"></i>
                                        <p class="font-bold text-xl mt-4">Kamu belum memiliki artikel yang disukai</p>
                                        <p class="text-sm text-gray-500 mt-2">Cari artikelnya lalu klik icon <i class="fas fa-thumbs-up text-gray-400"></i></p>
                                        <a href="/index.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded">Lihat Artikel Hari Ini</a>
                                    </div>`;
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });

    // Fungsi untuk menampilkan modal
    function showModal(message) {
        const modal = document.getElementById('successModal');
        const modalMessage = document.getElementById('modalMessage');
        modalMessage.textContent = message;
        modal.classList.remove('hidden');

        // Menutup modal otomatis setelah 3 detik
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 3000);
    }

    // Menutup modal ketika tombol tutup diklik
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('successModal').classList.add('hidden');
    });

    // Menutup modal ketika klik di luar modal
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('successModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Tutup dropdown saat klik di luar
    window.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
    });
</script>