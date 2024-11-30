<?php include 'header & footer/header.php'; ?>

<div class="container mx-auto mt-8 mb-16 px-4 lg:px-12" id="searchResults">
    <div class="mb-4">
        <h1 class="text-xl font-bold mb-1">Hasil Pencarian untuk "<span id="searchQuery"></span>"</h1>
        <p class="text-sm text-gray-600"><span id="totalResults">0</span> data ditemukan</p>
    </div>
    <div id="newsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Hasil pencarian akan ditampilkan di sini -->
    </div>
    <div id="pagination" class="flex justify-center mt-4">
        <!-- Pagination akan ditampilkan di sini -->
    </div>
</div>

<script>
async function fetchSearchResults(query, page = 1) {
    try {
        const response = await fetch(`api/pencarian/pencarian.php?query=${encodeURIComponent(query)}&page=${page}`);
        const data = await response.json();
        
        if (data.status === 'success') {
            displayResults(data);
            displayPagination(data);
        } else {
            displayError('Terjadi kesalahan saat mencari data');
        }
    } catch (error) {
        displayError('Terjadi kesalahan pada server');
    }
}

function displayResults(data) {
    document.getElementById('searchQuery').textContent = new URLSearchParams(window.location.search).get('query');
    document.getElementById('totalResults').textContent = data.total;
    
    const container = document.getElementById('newsContainer');
    container.innerHTML = '';
    
    if (data.data.length === 0) {
        container.innerHTML = `
            <div class="text-center col-span-3">
                <i class="fas fa-search-minus text-gray-400 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Tidak Ada Hasil Ditemukan</h2>
                <p class="text-gray-700 mb-4">Silakan coba dengan kata kunci yang berbeda.</p>
            </div>`;
        return;
    }
    
    data.data.forEach(news => {
        container.innerHTML += `
            <div>
                <a href="../category/news-detail.php?id=${news.id}">
                    <img src="${news.gambar}" class="w-full h-48 object-cover rounded-lg mb-4">
                </a>
                <a href="../category/news-detail.php?id=${news.id}">
                    <h2 class="text-2xl font-bold mb-2">${news.judul}</h2>
                </a>
                <p class="text-gray-500 mb-2">${new Date(news.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                <p class="text-gray-700 mb-4">${news.deskripsi}</p>
            </div>`;
    });
}

function displayPagination(data) {
    const pagination = document.getElementById('pagination');
    if (data.total_pages <= 1) {
        pagination.style.display = 'none';
        return;
    }

    let html = '<nav class="inline-flex shadow-sm -space-x-px" aria-label="Pagination">';
    const query = new URLSearchParams(window.location.search).get('query');
    
    // Tombol Previous
    if (data.current_page > 1) {
        html += `<a href="#" onclick="fetchSearchResults('${query}', ${data.current_page - 1}); return false;" 
                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                   <span>Prev</span>
                </a>`;
    } else {
        html += `<span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500">
                    <span>Prev</span>
                </span>`;
    }

    // Nomor halaman
    const startPage = Math.max(1, data.current_page - 5);
    const endPage = Math.min(data.total_pages, startPage + 9);
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === data.current_page) {
            html += `<span class="z-10 bg-blue-500 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                        ${i}
                    </span>`;
        } else {
            html += `<a href="#" onclick="fetchSearchResults('${query}', ${i}); return false;" 
                       class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                       ${i}
                    </a>`;
        }
    }

    // Tombol Next
    if (data.current_page < data.total_pages) {
        html += `<a href="#" onclick="fetchSearchResults('${query}', ${data.current_page + 1}); return false;" 
                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                   <span>Next</span>
                </a>`;
    } else {
        html += `<span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500">
                    <span>Next</span>
                </span>`;
    }

    html += '</nav>';
    pagination.innerHTML = html;
}

function displayError(message) {
    const container = document.getElementById('newsContainer');
    container.innerHTML = `
        <div class="text-center col-span-3">
            <i class="fas fa-exclamation-circle text-red-500 text-6xl mb-4"></i>
            <h2 class="text-2xl font-bold mb-2">Error</h2>
            <p class="text-gray-700 mb-4">${message}</p>
        </div>`;
}

// Inisialisasi pencarian saat halaman dimuat
const urlParams = new URLSearchParams(window.location.search);
const query = urlParams.get('query') || '';
const page = parseInt(urlParams.get('page')) || 1;
fetchSearchResults(query, page);
</script>

<?php include 'header & footer/footer.php'; ?>
