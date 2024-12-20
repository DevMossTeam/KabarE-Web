<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Pagination Example</title>
    </head>
</head>
<body>
<section class="bg-white ">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm text-center">
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600 ">404</h1>
            <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl ">Something's missing.</p>
            <p class="mb-4 text-lg font-light text-gray-500">Sorry, we can't find that page. You'll find lots to explore on the home page. </p>
            <a href="/KabarE-Web/index.php" class="inline-flex text-white bg-yellow-600 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-4">Back to Homepage</a>
        </div>   
    </div>
</section>
</body>
</html> -->

<?php
// The URL of your API endpoint
$apiUrl = "../../api/newsletterFetch.php";

// Fetch the data using file_get_contents()
$response = file_get_contents($apiUrl);

// Check if the response is valid
if ($response === FALSE) {
    die('Error occurred while fetching data');
}

// Decode the JSON response into a PHP array
$data = json_decode($response, true);

// Check if data is empty or if the 'data' key is not set
if (empty($data['data'])) {
    echo "No data found.";
    exit;
}

// Get all the data without pagination
$dataBerita = $data['data'];
// Your HTML content
$htmlContent = '<h2><br></h2><p class="ql-align-justify"><img src="https://polije.ac.id/wp-content/uploads/2024/10/c1-2.jpg" height="542" width="963" style="max-width: 80%; max-height: 300px; height: auto; width: auto; display: block; margin: 0px auto;"></p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Politeknik Negeri Jember (Polije) mengukuhkan langkahnya dalam memperluas jangkauan internasional melalui partisipasi dalam Kuliah Umum Spesial di Kyungpook National University (KNU) Kampus Daegu. Acara ini dihadiri oleh Wakil Direktur IV Polije, Agung Wahyono, SP., M.Si., Ph.D., dan Ketua Jurusan Teknologi Informasi, Hendra Yufit Riskianwan, S.T., M.T., yang menjadi pembicara utama. Kuliah umum ini berlangsung di Gedung IT5, Ruang 324, dan dihadiri oleh mahasiswa KNU yang antusias.</p><p class="ql-align-justify">“Diskusi ini bertujuan untuk memperkuat hubungan antara kedua institusi, terutama dalam pengembangan program internasional dan pertukaran mahasiswa,” jelas Agung.</p><p class="ql-align-justify">Ia menambahkan bahwa kerjasama ini diharapkan dapat membuka peluang bagi mahasiswa Polije untuk belajar di luar negeri, serta memberikan kesempatan bagi mahasiswa KNU untuk merasakan pengalaman akademis di Indonesia.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Hendra Yufit Riskianwan menyampaikan materi yang sangat relevan dengan kebutuhan saat ini, yakni teknologi terbaru dalam pertanian. Dengan judul presentasi “AI Enabled Smart Monitoring and Controlling of IoT Greenhouse,” ia menjelaskan bagaimana integrasi teknologi kecerdasan buatan (AI) dengan Internet of Things (IoT) dapat mengoptimalkan pemantauan dan manajemen rumah kaca.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">“Teknologi ini tidak hanya meningkatkan efisiensi produksi, tetapi juga membantu petani dalam menghadapi tantangan lingkungan, seperti perubahan iklim,” ungkap Hendra.</p><p class="ql-align-justify">Selama presentasinya, Hendra juga menekankan pentingnya inovasi dalam pertanian modern.</p><p class="ql-align-justify">“Dengan penerapan teknologi yang tepat, kita bisa meningkatkan hasil pertanian dan mengurangi dampak negatif terhadap lingkungan. Hal ini sangat penting untuk mendukung pembangunan berkelanjutan,” tambahnya.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Kegiatan kuliah umum ini menjadi platform bagi mahasiswa KNU untuk mendapatkan wawasan baru mengenai kolaborasi internasional dan perkembangan teknologi di bidang pertanian.</p><p class="ql-align-justify">Acara ini juga menjadi momentum untuk membangun jaringan antara Polije dan KNU. Agung Wahyono menegaskan bahwa kolaborasi ini tidak hanya menguntungkan dalam bidang akademik, tetapi juga dalam penelitian dan pengembangan teknologi.</p><p class="ql-align-justify">“Kami berharap dapat menjalin kerjasama yang lebih luas, termasuk dalam bidang penelitian bersama, untuk menghasilkan inovasi yang bermanfaat bagi kedua negara,” ujarnya.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Melalui kuliah umum ini, Polije menunjukkan komitmennya untuk terus meningkatkan kualitas pendidikan dan memperkuat posisinya sebagai institusi yang responsif terhadap perkembangan global.</p><p class="ql-align-justify">“Kerjasama ini diharapkan dapat terus terjalin dan memberikan manfaat yang berkelanjutan bagi kedua belah pihak, dalam pengembangan pendidikan, penelitian, serta kontribusi terhadap masyarakat,” tutup Agung Wahyono.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Dengan demikian, kegiatan ini tidak hanya menjadi ajang berbagi pengetahuan, tetapi juga menjadi langkah nyata dalam mewujudkan visi Polije untuk menjadi institusi pendidikan yang unggul di tingkat internasional.</p><p class="ql-align-justify"><br></p><p><br></p>';

// Create a DOMDocument instance to parse the HTML
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Disable warnings for invalid HTML
$dom->loadHTML($htmlContent);
libxml_clear_errors();

