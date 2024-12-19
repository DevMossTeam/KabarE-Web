<?php 
include '../header.php';
include '../category_header.php';
renderCategoryHeader('Hubungi Kami');
?>

<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <div class="flex items-center mb-4">
            <img src="../../assets/web-icon/Ic-main-KabarE.svg" alt="Logo KabarE" class="w-8 h-8 mr-4">
            <h1 class="text-2xl font-bold text-gray-800">Hubungi Kami</h1>
        </div>

        <!-- Formulir -->
        <form id="contactForm" class="w-full">
            <textarea 
                id="message" 
                name="message" 
                placeholder="Jelaskan masalah anda" 
                class="w-full p-4 border rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 resize-none"
                style="background-color: #EEEEEE; height: 200px;" 
                required></textarea>
            <button 
                type="submit" 
                class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-lg py-3 px-6 rounded-lg shadow transition-all duration-300 ease-in-out transform hover:scale-105">
                Kirim
            </button>
        </form>

        <!-- Informasi Kontak dengan teks rata kiri -->
        <div class="mt-6 text-left text-gray-600">
            <p>Kami akan merespon anda melalui email atau anda dapat menghubungi kami dengan mengirimkan pesan melalui email resmi kami <a href="mailto:devmoss@gmail.com" class="text-blue-500 hover:underline">devmoss@gmail.com</a></p>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 transform transition-all duration-300 ease-in-out scale-95 opacity-0 modal-content">
        <div class="flex items-center mb-4">
            <img src="https://img.icons8.com/fluency/48/checkmark.png" alt="Success Icon" class="w-12 h-12 mr-4">
            <h3 class="text-xl font-semibold text-green-500">Pesan Berhasil Dikirim!</h3>
        </div>
        <p class="text-gray-600 mb-6">Pesan Anda telah berhasil dikirim. Terima kasih atas masukan Anda. Kami akan segera meresponnya melalui email.</p>
        
        <div class="flex justify-end">
            <button id="closeModal" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Login -->
<div id="loginModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 transform transition-all duration-300 ease-in-out scale-95 opacity-0 modal-content">
        <div class="flex items-center mb-4">
            <img src="https://img.icons8.com/color/48/warning-shield.png" alt="Warning Icon" class="w-12 h-12 mr-4">
            <h3 class="text-xl font-semibold text-red-500">Harap Login</h3>
        </div>
        <p class="text-gray-600 mb-6">Anda harus login terlebih dahulu untuk mengirim pesan. Silakan login melalui halaman login.</p>
        
        <div class="flex justify-end">
            <a href="../../user-auth/login.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                Login
            </a>
            <button id="closeLoginModal" class="ml-4 bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>

<script>
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Mencegah form submit default

    var message = document.getElementById('message').value;

    // Simulasi cek user_id (gunakan API atau variable PHP untuk implementasi nyata)
    var userId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";

    if (!userId) {
        showLoginModal(); // Tampilkan modal login jika user_id null
        return;
    }

    // Cek apakah pesan kosong
    if (!message.trim()) {
        alert("Pesan tidak boleh kosong!");
        return;
    }

    // Mengirim data ke API menggunakan AJAX
    var formData = new FormData();
    formData.append('message', message);

    fetch('../../api/feedback/masukan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Jika berhasil, reset form dan tampilkan modal
        if (data.status === 'success') {
            document.getElementById('contactForm').reset();
            showModal(); // Tampilkan modal sukses
        }
    })
    .catch(error => {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan saat mengirim pesan.');
    });
});

// Fungsi untuk menampilkan modal sukses
function showModal() {
    const modal = document.getElementById('confirmationModal');
    const modalContent = modal.querySelector('.modal-content');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 100);
}

// Fungsi untuk menampilkan modal login
function showLoginModal() {
    const modal = document.getElementById('loginModal');
    const modalContent = modal.querySelector('.modal-content');

    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 100);
}

// Fungsi untuk menutup modal login
function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    const modalContent = modal.querySelector('.modal-content');

    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Event listener untuk tombol tutup modal login
document.getElementById('closeLoginModal').addEventListener('click', closeLoginModal);

// Event listener untuk klik di luar modal login
document.getElementById('loginModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeLoginModal();
    }
});

// Fungsi untuk menutup modal sukses
function closeModal() {
    const modal = document.getElementById('confirmationModal');
    const modalContent = modal.querySelector('.modal-content');
    
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Event listener untuk tombol tutup modal sukses
document.getElementById('closeModal').addEventListener('click', closeModal);

// Event listener untuk klik di luar modal sukses
document.getElementById('confirmationModal').addEventListener('click', function (event) {
    if (event.target === this) {
        closeModal();
    }
});
</script>
