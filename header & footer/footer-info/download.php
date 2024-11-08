<?php include '../../header & footer/header.php'; ?>
<?php include '../../header & footer/category_header.php'; ?>
<?php renderCategoryHeader(categoryName: 'Download'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KabarE Download</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .slide {
            transition: transform 0.5s ease-in-out;
        }
    </style>
</head>
<body class="font-sans flex flex-col min-h-screen">
    <div class="flex-grow">
        <!-- Logo Website -->
        <div class="flex justify-center my-4">
            <img src="../../assets/web-icon/KabarE-UTDK.png" alt="KabarE Logo" class="w-36">
        </div>

        <!-- Slider -->
        <div class="relative max-w-xl mx-auto overflow-hidden">
            <div class="slides flex">
                <img src="../../assets/PLP-icon/Intro 2.png" alt="Intro 2" class="w-full slide">
                <img src="../../assets/PLP-icon/Intro 1.png" alt="Intro 1" class="w-full slide hidden">
                <img src="../../assets/PLP-icon/Intro 3.png" alt="Intro 3" class="w-full slide hidden">
            </div>
            <div class="flex justify-center mt-2">
                <span class="indicator w-2.5 h-2.5 bg-gray-300 rounded-full mx-1 cursor-pointer" onclick="showSlide(0)"></span>
                <span class="indicator w-2.5 h-2.5 bg-gray-300 rounded-full mx-1 cursor-pointer" onclick="showSlide(1)"></span>
                <span class="indicator w-2.5 h-2.5 bg-gray-300 rounded-full mx-1 cursor-pointer" onclick="showSlide(2)"></span>
            </div>
        </div>

        <!-- Logos -->
        <div class="flex justify-center my-4">
            <img src="../../assets/PLP-icon/PKR-ic.png" alt="PKR Logo" class="w-24 mx-4">
            <img src="../../assets/PLP-icon/KKS-ic.png" alt="KKS Logo" class="w-24 mx-4">
        </div>

        <!-- Animasi Gambar -->
        <div class="relative text-center mt-12">
            <img class="welcome absolute opacity-0 transition-opacity duration-1000 transform -translate-x-full" src="../../assets/PLP-icon/Wellcome 1.png" alt="Welcome">
            <img class="home absolute opacity-0 transition-opacity duration-1000 transform -translate-x-1/2" src="../../assets/PLP-icon/Home 1.png" alt="Home">
            <div class="text relative opacity-0 transition-opacity duration-1000 transform translate-y-full">
                <p>Selamat Datang di KabarE!</p>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slides img');
        const indicators = document.querySelectorAll('.indicator');

        function showSlide(index) {
            slides[currentSlide].classList.add('hidden');
            indicators[currentSlide].classList.remove('bg-gray-800');
            currentSlide = index;
            slides[currentSlide].classList.remove('hidden');
            indicators[currentSlide].classList.add('bg-gray-800');
        }

        function initSlider() {
            slides[currentSlide].classList.remove('hidden');
            indicators[currentSlide].classList.add('bg-gray-800');
        }

        function animateImages() {
            const welcome = document.querySelector('.welcome');
            const home = document.querySelector('.home');
            const text = document.querySelector('.text');

            setTimeout(() => {
                welcome.classList.remove('opacity-0');
                welcome.classList.remove('-translate-x-full');
            }, 500);

            setTimeout(() => {
                home.classList.remove('opacity-0');
                home.classList.remove('-translate-x-1/2');
            }, 1500);

            setTimeout(() => {
                text.classList.remove('opacity-0');
                text.classList.remove('translate-y-full');
            }, 2500);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initSlider();
            animateImages();
        });
    </script>
</body>
<?php include '../../header & footer/footer.php'; ?>
</html>