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
    <title>Contact</title>
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
        <h1 class="text-3xl font-bold mb-6 text-center">Contact Us</h1>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <p class="text-gray-700 mb-4">Jika Anda memiliki pertanyaan atau ingin menghubungi kami, silakan gunakan informasi kontak di bawah ini:</p>
            <p class="text-gray-700 mb-4"><strong>Email:</strong> contact@beritaku.com</p>
            <p class="text-gray-700 mb-4"><strong>Telepon:</strong> +62 123 456 789</p>
            <p class="text-gray-700 mb-4"><strong>Alamat:</strong> Jl. Contoh No. 123, Jakarta, Indonesia</p>
            <form action="contact.php" method="POST" class="mt-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-gray-700">Message</label>
                    <textarea id="message" name="message" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>