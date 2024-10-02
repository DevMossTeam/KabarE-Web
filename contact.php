<?php include 'header.php'; ?>

<!-- Main Content -->
<div class="container mx-auto mt-8 mb-16">
    <h1 class="text-3xl font-bold mb-6 text-center">Contact Us</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <p class="text-gray-700 mb-4">Jika Anda memiliki pertanyaan atau ingin menghubungi kami, silakan gunakan informasi kontak di bawah ini:</p>
        <p class="text-gray-700 mb-4"><strong>Email:</strong> contact@beritaku.com</p>
        <p class="text-gray-700 mb-4"><strong>Telepon:</strong> +62 123 456 789</p>
        <p class="text-gray-700 mb-4"><strong>Alamat:</strong> Jl. Contoh No. 123, Jakarta, Indonesia</p>
        <form action="contact.php" method="POST" class="mt-6">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700">Message</label>
                <textarea id="message" name="message" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Send Message</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>