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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reaction_id'])) {
    $reactionId = $_POST['reaction_id'];
    $beritaId = $_POST['berita_id'];

    // Pastikan reaction_id adalah string yang valid (misalnya, tidak kosong)
    if (!empty($reactionId)) {
        // Hapus data reaksi dari tabel reaksi hanya untuk berita_id yang sesuai
        $deleteQuery = "DELETE FROM reaksi WHERE id = ? AND berita_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if ($deleteStmt) {
            // Gunakan 's' untuk string karena reaction_id dan berita_id adalah string
            $deleteStmt->bind_param("ss", $reactionId, $beritaId); // Pastikan 's' untuk string
            $deleteStmt->execute();
            $deleteStmt->close();
        }
    }

    // Redirect setelah penghapusan untuk merefresh halaman liked.php
    header('Location: liked.php');
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
function get_first_image($content) {
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

<!-- JavaScript untuk Menangani Dropdown -->
<script>
    document.querySelectorAll('.open-dropdown-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Mencegah klik di tombol untuk menyebar ke window
            
            const reactionId = this.getAttribute('data-id');
            const beritaId = this.getAttribute('data-berita-id'); // Ambil berita_id
            console.log("reaction_id:", reactionId, "berita_id:", beritaId); // Menampilkan reaction_id dan berita_id di console

            const dropdownMenu = this.nextElementSibling; // Dropdown menu adalah elemen setelah tombol
            const isOpen = !dropdownMenu.classList.contains('hidden'); // Cek apakah dropdown sedang terbuka
            
            // Menutup semua dropdown terlebih dahulu
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });

            // Jika dropdown belum terbuka, tampilkan dropdown yang diklik
            if (!isOpen) {
                dropdownMenu.classList.remove('hidden');
            }
        });
    });

    // Menutup dropdown jika klik di luar dropdown atau tombol titik tiga
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.open-dropdown-btn')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });
</script>
