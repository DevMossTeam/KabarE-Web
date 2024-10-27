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
        <h1 class="text-2xl font-bold mb-6">Penulisan Artikel</h1>

        <div class="flex">
            <div class="w-2/3 pr-8">
                <div class="mb-4">
                    <label for="title" class="block text-lg font-semibold mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 outline-none">
                </div>

                <div class="mb-2">
                    <label for="articleContent" class="block text-lg font-semibold mb-2">Form Penulisan Artikel</label>
                    <div class="flex items-center space-x-2 mb-2 overflow-x-auto overflow-y-hidden whitespace-nowrap relative">
                        <!-- Toolbar -->
                        <button type="button" onclick="toggleCommand(this, 'undo')" class="p-2 hover:bg-gray-300 rounded"><i class="fas fa-undo-alt"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'redo')" class="p-2 hover:bg-gray-300 rounded"><i class="fas fa-redo-alt"></i></button>
                        <span class="border-r border-gray-300 h-6"></span>
                        <select onchange="document.execCommand('formatBlock', false, this.value)" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded">
                            <option value="p">Normal</option>
                            <option value="h1">Heading 1</option>
                            <option value="h2">Heading 2</option>
                            <option value="h3">Heading 3</option>
                        </select>
                        <button type="button" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded relative" onclick="toggleDropdown('fontSelector', this)">
                            <i class="fas fa-font"></i>
                        </button>
                        <button type="button" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded relative" onclick="toggleDropdown('fontSizeSelector', this)">
                            <i class="fas fa-text-height"></i>
                        </button>
                        <span class="border-r border-gray-300 h-6"></span>
                        <button type="button" onclick="toggleCommand(this, 'bold')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-bold"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'italic')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-italic"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'underline')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-underline"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'strikeThrough')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-strikethrough"></i></button>
                        <div class="relative flex items-center">
                            <button type="button" id="fontColorButton" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-font"></i></button>
                            <input type="color" id="fontColor" class="absolute top-full left-0 mt-1 w-full h-8 opacity-0 cursor-pointer">
                            <span id="fontColorIndicator" class="h-1 w-4 bg-black rounded-full absolute bottom-0 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <div class="relative flex items-center">
                            <button type="button" id="highlightColorButton" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-highlighter"></i></button>
                            <input type="color" id="highlightColor" class="absolute top-full left-0 mt-1 w-full h-8 opacity-0 cursor-pointer">
                            <span id="highlightColorIndicator" class="h-1 w-4 bg-yellow-300 rounded-full absolute bottom-0 left-1/2 transform -translate-x-1/2"></span>
                        </div>
                        <span class="border-r border-gray-300 h-6"></span>
                        <button type="button" id="linkButton" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-link"></i></button>
                        <button type="button" id="imageButton" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-image"></i></button>
                        <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="insertImage(event)">
                        <button type="button" id="videoButton" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-video"></i></button>
                        <input type="file" id="videoInput" accept="video/*" class="hidden" onchange="insertVideo(event)">
                        <span class="border-r border-gray-300 h-6"></span>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="toggleAlign(this, 'justifyLeft')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-align-left"></i></button>
                            <button type="button" onclick="toggleAlign(this, 'justifyCenter')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-align-center"></i></button>
                            <button type="button" onclick="toggleAlign(this, 'justifyRight')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-align-right"></i></button>
                            <button type="button" onclick="toggleAlign(this, 'justifyFull')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-align-justify"></i></button>
                            <button type="button" onclick="toggleCommand(this, 'indent')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-indent"></i></button>
                            <button type="button" onclick="toggleCommand(this, 'outdent')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-outdent"></i></button>
                        </div>
                        <span class="border-r border-gray-300 h-6"></span>
                        <button type="button" onclick="toggleCommand(this, 'insertOrderedList')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-list-ol"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'insertUnorderedList')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-list-ul"></i></button>
                        <span class="border-r border-gray-300 h-6"></span>
                        <button type="button" onclick="toggleCommand(this, 'blockquote')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-quote-right"></i></button>
                        <button type="button" onclick="toggleCommand(this, 'removeFormat')" class="p-2 hover:bg-gray-300 border-2 border-transparent rounded"><i class="fas fa-eraser"></i></button>
                    </div>
                    <div id="editor" contenteditable="true" class="border border-gray-300 rounded p-4 h-96 overflow-auto focus:outline-none"></div>
                    <input type="hidden" name="content" id="hiddenContent">
                </div>
            </div>

            <div class="w-1/3 pl-8">
                <div class="flex space-x-2 items-center mb-4">
                    <i class="fas fa-cloud cloud-icon" id="cloudIcon"></i>
                    <button type="button" id="previewButton" class="text-gray-800 border border-gray-400 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                        <i class="fas fa-eye mr-2"></i> Pratinjau
                    </button>
                    <button type="submit" id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Publikasi
                    </button>
                </div>

                <h2 class="text-lg font-bold mb-2">Pengaturan Publikasi</h2>
                <div class="mb-4">
                    <h2 class="font-semibold">Visibilitas</h2>
                    <p class="text-sm text-gray-600">Atur visibilitas artikel agar dapat dilihat oleh kelompok yang diinginkan.</p>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="visibility" value="public" class="form-radio" checked>
                            <span class="ml-2">Public</span>
                        </label>
                        <label class="inline-flex items-center ml-4">
                            <input type="radio" name="visibility" value="private" class="form-radio">
                            <span class="ml-2">Private</span>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <h2 class="font-semibold">Tambahkan Tag</h2>
                    <p class="text-sm text-gray-600">Tambahkan tag untuk membantu pembaca menemukan berita artikel.</p>
                    <div id="tagContainer" class="flex flex-wrap mb-2"></div>
                    <input type="text" id="tagInput" placeholder="Tambah tag" class="w-full border-b-2 border-gray-300 focus:outline-none">
                </div>
                <div>
                    <h2 class="font-semibold">Kategori</h2>
                    <p class="text-sm text-gray-600">Menambah kategori untuk mempermudah pencarian artikel.</p>
                    <select class="w-full border-b-2 border-gray-300 focus:outline-none mt-2">
                        <option>Politik</option>
                        <option>Ekonomi</option>
                        <option>Teknologi</option>
                        <option>Olahraga</option>
                        <option>Hiburan</option>
                        <option>Kesehatan</option>
                    </select>
                </div>
            </div>
        </div>
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

    <div id="fontSelector" class="dropdown hidden absolute bg-white border border-gray-300 rounded shadow-lg mt-2 z-10">
        <ul class="p-2">
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Arial')">Arial</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Courier New')">Courier New</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Georgia')">Georgia</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Helvetica')">Helvetica</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Times New Roman')">Times New Roman</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Trebuchet MS')">Trebuchet MS</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFont('Verdana')">Verdana</li>
        </ul>
    </div>

    <div id="fontSizeSelector" class="dropdown hidden absolute bg-white border border-gray-300 rounded shadow-lg mt-2 z-10">
        <ul class="p-2">
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('8px')">Terkecil</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('10px')">Kecil</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('12px')">Normal</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('14px')">Sedang</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('18px')">Besar</li>
            <li class="hover:bg-gray-200 p-1 cursor-pointer" onclick="changeFontSize('24px')">Terbesar</li>
        </ul>
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
        const activeCommands = new Set();
        const cloudIcon = document.getElementById('cloudIcon');
        let typingTimer;

        window.addEventListener('DOMContentLoaded', () => {
            document.execCommand('justifyLeft', false, null);
            document.querySelector('[onclick*="justifyLeft"] i').classList.add('text-blue-500');
            editor.focus();
        });

        function toggleCommand(button, command) {
            if (document.queryCommandSupported(command)) {
                const isActive = document.queryCommandState(command);
                document.execCommand(command, false, null);
                editor.focus();
                updateButtonState(button, command, !isActive);
            } else {
                console.warn(`Perintah ${command} tidak didukung di browser ini.`);
            }
        }

        function execCommandWithoutState(command) {
            if (document.queryCommandSupported(command)) {
                document.execCommand(command, false, null);
                editor.focus();
            } else {
                console.warn(`Perintah ${command} tidak didukung di browser ini.`);
            }
        }

        function toggleAlign(button, command) {
            const alignButtons = document.querySelectorAll('[onclick*="justify"]');
            alignButtons.forEach(btn => btn.querySelector('i').classList.remove('text-blue-500'));

            document.execCommand(command, false, null);
            editor.focus();
            button.querySelector('i').classList.add('text-blue-500');
        }

        function updateButtonState(button, command, isActive) {
            const icon = button.querySelector('i');
            if (['bold', 'italic', 'underline', 'strikeThrough'].includes(command)) {
                if (isActive) {
                    icon.classList.add('text-blue-500');
                } else {
                    icon.classList.remove('text-blue-500');
                }
            }
        }

        document.querySelectorAll('.toolbar-button').forEach(button => {
            button.addEventListener('click', () => {
                const command = button.getAttribute('data-command');
                toggleCommand(button, command);
            });
        });

        editor.addEventListener('focus', () => {
            activeCommands.forEach(command => {
                document.execCommand(command, false, null);
            });
            updateAllButtonStates();
        });

        editor.addEventListener('blur', () => {
            activeCommands.clear();
            if (document.queryCommandState('bold')) activeCommands.add('bold');
            if (document.queryCommandState('italic')) activeCommands.add('italic');
            if (document.queryCommandState('underline')) activeCommands.add('underline');
            if (document.queryCommandState('strikeThrough')) activeCommands.add('strikeThrough');
        });

        function updateAllButtonStates() {
            document.querySelectorAll('.toolbar-button').forEach(button => {
                const command = button.getAttribute('data-command');
                updateButtonState(button, command, document.queryCommandState(command));
            });
        }

        fontColorButton.addEventListener('click', () => {
            fontColor.click();
        });

        highlightColorButton.addEventListener('click', () => {
            highlightColor.click();
        });

        fontColor.addEventListener('input', (e) => {
            fontColorIndicator.style.backgroundColor = e.target.value;
        });

        highlightColor.addEventListener('input', (e) => {
            highlightColorIndicator.style.backgroundColor = e.target.value;
        });

        fontColor.addEventListener('change', (e) => {
            changeFontColor(e.target.value);
        });

        highlightColor.addEventListener('change', (e) => {
            changeHighlightColor(e.target.value);
        });

        function changeFontColor(color) {
            document.execCommand('styleWithCSS', false, true);
            document.execCommand('foreColor', false, color);
            fontColorIndicator.style.backgroundColor = color;
        }

        function changeHighlightColor(color) {
            document.execCommand('styleWithCSS', false, true);
            document.execCommand('hiliteColor', false, color);
            highlightColorIndicator.style.backgroundColor = color;
        }

        imageButton.addEventListener('click', () => {
            imageInput.click();
        });

        videoButton.addEventListener('click', () => {
            videoInput.click();
        });

        linkButton.addEventListener('click', () => {
            const url = prompt('Masukkan URL:');
            if (url) {
                document.execCommand('createLink', false, url);
            }
        });

        function insertImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const alignmentClass = getCurrentAlignmentClass();
                    const imgHTML = `<img src="${e.target.result}" class="block my-4 ${alignmentClass}">`;
                    document.execCommand('insertHTML', false, imgHTML);
                };
                reader.readAsDataURL(file);
            }
            event.target.value = '';
        }

        function insertVideo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const alignmentClass = getCurrentAlignmentClass();
                    const videoHTML = `<video src="${e.target.result}" controls class="block my-4 ${alignmentClass}"></video><p><br></p>`;
                    document.execCommand('insertHTML', false, videoHTML);
                };
                reader.readAsDataURL(file);
            }
            event.target.value = '';
        }

        function getCurrentAlignmentClass() {
            if (activeCommands.has('justifyCenter')) {
                return 'mx-auto';
            } else if (activeCommands.has('justifyRight')) {
                return 'ml-auto';
            } else {
                return 'mr-auto';
            }
        }

        function updateElementAlignment(element) {
            element.classList.remove('mx-auto', 'ml-auto', 'mr-auto');
            const alignmentClass = getCurrentAlignmentClass();
            element.classList.add(alignmentClass);
        }

        editor.addEventListener('input', () => {
            updateAllButtonStates();
        });

        function toggleDropdown(id, button) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                if (dropdown.id === id) {
                    dropdown.classList.toggle('show');
                    positionDropdown(dropdown, button);
                } else {
                    dropdown.classList.remove('show');
                }
            });
        }

        function positionDropdown(dropdown, button) {
            const rect = button.getBoundingClientRect();
            dropdown.style.top = `${rect.bottom + window.scrollY}px`;
            dropdown.style.left = `${rect.left + window.scrollX}px`;
        }

        function changeFont(fontName) {
            document.execCommand('styleWithCSS', false, true);
            document.execCommand('fontName', false, fontName);
            closeDropdowns();
            editor.focus();
        }

        function changeFontSize(size) {
            document.execCommand('styleWithCSS', false, true);
            document.execCommand('fontSize', false, '7'); // Use a dummy size
            const fontElements = editor.getElementsByTagName('font');
            for (let i = 0; i < fontElements.length; i++) {
                if (fontElements[i].size === '7') {
                    fontElements[i].removeAttribute('size');
                    fontElements[i].style.fontSize = size;
                }
            }
            closeDropdowns();
            editor.focus();
        }

        function closeDropdowns() {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
        }

        previewButton.addEventListener('click', () => {
            if (editor.innerHTML.trim() === '') {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else {
                hiddenContent.value = editor.innerHTML;
                articleForm.action = 'previewAuthor.php';
                articleForm.submit();
            }
        });

        publishButton.addEventListener('click', (e) => {
            if (editor.innerHTML.trim() === '') {
                e.preventDefault();
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else {
                hiddenContent.value = editor.innerHTML;
                articleForm.action = 'publishAuthor.php';
                articleForm.submit();
            }
        });

        function showPopup(message, success = true) {
            const icon = document.querySelector('#popup i');
            popupTitle.textContent = success ? 'Sukses' : 'Kesalahan';
            popupMessage.textContent = message;
            if (success) {
                icon.classList.replace('fa-exclamation-circle', 'fa-check-circle');
                icon.classList.replace('text-red-500', 'text-green-500');
            } else {
                icon.classList.replace('fa-check-circle', 'fa-exclamation-circle');
                icon.classList.replace('text-green-500', 'text-red-500');
            }
            popup.classList.remove('hidden');
            document.querySelector('#popup div').classList.add('scale-100');
        }

        closePopup.addEventListener('click', () => {
            popup.classList.add('hidden');
            document.querySelector('#popup div').classList.remove('scale-100');
        });

        editor.addEventListener('input', () => {
            clearTimeout(typingTimer);
            cloudIcon.classList.add('loading');
            typingTimer = setTimeout(() => {
                cloudIcon.classList.remove('loading');
            }, 2000);
        });
    </script>
</body>
</html>
