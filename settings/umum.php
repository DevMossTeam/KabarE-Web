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
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="namaLengkapInfo" contenteditable="false" data-default-text="Informasi ini harus akurat">Informasi ini harus akurat</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('namaLengkap')"></i>
                </div>
                <!-- Ulangi pola ini untuk setiap bagian informasi -->
                <!-- Username -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-user-tag text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Username</p>
                            <p class="font-semibold" id="usernameText">Chiquita</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="usernameInfo" contenteditable="false" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('username')"></i>
                </div>
                <!-- Posisi -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-briefcase text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Posisi</p>
                            <p class="font-semibold" id="posisiText">Jurnalis / Penulis</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="posisiInfo" contenteditable="false" data-default-text="Informasi ini tidak dapat diubah oleh anda">Informasi ini tidak dapat diubah oleh anda</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('posisi')"></i>
                </div>
                <!-- Info Lainnya -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-info-circle text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Info Lainnya</p>
                            <p class="font-semibold" id="infoLainnyaText">Mahasiswa Jurusan Teknologi Informasi</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="infoLainnyaInfo" contenteditable="false" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('infoLainnya')"></i>
                </div>
                <!-- Bio Anda -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-align-left text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Bio Anda</p>
                            <p class="font-semibold" id="bioText">Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="bioInfo" contenteditable="false" data-default-text="Informasi ini akan terlihat pembaca">Informasi ini akan terlihat pembaca</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('bio')"></i>
                </div>
                <!-- Social Media -->
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
    let currentEdit = null;

    function toggleEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);

        if (currentEdit && currentEdit !== field) {
            cancelEdit(currentEdit);
        }

        if (currentEdit === field) {
            saveEdit(field);
        } else {
            infoElement.contentEditable = true;
            infoElement.classList.add('focus:border-b-2', 'focus:border-blue-500');
            infoElement.textContent = '';
            infoElement.focus();
            currentEdit = field;
        }
    }

    function saveEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        const infoElement = document.getElementById(`${field}Info`);

        if (infoElement.textContent.trim() !== '') {
            textElement.textContent = infoElement.textContent;
        }
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        infoElement.textContent = infoElement.getAttribute('data-default-text');
        currentEdit = null;
    }

    function cancelEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        infoElement.textContent = infoElement.getAttribute('data-default-text');
        currentEdit = null;
    }

    document.addEventListener('click', function(event) {
        if (currentEdit && !event.target.closest('.flex')) {
            cancelEdit(currentEdit);
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && currentEdit) {
            event.preventDefault();
            saveEdit(currentEdit);
        }
    });
</script>
