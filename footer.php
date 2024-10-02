    <!-- Footer -->
    <footer class="bg-black text-white py-4 mt-auto">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <img src="assets/web-icon/KabarU-logo.png" alt="KabarU Logo" class="h-8 mb-2">
                    <p class="text-lg mb-0">Politeknik Negeri Jember</p>
                    <p class="text-lg mt-0 mb-2">UKM Explant</p>
                    <p class="text-xs mb-2">Media Berita Mahasiswa Terpercaya</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <p class="text-xs">Jl. Medan Merdeka Barat No.4-5, Jakarta Pusat.</p>
                    </div>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-phone mr-2"></i>
                        <p class="text-xs">+6221 350 0584, +6221 351 1086</p>
                    </div>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-envelope mr-2"></i>
                        <p class="text-xs">Pemberitaan: beritapro3@rri.go.id</p>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-gray-300 border-2">
            <div class="text-center space-x-4">
                <span class="text-sm">Ketentuan</span>
                <span class="text-sm">Kebijakan Privasi</span>
                <span class="text-sm">Pedoman Media Siber</span>
                <span class="text-sm">Tentang Kami</span>
                <span class="text-sm">Peta Situs</span>
            </div>
            <div class="text-center mt-4">
                <p class="text-sm">Unit Kegiatan Mahasiswa Penyiaran Berita Mahasiswa Politeknik Negeri Jember. © 2024, Copyright KabarU.co.id</p>
            </div>
        </div>
    </footer>

    <script>
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');

        profileButton.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        const slider = document.getElementById('slider');
        const prevButton = document.getElementById('prev');
        const nextButton = document.getElementById('next');
        const dots = document.querySelectorAll('[data-slide]');
        let currentSlide = 0;

        // Update slider position and dot indicator
        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach(dot => dot.classList.remove('bg-gray-800', 'w-6', 'h-2'));
            dots.forEach(dot => dot.classList.add('w-3', 'h-3', 'rounded-full'));
            dots[currentSlide].classList.add('bg-gray-800', 'w-6', 'h-2', 'rounded-full');
        }

        // Next slide
        nextButton.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % dots.length;
            updateSlider();
        });

        // Previous slide
        prevButton.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + dots.length) % dots.length;
            updateSlider();
        });

        // Dot indicator click
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                currentSlide = parseInt(dot.getAttribute('data-slide'));
                updateSlider();
            });
        });

        // Initialize slider
        updateSlider();
    </script>
</body>
</html>