// Extract the image source (URL), title (first heading), and content (paragraphs)
$imageData = [];
$title = '';
$content = '';

$imageTags = $dom->getElementsByTagName('img');
foreach ($imageTags as $img) {
    $imageData[] = $img->getAttribute('src');
}


$contentTags = $dom->getElementsByTagName('p');
foreach ($contentTags as $p) {
    $content .= $p->textContent . "\n"; // Concatenate all paragraph contents
}

// Create an array with the extracted data
$result = [
    'image' => $imageData,
    'content' => $content
];
?>

<script>
    // Assuming the PHP variable $dataBerita is echoed as a JavaScript variable
    let dataBerita = <?php echo json_encode($dataBerita); ?> ;

    // Loop through the data and log the fields to the console
    dataBerita.forEach(item => {
        console.log("ID Utama: " + item.id);
        console.log("Judul: " + item.judul);
        console.log("Tanggal Diterbitkan: " + item.tanggal_diterbitkan);
        console.log("View Count: " + item.view_count);
        console.log("User ID Indeks: " + item.user_id);
        console.log("User nama_pengguna Indeks: " + item.nama_pengguna);
        console.log("email: " + item.email);
        console.log("profile_pic: " + item.profile_pic);
        console.log("Kategori: " + item.kategori);
        console.log("Konten Artikel: " + item.konten_artikel);
        console.log("Visibilitas: " + item.visibilitas);
        
    });
</script>

<!DOCTYPE html>
<html>

<body style="background-color: #f7fafc; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"
        style="background-color: #f7fafc; margin: 0; padding: 0;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0"
                    style="background-color: #ffffff; padding: 20px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 5px;">
                            <h1 style="font-size: 24px; color: #3b82f6; font-weight: bold; margin: 0;">KabarE</h1>
                            <p style="color: #6b7280; font-size: 14px; margin: 4px 0;">Stories from your recommendation
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0;"></td>
                    </tr>
                    <tr>
                        <td>
                            <h2 style="color: #4a5568; font-size: 24px; font-weight: 600;">Weekly Tech Update</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 10px 0;">
                                <tr>
                                    <td width="50%" valign="top">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                            style="padding-right: 16px;">
                                            <tr>
                                                <td
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div style="display: flex; align-items: center;">
    <img src="https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp"
        alt="Profile Picture"
        style="border-radius: 50%; width: 30px; height: 30px; object-fit: cover;">
    <p style="color: #4a5568; font-weight: 500; font-size: 12px; margin-left: 6px;">
        Satria Ardiantha Uno
    </p>
</div>

                                                    <div
                                                        style="background-color: red; padding: 4px 6px; border-radius: 12px; font-size: 12px; color: white; display: inline-flex; align-items: center;">
                                                        Kategori
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a href="../../category/news-detail.php?="
                                                        style="text-decoration: none; color: inherit;">
                                                        <h3
                                                            style="font-size: 16px; font-weight: bold; color: #1a202c; margin: 8px 0;">
                                                            MAHASISWA PROGRAM STUDI TEKNIK KOMPUTER POLIJE DITERIMA MAGANG DI PERUSAHAAN APPEN
                                                        </h3>
                                                    </a>
                                                    <p style="color: #a0aec0; font-size: 12px; margin: 4px 0; 
                                                        display: -webkit-box; 
                                                        -webkit-line-clamp: 2; 
                                                        -webkit-box-orient: vertical; 
                                                        overflow: hidden; 
                                                        text-overflow: ellipsis;">
                                                        Artikel ini membahas perkembangan terbaru dalam teknologi. Artikel ini membahas perkembangan terbaru dalam teknologi. Artikel ini membahas perkembangan terbaru dalam teknologi. Artikel ini membahas perkembangan terbaru dalam teknologi. Artikel ini membahas perkembangan terbaru dalam teknologi.
                                                    </p>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin-top: 16px;">
                                                    <img src="https://icons.veryicon.com/png/o/miscellaneous/yuanql/icon-like.png"
                                                        alt="Likes" style="width: 20px; height: 20px;">
                                                    <span
                                                        style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">32</span>
                                                    <img src="https://img.icons8.com/ios_filled/200/speech-bubble.png"
                                                        alt="Comments"
                                                        style="width: 20px; height: 20px; margin-left: 16px;">
                                                    <span
                                                        style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">45</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" align="center">
                                        <a href="../../category/news-detail.php?=">
                                            <img src="<?php echo htmlspecialchars($imageData[0]); ?>" alt="Post Image"
                                                style="width: 80%; max-height: 150px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p style="color: #a0aec0; font-size: 12px; margin-top: 24px;">Artikel ini membahas
                                perkembangan terbaru dalam teknologi.</p>
                        </td>
                    </tr>
                </table>
                <table width="600" border="0" cellspacing="0" cellpadding="0"
                    style="background-color: #101827; padding: 20px;">
                    <tr>
                        <td align="center" style="color: #ffffff; font-size: 16px; margin-bottom: 16px;">Baca Dimana
                            saja.</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <img src="https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png?hl=id"
                                alt="Play Store Badge" style="height: 50px; width: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 16px 0;"></td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #a0aec0; font-size: 12px; margin-bottom: 20px;">
                            © 2024 KabarE. All Rights Reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>