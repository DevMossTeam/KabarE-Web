<?php include '../header.php'; ?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-16">
    <!-- Welcome Text -->
    <div class="container mx-auto text-center mb-16">
        <h1 class="text-3xl font-bold mb-6">Latest News</h1>
        <p class="mb-8">Stay updated with the latest news and articles.</p>
    </div>

    <!-- Breaking News -->
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Breaking News</h2>
            <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
            <a href="#" class="text-blue-500 hover:underline">Read more</a>
        </div>

        <!-- News Articles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Article 1 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 4</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Article 2 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 5</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Article 3 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 6</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Tambahkan lebih banyak artikel sesuai kebutuhan -->
        </div>

        <!-- Additional Content -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Global News</h2>
            <ul class="list-disc pl-5">
                <li class="text-gray-700 mb-2">Update 1: Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                <li class="text-gray-700 mb-2">Update 2: Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</li>
                <li class="text-gray-700 mb-2">Update 3: Sed nisi. Nulla quis sem at nibh elementum imperdiet.</li>
                <li class="text-gray-700 mb-2">Update 4: Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.</li>
            </ul>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>