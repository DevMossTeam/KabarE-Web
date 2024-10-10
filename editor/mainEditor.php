<?php
session_start();
include '../config.php'; // Pastikan jalur relatif ini benar
include '../header.php'; // Sertakan header

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Utama</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white p-4 border-r border-gray-300">
                <img src="path/to/profile.jpg" alt="Profile Picture" class="rounded-full w-32 mx-auto">
                <h2 class="text-center text-xl font-bold mt-4">Chiquita Clairina K</h2>
                <p class="text-center text-gray-600">Mahasiswa Jurusan Teknologi Informasi</p>
                <p class="text-center mt-2">Bio: Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa.</p>
                <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded">Edit Profile</button>
                <div class="mt-4">
                    <h3 class="text-gray-700 font-bold">Social Media:</h3>
                    <p class="text-gray-600"><i class="fab fa-instagram"></i> ndikka005</p>
                    <p class="text-gray-600"><i class="fab fa-tiktok"></i> asyourArt01</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-3/4 ml-4">
                <div class="relative flex mt-4 border-b">
                    <a href="/editor/mainEditor.php?page=artikel" class="menu-item mr-4 pb-2">ARTIKEL</a>
                    <a href="/editor/mainEditor.php?page=bacaNanti" class="menu-item mr-4 pb-2">BACA NANTI</a>
                    <a href="/editor/mainEditor.php?page=liked" class="menu-item pb-2">DISUKAI</a>
                    <div id="active-indicator" class="absolute bottom-0 h-0.5 bg-blue-500 transition-all duration-300"></div>
                </div>
                <div class="mt-4">
                    <?php
                    // Tentukan halaman yang akan dimuat berdasarkan parameter GET
                    $page = $_GET['page'] ?? 'artikel'; // Default ke 'artikel' jika tidak ada parameter

                    // Sertakan file yang sesuai berdasarkan parameter
                    switch ($page) {
                        case 'bacaNanti':
                            include 'bacaNanti.php';
                            break;
                        case 'liked':
                            include 'liked.php';
                            break;
                        case 'artikel':
                        default:
                            include 'artikel.php';
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php include '../footer.php'; // Sertakan footer ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuItems = document.querySelectorAll('.menu-item');
        const activeIndicator = document.getElementById('active-indicator');

        function updateIndicator(element) {
            const { offsetLeft, offsetWidth } = element;
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
</body>
</html>