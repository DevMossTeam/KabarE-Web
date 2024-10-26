<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penulisan Artikel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-6">
        <form id="articleForm" action="publishAuthor.php" method="post" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Penulisan Artikel</h1>
                <div class="flex space-x-2">
                    <button type="button" id="previewButton" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                        <i class="fas fa-eye mr-2"></i> Pratinjau
                    </button>
                    <button type="submit" id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Publikasi
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="title" class="block text-lg font-semibold mb-2">Judul Artikel</label>
                <input type="text" name="title" id="title" placeholder="Tulis judul artikelmu sendiri" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="articleContent" class="block text-lg font-semibold mb-2">Form Penulisan Artikel</label>
                <div class="flex flex-wrap space-x-2 mb-2 relative">
                    <button type="button" onclick="toggleCommand(this, 'undo')" class="p-2 hover:bg-gray-300"><i class="fas fa-undo-alt"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'redo')" class="p-2 hover:bg-gray-300"><i class="fas fa-redo-alt"></i></button>
                    <select onchange="document.execCommand('formatBlock', false, this.value)" class="p-2 hover:bg-gray-300 border-2 border-transparent">
                        <option value="p">Normal</option>
                        <option value="h1">Heading 1</option>
                        <option value="h2">Heading 2</option>
                        <option value="h3">Heading 3</option>
                    </select>
                    <button type="button" onclick="toggleCommand(this, 'bold')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-bold"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'italic')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-italic"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'underline')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-underline"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'strikeThrough')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-strikethrough"></i></button>
                    <div class="relative flex items-center">
                        <button type="button" id="fontColorButton" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-font"></i></button>
                        <input type="color" id="fontColor" class="absolute top-full left-0 mt-1 w-full h-8 opacity-0 cursor-pointer">
                        <span id="fontColorIndicator" class="h-1 w-4 bg-black rounded-full absolute bottom-0 left-1/2 transform -translate-x-1/2"></span>
                    </div>
                    <div class="relative flex items-center">
                        <button type="button" id="highlightColorButton" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-highlighter"></i></button>
                        <input type="color" id="highlightColor" class="absolute top-full left-0 mt-1 w-full h-8 opacity-0 cursor-pointer">
                        <span id="highlightColorIndicator" class="h-1 w-4 bg-yellow-300 rounded-full absolute bottom-0 left-1/2 transform -translate-x-1/2"></span>
                    </div>
                    <button type="button" onclick="toggleCommand(this, 'justifyLeft')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-align-left"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'justifyCenter')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-align-center"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'justifyRight')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-align-right"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'justifyFull')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-align-justify"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'insertUnorderedList')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-list-ul"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'insertOrderedList')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-list-ol"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'createLink', prompt('Enter URL:'))" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-link"></i></button>
                    <button type="button" id="imageButton" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-image"></i></button>
                    <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="insertImage(event)">
                    <button type="button" onclick="toggleCommand(this, 'insertHTML', '<blockquote>' + window.getSelection().toString() + '</blockquote>')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-quote-right"></i></button>
                    <button type="button" onclick="toggleCommand(this, 'removeFormat')" class="p-2 hover:bg-gray-300 border-2 border-transparent"><i class="fas fa-eraser"></i></button>
                </div>
                <div id="editor" contenteditable="true" class="border border-gray-300 rounded p-4 h-96 overflow-auto focus:outline-none"></div>
                <input type="hidden" name="content" id="hiddenContent">
            </div>
        </form>
    </div>

    <div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-2"></i>
                <h2 class="text-xl font-bold" id="popupTitle">Pesan</h2>
            </div>
            <p class="mb-4" id="popupMessage">Pesan kesalahan.</p>
            <button id="closePopup" class="bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>

    <script>
        const editor = document.getElementById('editor');
        const hiddenContent = document.getElementById('hiddenContent');
        const previewButton = document.getElementById('previewButton');
        const publishButton = document.getElementById('publishButton');
        const popup = document.getElementById('popup');
        const closePopup = document.getElementById('closePopup');
        const popupTitle = document.getElementById('popupTitle');
        const popupMessage = document.getElementById('popupMessage');
        const articleForm = document.getElementById('articleForm');
        const titleInput = document.getElementById('title');
        const imageButton = document.getElementById('imageButton');
        const imageInput = document.getElementById('imageInput');
        const fontColorButton = document.getElementById('fontColorButton');
        const highlightColorButton = document.getElementById('highlightColorButton');
        const fontColor = document.getElementById('fontColor');
        const highlightColor = document.getElementById('highlightColor');
        const fontColorIndicator = document.getElementById('fontColorIndicator');
        const highlightColorIndicator = document.getElementById('highlightColorIndicator');
        const activeCommands = new Set();

        // Load data from localStorage
        window.addEventListener('load', () => {
            const savedTitle = localStorage.getItem('articleTitle');
            const savedContent = localStorage.getItem('articleContent');

            if (savedTitle) {
                titleInput.value = savedTitle;
            }

            if (savedContent) {
                editor.innerHTML = savedContent;
            }
        });

        // Save data to localStorage
        titleInput.addEventListener('input', () => {
            localStorage.setItem('articleTitle', titleInput.value);
        });

        editor.addEventListener('input', () => {
            localStorage.setItem('articleContent', editor.innerHTML);
        });

        function toggleCommand(button, command, value = null) {
            if (command === 'blockquote') {
                document.execCommand('formatBlock', false, 'blockquote');
                return;
            }

            if (command.includes('justify')) {
                // Nonaktifkan semua tombol perataan
                document.querySelectorAll('[onclick*="justify"]').forEach(btn => {
                    btn.classList.remove('border-black');
                });
                // Hapus perintah perataan dari set aktif
                activeCommands.forEach(cmd => {
                    if (cmd.includes('justify')) {
                        activeCommands.delete(cmd);
                    }
                });
            }

            if (activeCommands.has(command)) {
                document.execCommand(command, false, value);
                if (!isNoBorderCommand(command)) {
                    button.classList.remove('border-black');
                }
                activeCommands.delete(command);
            } else {
                document.execCommand(command, false, value);
                if (!isNoBorderCommand(command)) {
                    button.classList.add('border-black');
                }
                activeCommands.add(command);
            }
        }

        function isNoBorderCommand(command) {
            return ['undo', 'redo', 'removeFormat'].includes(command);
        }

        editor.addEventListener('mouseup', () => {
            activeCommands.forEach(command => {
                document.execCommand(command, false, null);
            });
        });

        function showPopup(title, message) {
            popupTitle.textContent = title;
            popupMessage.textContent = message;
            popup.classList.remove('hidden');
            popup.querySelector('div').classList.add('scale-100');
        }

        function hidePopup() {
            popup.classList.add('hidden');
            popup.querySelector('div').classList.remove('scale-100');
        }

        function checkContent() {
            const content = editor.innerText.trim();
            if (content === '') {
                showPopup('Konten Masih Kosong', 'Anda belum menambahkan konten disini.');
                return false;
            }
            return true;
        }

        function insertImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    editor.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
            // Reset the input value to allow the same file to be selected again
            event.target.value = '';
        }

        imageButton.addEventListener('click', () => {
            imageInput.click();
        });

        previewButton.addEventListener('click', (e) => {
            if (checkContent()) {
                hiddenContent.value = editor.innerHTML;
                articleForm.action = 'previewAuthor.php'; // Set action to previewAuthor.php
                articleForm.submit();
            } else {
                e.preventDefault();
            }
        });

        publishButton.addEventListener('click', (e) => {
            if (checkContent()) {
                hiddenContent.value = editor.innerHTML;
                // Remove data from localStorage only after successful publication
                localStorage.removeItem('articleTitle');
                localStorage.removeItem('articleContent');
            } else {
                e.preventDefault();
            }
        });

        closePopup.addEventListener('click', hidePopup);

        window.addEventListener('click', (e) => {
            if (e.target === popup) hidePopup();
        });
    </script>
</body>
</html>
