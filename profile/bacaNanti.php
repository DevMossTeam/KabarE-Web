<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection/config.php'; // Pastikan jalur relatif ini benar

$user_id = $_SESSION['user_id'] ?? null;
$articles = [];
$sortOrder = ($_GET['sort'] ?? 'terbaru') === 'terlama' ? 'ASC' : 'DESC'; // Tentukan urutan
$searchQuery = $_GET['search'] ?? ''; // Ambil query pencarian dari URL

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'hapus') {
    $id = $_POST['id'] ?? null;  // ID untuk mengidentifikasi entri bookmark
    $berita_id = $_POST['berita_id'] ?? null;  // ID artikel yang ingin dihapus

    if ($id && $berita_id) {
        // Menghapus artikel dari daftar bookmark berdasarkan ID dan berita_id
        $deleteQuery = "DELETE FROM bookmark WHERE id = ? AND berita_id = ?";
        $stmt = $conn->prepare($deleteQuery);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Gagal menyiapkan query: ' . $conn->error]);
            exit;
        }

        // Gunakan 'ss' karena id dan berita_id adalah tipe VARCHAR
        $stmt->bind_param('ss', $id, $berita_id);

        // Cek apakah query berhasil dijalankan
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Artikel yang disimpan berhasil dihapus!']);
        } else {
            // Menampilkan error jika query gagal
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus artikel. Error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID atau berita_id tidak ditemukan.']);
    }
    exit;
}

// SQL Query untuk mencari artikel yang dibookmark berdasarkan judul dan urutan
$query = "
    SELECT b.id AS berita_id, b.judul, b.konten_artikel, bm.tanggal_bookmark, bm.id
    FROM bookmark bm
    JOIN berita b ON bm.berita_id = b.id
    WHERE bm.user_id = ?
";

// Tambahkan kondisi pencarian jika ada query pencarian
if (!empty($searchQuery)) {
    $query .= " AND b.judul LIKE ?";
}

$query .= " ORDER BY bm.tanggal_bookmark $sortOrder LIMIT 20";

// Persiapkan statement SQL
$stmt = $conn->prepare($query);

if ($stmt) {
    if (!empty($searchQuery)) {
        // Bind parameter pencarian
        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $user_id, $searchParam);
    } else {
        $stmt->bind_param("s", $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    $stmt->close();
}
?>

<!-- HTML untuk menampilkan artikel dan dropdown hapus -->
<div class="container mx-auto px-4 lg:px-8 mb-12" style="margin-left: 0;">
    <?php if (empty($articles) && empty($searchQuery)): ?>
        <!-- Menampilkan jika tidak ada artikel yang dibookmark -->
        <div class="text-center mt-12">
            <i class="fas fa-folder text-6xl text-gray-400"></i>
            <p class="font-bold text-xl mt-4">Kamu belum memiliki artikel yang dibaca nanti</p>
            <p class="text-sm text-gray-500 mt-2">Cari artikelnya lalu klik icon <i class="fas fa-bookmark text-gray-400"></i></p>
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
        <!-- Menampilkan daftar artikel yang dibookmark -->
        <?php foreach ($articles as $article): ?>
            <div class="flex items-start mb-6 relative">
                <div class="flex-grow max-w-3xl pr-4">
                    <h3 class="text-2xl font-medium mt-2 mb-2">
                        <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['judul']) ?>
                        </a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-2">
                        | Disimpan <?= date('d F Y', strtotime($article['tanggal_bookmark'])) ?>
                    </p>
                </div>
                <div class="relative">
                    <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>">
                        <img src="<?= get_first_image($article['konten_artikel']) ?>" alt="Gambar Ditandai" class="w-56 h-28 object-cover rounded ml-2">
                    </a>
                    <div class="absolute top-0 right-[-15px] transform translate-x-1/2 -translate-y-1/2">
                        <button class="text-gray-500 open-dropdown-btn" data-id="<?= $article['id'] ?>" data-berita-id="<?= $article['berita_id'] ?>" data-title="<?= htmlspecialchars($article['judul']) ?>">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu hidden absolute right-0 mt-2 bg-white border border-gray-300 shadow-lg rounded w-48">
                            <form method="POST" action="bacaNanti.php">
                                <!-- Input hidden untuk berita_id -->
                                <input type="hidden" name="id" value="<?= $article['id'] ?>"> <!-- ID Bookmark -->
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

<?php
function get_first_image($content)
{
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
    return $image['src'] ?? 'https://via.placeholder.com/400x200';
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const dropdownBtns = document.querySelectorAll('.open-dropdown-btn');
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    const successModal = document.getElementById('successModal');

    // Tampilkan atau sembunyikan dropdown saat tombol di-klik
    dropdownBtns.forEach(button => {
        button.addEventListener('click', function (event) {
            // Menyembunyikan semua dropdown terlebih dahulu
            allDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));

            // Menampilkan atau menyembunyikan dropdown terkait dengan artikel yang dipilih
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');

            // Mencegah klik tombol dropdown untuk menutup menu
            event.stopPropagation();
        });
    });

    // Menangani klik di luar dropdown untuk menutupnya
    document.addEventListener('click', function (event) {
        allDropdowns.forEach(dropdown => {
            // Jika klik terjadi di luar dropdown dan tombol dropdown, maka tutup dropdown
            if (!dropdown.contains(event.target) && !event.target.classList.contains('open-dropdown-btn')) {
                dropdown.classList.add('hidden');
            }
        });
    });

    const deleteForms = document.querySelectorAll('.dropdown-menu form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const beritaId = form.querySelector('input[name="berita_id"]').value;
            const id = form.querySelector('input[name="id"]').value; // Bookmark ID

            console.log('Mengirim data untuk hapus:', {
                beritaId,
                id
            }); // Log data yang dikirim

            fetch('bacaNanti.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'hapus',
                    berita_id: beritaId,
                    id: id
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Response dari server:', data); // Log respons dari server
                    if (data.success) {
                        showSuccessModal(data.message);
                        const articleRow = form.closest('.flex');
                        const separator = articleRow.nextElementSibling; // Elemen garis abu-abu di bawah

                        // Hapus artikel dan garis abu-abu di bawahnya
                        articleRow.remove();
                        if (separator && separator.classList.contains('border-b')) {
                            separator.remove();
                        }
                    } else {
                        alert('Gagal menghapus artikel! Pesan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus artikel!');
                });
        });
    });

    // Tambahkan event listener pada klik tombol tutup modal
    document.getElementById('closeModal').addEventListener('click', function () {
        closeModal();
    });

    // Tambahkan event listener untuk klik di luar modal
    successModal.addEventListener('click', function (event) {
        // Tutup modal jika pengguna mengklik di luar kotak modal
        if (event.target === successModal) {
            closeModal();
        }
    });

    function showSuccessModal(message) {
        const modalMessage = document.getElementById('modalMessage');
        modalMessage.textContent = message;
        successModal.classList.remove('hidden');

        // Tutup modal secara otomatis setelah 3 detik
        setTimeout(() => {
            closeModal();
        }, 3000);
    }

    function closeModal() {
        successModal.classList.add('hidden');
    }
});
</script>