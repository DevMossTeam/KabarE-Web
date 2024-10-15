<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Pengaturan Umum</h2>
    <p>Konten pengaturan umum akan ditampilkan di sini.</p>
    <!-- Tambahkan konten pengaturan umum lainnya -->
</div>

<script>
    document.querySelectorAll('.flex a').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.flex a').forEach(item => item.classList.remove('border-b-2', 'border-blue-500'));
            this.classList.add('border-b-2', 'border-blue-500');
        });
    });
</script> 
