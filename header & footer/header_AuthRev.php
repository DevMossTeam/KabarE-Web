<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header AuthRev</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen overflow-x-hidden">
    <!-- Navbar -->
    <nav id="authRevNavbar" class="z-10 relative">
        <!-- Bagian Atas Navbar -->
        <div class="bg-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/index.php" class="text-black text-2xl font-bold">
                    <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="w-10 h-10">
                </a>
            </div>
        </div>

        <!-- Bagian Bawah Navbar -->
        <div class="bg-blue-500 p-2">
            <div class="container mx-auto flex justify-start items-center">
                <button onclick="location.href='/index.php'" class="text-white text-lg flex items-center">
                    <span class="mr-2">&lt;&lt;</span> Kembali
                </button>
            </div>
        </div>
    </nav>
</body>

</html>

