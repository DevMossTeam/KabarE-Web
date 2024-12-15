<?php
session_start();
include '../connection/config.php'; // Pastikan jalur relatif ini benar
include '../header & footer/header.php'; // Sertakan header

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$userData = [];

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT profile_pic, nama_lengkap, role, nama_pengguna, kredensial FROM user WHERE uid = ?");
    if ($stmt) {
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

// Fetch the selected sorting option (default is 'terbaru' - newest)
$sortOption = $_GET['sort'] ?? 'terbaru';

// Get search query from URL if exists
$searchQuery = $_GET['search'] ?? '';

$page = $_GET['page'] ?? 'bacaNanti';
$page = $_GET['page'] ?? 'liked';
?>

<div class="flex ml-12 mt-4">
    <!-- Sidebar -->
    <div class="w-1/6 p-4 mt-4">
        <div class="flex justify-center">
            <?php if (!empty($userData['profile_pic'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($userData['profile_pic']) ?>" alt="Profile Picture" class="w-24 h-24 rounded-full">
            <?php else: ?>
                <i class="fas fa-user-circle text-9xl text-gray-500"></i>
            <?php endif; ?>
        </div>
        <h2 class="text-center text-2xl font-semibold mt-4"><?= htmlspecialchars($userData['nama_lengkap'] ?? 'Nama Pengguna') ?></h2>
        <p class="text-center text-sm text-[#9F9F9F]">@<?= htmlspecialchars($userData['nama_pengguna'] ?? 'username') ?></p>
        <p class="text-center text-md text-gray-400 mt-0"><?= htmlspecialchars($userData['role'] ?? 'Role belum diatur') ?></p>
        <?php if (!empty($userData['kredensial'])): ?>
            <p class="text-center text-gray-600 text-lg"><?= htmlspecialchars($userData['kredensial']) ?></p>
        <?php endif; ?>
        <a href="../settings/umum.php" class="mt-4 w-full bg-blue-500 text-white py-2 rounded block text-center">Edit Profile</a>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 ml-12 mt-4">
        <h2 class="text-4xl font-semibold mt-4 mb-2 ml-4">
            <?= htmlspecialchars($userData['nama_lengkap'] ?? 'Nama Pengguna') ?> <span class="text-4xl text-gray-600">(<?= htmlspecialchars($userData['role'] ?? 'Pembaca') ?>)</span>
        </h2>

        <div class="relative flex items-center mt-4 border-b ml-4">
            <!-- Menu Links -->
            <a href="/profile/mainEditor.php?page=bacaNanti" class="menu-item mr-6 pb-2 text-sm">
                Disimpan
            </a>
            <a href="/profile/mainEditor.php?page=liked" class="menu-item mr-6 pb-2 text-sm">
                Disukai
            </a>

            <!-- Search and Filter Container -->
            <div class="flex items-center space-x-2 ml-4">
                <!-- Filter Button -->
                <div class="flex items-center bg-gray-100 p-2 rounded-full">
                    <button id="filter-button" class="p-2 text-sm bg-gray-100 rounded-full flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Urutkan</span> <!-- Text for the filter button -->
                        <img src="https://img.icons8.com/ios-filled/50/808080/filter.png" alt="Filter Icon" class="w-5 h-5" />
                    </button>
                </div>

                <!-- Search Input with Icon -->
                <div class="relative">
                    <input type="text" id="search-input" class="p-2 pl-10 text-sm rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari artikel..." value="<?= htmlspecialchars($searchQuery) ?>" />
                    <img src="https://img.icons8.com/ios-filled/50/808080/search.png" alt="Search Icon" class="absolute left-3 top-2 w-5 h-5 cursor-pointer" id="search-icon" />
                </div>
            </div>

            <!-- Filter Dropdown -->
            <div id="filter-dropdown" class="hidden absolute bg-white shadow-lg rounded ml-44 mt-24 w-48 p-2 z-10">
                <a href="?page=<?= htmlspecialchars($page) ?>&sort=terbaru" class="block text-sm py-1 hover:bg-gray-200 active:bg-gray-300">Terbaru</a>
                <a href="?page=<?= htmlspecialchars($page) ?>&sort=terlama" class="block text-sm py-1 hover:bg-gray-200 active:bg-gray-300">Terlama</a>
            </div>
            <div id="active-indicator" class="absolute bottom-0 h-0.5 bg-blue-500 transition-all duration-300"></div>
        </div>

        <div class="mt-4">
            <?php
            // Tentukan halaman yang akan dimuat berdasarkan parameter GET
            $page = $_GET['page'] ?? 'bacaNanti'; // Default ke 'bacaNanti' jika tidak ada parameter

            // Sertakan file yang sesuai berdasarkan parameter
            switch ($page) {
                case 'bacaNanti':
                    // Tentukan urutan berdasarkan pilihan pengguna
                    $sortOrder = ($sortOption == 'terbaru') ? 'DESC' : 'ASC';
                    include 'bacaNanti.php'; // Tampilkan artikel dengan urutan
                    break;
                case 'liked':
                    // Tentukan urutan berdasarkan pilihan pengguna
                    $sortOrder = ($sortOption == 'terbaru') ? 'DESC' : 'ASC';
                    include 'liked.php'; // Tampilkan artikel dengan urutan
                    break;
            }
            ?>
        </div>
    </div>
</div>

<?php include '../header & footer/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterButton = document.getElementById('filter-button');
        const filterDropdown = document.getElementById('filter-dropdown');
        const searchInput = document.getElementById('search-input');
        const searchIcon = document.getElementById('search-icon');

        // Toggle the dropdown visibility when the filter button is clicked
        filterButton.addEventListener('click', () => {
            filterDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicked outside
        document.addEventListener('click', (e) => {
            if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });

        // Trigger search when icon clicked or Enter is pressed
        searchIcon.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            window.location.href = `?page=<?php echo $page; ?>&search=${encodeURIComponent(searchQuery)}&sort=<?= $sortOption ?>`;
        });

        // Trigger search on Enter key press
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const searchQuery = searchInput.value.trim();
                window.location.href = `?page=<?php echo $page; ?>&search=${encodeURIComponent(searchQuery)}&sort=<?= $sortOption ?>`;
            }
        });

        const menuItems = document.querySelectorAll('.menu-item');
        const activeIndicator = document.getElementById('active-indicator');

        function updateIndicator(element) {
            const {
                offsetLeft,
                offsetWidth
            } = element;
            activeIndicator.style.width = `${offsetWidth}px`;
            activeIndicator.style.left = `${offsetLeft}px`;
        }

        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                updateIndicator(item);
                window.location.href = item.href;
            });
        });

        // Set initial position
        const currentPage = '<?php echo $page; ?>';
        const initialItem = Array.from(menuItems).find(item => item.href.includes(currentPage));
        if (initialItem) {
            updateIndicator(initialItem);
        }
    });
</script>