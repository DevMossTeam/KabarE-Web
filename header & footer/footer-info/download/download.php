<?php include '../../../header & footer/header.php'; ?>
<?php include '../../../header & footer/category_header.php'; ?>
<?php renderCategoryHeader(categoryName: 'Download'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KabarE Download</title>
    <link rel="stylesheet" href="style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="slider.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    <div class="slider">
        <div class="slides">
            <div class="slide active">
                <img src="img/Intro 1.png" alt="Intro 1">
            </div>
            <div class="slide">
                <img src="img/Intro 2.png" alt="Intro 2">
            </div>
            <div class="slide">
                <img src="img/Intro 3.png" alt="Intro 3">
            </div>
        </div>
        <div class="navigation">
            <span class="dot" onclick="selectSlide(0)"></span>
            <span class="dot" onclick="selectSlide(1)"></span>
            <span class="dot" onclick="selectSlide(2)"></span>
        </div>
    </div>

    <div class="rectangle-container">
        <div class="rectangle-top">
            <div class="text-box1">
                <div class="text-content">
                    <h2>Piye KabarE rek !!!</h2>
                    <p>Ada Kabar apa nih hari ini?</p>
                </div>
                <img src="img/foot1.svg" alt="">
            </div>
        </div>
        <div class="rectangle-bottom">
            <div class="text-box2">
                <img src="img/foot2.svg" alt="">
                <div class="text-content">
                    <h2>Klik KabarE Sekarang</h2>
                    <p>Senyumin Harimu!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="header-content">
        <div class="image-container">
            <img src="img/Wellcome.png" alt="Wellcome" class="wellcome-image">
            <img src="img/Home.png" alt="Home" class="home-image">
        </div>
        <div class="text-container">
            <h2>Mengapa Menggunakan KabarE?</h2>
            <p>Ayo, jangan sampai ketinggalan berita kampus terbaru! Unduh aplikasi KabarE sekarang dan nikmati pengalaman membaca berita yang lebih praktis dan cepat, langsung dari ponsel Anda.</p>
            <p>Tautan Download untuk Android: <a href="#">Unduh KabarE Sekarang</a></p>
            <p>Aplikasi ini tersedia secara gratis dan kompatibel dengan berbagai versi Android. Kami secara berkala melakukan pembaruan untuk meningkatkan performa dan menambahkan fitur-fitur baru sesuai kebutuhan pengguna.</p>
            <h2>Nikmati Akses Mudah ke Berita Kampus di KabarE!</h2>
            <p>Jadilah bagian dari komunitas kampus yang selalu terinformasi. Unduh KabarE dan temukan semua informasi yang Anda butuhkan langsung di genggaman Anda!</p>
        </div>
    </div>
</body>

</html>

<?php include '../../../header & footer/footer.php'; ?>