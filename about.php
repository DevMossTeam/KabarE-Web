<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-white text-2xl font-bold">BeritaKu</a>
            <div>
                <a href="index.php" class="text-white px-4">Home</a>
                <a href="news.php" class="text-white px-4">News</a>
                <a href="about.php" class="text-white px-4">About</a>
                <a href="contact.php" class="text-white px-4">Contact</a>
                <a href="logout.php" class="text-white px-4">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6 text-center">About Us</h1>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <p class="text-gray-700 mb-4">BeritaKu adalah platform berita terkemuka yang menyediakan berita terbaru dan terpercaya dari seluruh dunia. Kami berkomitmen untuk memberikan informasi yang akurat dan mendalam kepada pembaca kami.</p>
            <p class="text-gray-700 mb-4">Tim kami terdiri dari jurnalis berpengalaman yang bekerja tanpa lelah untuk menyajikan berita yang relevan dan menarik. Kami percaya bahwa informasi yang baik adalah kunci untuk masyarakat yang lebih baik.</p>
            <p class="text-gray-700 mb-4">Visi kami adalah menjadi sumber berita utama yang dapat diandalkan oleh masyarakat global. Kami berusaha untuk mencapai ini dengan menjaga standar jurnalistik yang tinggi dan berkomitmen pada integritas dan kejujuran.</p>
        </div>
    </div>
</body>
</html>