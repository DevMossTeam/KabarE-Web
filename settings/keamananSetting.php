<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Pembungkus Keamanan -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col items-start">
                <h3 class="text-lg font-semibold mb-2">Keamanan</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-blue-500"></i>
                    <span class="text-gray-700">Pengaturan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembungkus Pengaturan Keamanan -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-[20px] font-regular mb-4 ml-10">Pengaturan Keamanan</h2>
            <div class="space-y-4 max-w-5xl mx-auto">
                <div class="flex items-center pb-2">
                    <i class="fas fa-envelope text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-semibold" id="emailText">E4123@polije.ac.id</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="emailInfo" contenteditable="false" data-default-text="Informasi ini harus akurat">Informasi ini harus akurat</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('email')"></i>
                </div>
                
                <div class="flex items-center pb-2">
                    <i class="fas fa-lock text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Kata Sandi</p>
                            <p class="font-semibold" id="passwordText">********</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="passwordInfo" contenteditable="false" data-default-text="Informasi ini harus akurat">Informasi ini harus akurat</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('password')"></i>
                </div>

                <!-- Bantuan -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Bantuan</h3>
                    <div class="flex items-center space-x-2 mt-4">
                        <i class="fas fa-question-circle text-gray-500"></i>
                        <div>
                            <p class="text-gray-700">Lupa Kata Sandi?</p>
                            <p class="text-blue-500 hover:underline">Hubungi kami untuk mengatasi permasalahan anda</p>
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
