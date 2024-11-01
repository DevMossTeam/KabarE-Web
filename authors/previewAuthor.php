<?php
include '../header & footer/header_AuthRev.php';

// Pastikan direktori uploads/ ada
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Ambil data dari POST
$title = $_POST['title'] ?? 'Judul tidak tersedia';
$content = $_POST['content'] ?? 'Konten tidak tersedia';

// Proses file cover
$coverPath = '';
if (isset($_FILES['cover']) && $_FILES['cover']['error'] == UPLOAD_ERR_OK) {
    $coverPath = $uploadDir . basename($_FILES['cover']['name']);
    if (move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath)) {
        $coverPath = htmlspecialchars($coverPath);
    } else {
        $coverPath = '';
    }
}

// Dapatkan waktu saat ini
$currentDate = date('l, d F Y');

// Penulis
$author = "Anonym";
?>

<style>
    .content img {
        max-width: 600px;
        max-height: 400px;
        height: auto;
        width: auto;
    }
</style>

<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <span class="bg-red-500 text-white px-2 py-1 rounded">Kategori</span>
            <button class="text-blue-500 hover:underline">Edit</button>
        </div>
        <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($title); ?></h1>
        <p class="text-gray-600 mb-4"><?php echo $currentDate; ?> | Penulis: <?php echo $author; ?></p>
        <?php if ($coverPath): ?>
            <img src="<?php echo $coverPath; ?>" alt="Cover Image" class="w-full h-auto mb-4">
        <?php endif; ?>
        <div class="text-gray-800 mb-4 content">
            <?php echo $content; ?>
        </div>
    </div>
</div>
