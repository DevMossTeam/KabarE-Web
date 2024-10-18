<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Pembungkus Edit Profile -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold mb-2">Edit Profile</h3>
                <div class="relative">
                    <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                        <i class="fas fa-user text-8xl text-gray-500"></i>
                        <div class="absolute bottom-1 right-1 bg-blue-500 text-white px-3 py-2 rounded-full">
                            <i class="fas fa-camera text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                <p class="text-center text-gray-500 mt-2">Foto ini akan muncul dalam profil anda, ayo pasang profile terbaikmu!</p>
                <button class="bg-blue-500 text-white w-full py-1 rounded-lg mt-2">Ganti Foto</button>
            </div>
        </div>
    </div>

    <!-- Pembungkus Informasi Akun -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-[20px] font-regular mb-4 ml-10">Informasi Akun Anda</h2>
            <div class="space-y-4 max-w-5xl mx-auto">
                <div class="flex items-center pb-2">
                    <i class="fas fa-user text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Nama Lengkap</p>
                            <p class="font-semibold" id="namaLengkapText">Chiquita Clairina K</p>
                            <input type="text" value="Chiquita Clairina K" class="w-full mt-2 border border-gray-300 rounded px-2 py-1 focus:outline-none hidden" id="namaLengkapInput">
                        </div>
                        <div class="border-t border-gray-300 mt-2 w-full"></div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer" onclick="toggleEdit('namaLengkap')"></i>
                </div>
                <div class="flex items-center pb-2">
                    <i class="fas fa-user-tag text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Username</p>
                            <p class="font-semibold" id="usernameText">Chiquita</p>
                            <input type="text" value="Chiquita" class="w-full mt-2 border border-gray-300 rounded px-2 py-1 focus:outline-none hidden" id="usernameInput">
                        </div>
                        <div class="border-t border-gray-300 mt-2 w-full"></div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer" onclick="toggleEdit('username')"></i>
                </div>
                <div class="flex items-center pb-2">
                    <i class="fas fa-briefcase text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Posisi</p>
                            <p class="font-semibold" id="posisiText">Jurnalis / Penulis</p>
                            <input type="text" value="Jurnalis / Penulis" class="w-full mt-2 border border-gray-300 rounded px-2 py-1 focus:outline-none hidden" id="posisiInput">
                        </div>
                        <div class="border-t border-gray-300 mt-2 w-full"></div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer" onclick="toggleEdit('posisi')"></i>
                </div>
                <div class="flex items-center pb-2">
                    <i class="fas fa-info-circle text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Info Lainnya</p>
                            <p class="font-semibold" id="infoLainnyaText">Mahasiswa Jurusan Teknologi Informasi</p>
                            <input type="text" value="Mahasiswa Jurusan Teknologi Informasi" class="w-full mt-2 border border-gray-300 rounded px-2 py-1 focus:outline-none hidden" id="infoLainnyaInput">
                        </div>
                        <div class="border-t border-gray-300 mt-2 w-full"></div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer" onclick="toggleEdit('infoLainnya')"></i>
                </div>
                <div class="flex items-center pb-2">
                    <i class="fas fa-align-left text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Bio Anda</p>
                            <p class="font-semibold" id="bioText">Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa</p>
                            <input type="text" value="Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa" class="w-full mt-2 border border-gray-300 rounded px-2 py-1 focus:outline-none hidden" id="bioInput">
                        </div>
                        <div class="border-t border-gray-300 mt-2 w-full"></div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer" onclick="toggleEdit('bio')"></i>
                </div>
                <div class="flex items-center pb-2">
                    <div class="flex items-center space-x-2 w-1/2">
                        <i class="fas fa-link text-gray-500"></i>
                        <div class="flex w-full border border-gray-300 rounded-full focus-within:border-blue-500 relative">
                            <input type="text" placeholder="Masukkan link Social Media anda" class="rounded-l-full px-4 py-2 w-full focus:outline-none">
                            <div class="absolute inset-y-0 right-12 flex items-center pointer-events-none">
                                <span class="border-l border-gray-300 h-full"></span>
                            </div>
                            <button class="text-blue-500 px-4 py-2 rounded-r-full text-xl">+</button>
                        </div>
                    </div>
                    <div class="flex-1 ml-10">
                        <div class="flex items-center space-x-6 justify-end">
                            <i class="fas fa-link text-gray-500"></i>
                            <span class="text-gray-700 ml-4">ndikka005</span>
                            <i class="fas fa-pen text-blue-500 cursor-pointer text-sm ml-4"></i>
                        </div>
                        <div class="flex items-center space-x-6 mt-2 justify-end">
                            <i class="fas fa-link text-gray-500"></i>
                            <span class="text-gray-700 ml-4">asyourArt01</span>
                            <i class="fas fa-pen text-blue-500 cursor-pointer text-sm ml-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        const inputElement = document.getElementById(`${field}Input`);
        
        if (textElement.style.display !== 'none') {
            textElement.style.display = 'none';
            inputElement.classList.remove('hidden');
            inputElement.focus();
        } else {
            textElement.style.display = 'block';
            inputElement.classList.add('hidden');
        }
    }

    document.querySelectorAll('.flex a').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.flex a').forEach(item => item.classList.remove('border-b-2', 'border-blue-500'));
            this.classList.add('border-b-2', 'border-blue-500');
        });
    });
</script>
