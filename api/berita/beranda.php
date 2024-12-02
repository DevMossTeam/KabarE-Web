<?php
header('Content-Type: application/json');
include '../../connection/config.php';

function timeAgo($datetime) {
    $now = new DateTime();
    $posted = new DateTime($datetime);
    $interval = $now->diff($posted);

    if ($interval->y > 0) return $interval->y . " tahun yang lalu";
    if ($interval->m > 0) return $interval->m . " bulan yang lalu";
    if ($interval->d > 0) return $interval->d . " hari yang lalu";
    if ($interval->h > 0) return $interval->h . " jam yang lalu";
    if ($interval->i > 0) return $interval->i . " menit yang lalu";
    return "baru saja";
}

try {
    // Data untuk slider
    $querySlider = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                    FROM berita 
                    WHERE visibilitas = 'public' 
                    ORDER BY RAND() 
                    LIMIT 7";
    $resultSlider = $conn->query($querySlider);
    $sliderData = [];
    
    if ($resultSlider) {
        while ($row = $resultSlider->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $sliderData[] = $row;
        }
    }

    // Data berita terkini
    $queryTerkini = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                     FROM berita 
                     WHERE visibilitas = 'public' 
                     ORDER BY tanggal_diterbitkan DESC 
                     LIMIT 6";
    $resultTerkini = $conn->query($queryTerkini);
    $beritaTerkini = [];
    
    if ($resultTerkini) {
        while ($row = $resultTerkini->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $beritaTerkini[] = $row;
        }
    }

    // Data berita populer
    $queryPopuler = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                     FROM berita 
                     WHERE visibilitas = 'public' 
                     ORDER BY RAND() 
                     LIMIT 3";
    $resultPopuler = $conn->query($queryPopuler);
    $beritaPopuler = [];
    
    if ($resultPopuler) {
        while ($row = $resultPopuler->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $beritaPopuler[] = $row;
        }
    }

    // Data berita baru
    $queryBaru = "SELECT id, judul, tanggal_diterbitkan 
                  FROM berita 
                  WHERE visibilitas = 'public' 
                  ORDER BY tanggal_diterbitkan DESC 
                  LIMIT 6";
    $resultBaru = $conn->query($queryBaru);
    $beritaBaru = [];
    
    if ($resultBaru) {
        while ($row = $resultBaru->fetch_assoc()) {
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $beritaBaru[] = $row;
        }
    }

    // Query untuk news cards (3 berita di sebelah kiri slider)
    $queryNewsCards = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                      FROM berita 
                      WHERE visibilitas = 'public' 
                      ORDER BY RAND() 
                      LIMIT 3";
    $resultNewsCards = $conn->query($queryNewsCards);
    $newsCardsData = [];
    
    if ($resultNewsCards) {
        while ($row = $resultNewsCards->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $newsCardsData[] = $row;
        }
    }

    // Daftar kategori yang tersedia
    $categories = ['UKM', 'Prestasi', 'Politik', 'Olahraga', 'Kesehatan', 'Kampus', 'Ekonomi', 'Bisnis', 'Berita Lainnya'];

    // Pilih kategori acak
    $randomCategory = $categories[array_rand($categories)];

    // Query untuk berita utama dari kategori acak
    $queryRandomCategory = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                           FROM berita 
                           WHERE kategori = '$randomCategory' 
                           AND visibilitas = 'public' 
                           ORDER BY RAND() 
                           LIMIT 10";
    $resultRandomCategory = $conn->query($queryRandomCategory);
    $randomCategoryData = [];

    if ($resultRandomCategory) {
        while ($row = $resultRandomCategory->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $randomCategoryData[] = $row;
        }
    }

    // Query untuk 2 berita random tambahan dari kategori acak lainnya
    $randomCategory2 = $categories[array_rand($categories)];
    while ($randomCategory2 == $randomCategory) {
        $randomCategory2 = $categories[array_rand($categories)];
    }

    $queryRandomExtra = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                         FROM berita 
                         WHERE kategori = '$randomCategory2' 
                         AND visibilitas = 'public' 
                         ORDER BY RAND() 
                         LIMIT 2";
    $resultRandomExtra = $conn->query($queryRandomExtra);
    $randomExtraData = [];

    if ($resultRandomExtra) {
        while ($row = $resultRandomExtra->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $randomExtraData[] = $row;
        }
    }

    // Query untuk Berita Lainnya (4 berita acak dari semua kategori)
    $queryBeritaLainnya = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                           FROM berita 
                           WHERE visibilitas = 'public' 
                           ORDER BY RAND() 
                           LIMIT 4";
    $resultBeritaLainnya = $conn->query($queryBeritaLainnya);
    $beritaLainnyaData = [];

    if ($resultBeritaLainnya) {
        while ($row = $resultBeritaLainnya->fetch_assoc()) {
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }
            $row['firstImage'] = $firstImage;
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $beritaLainnyaData[] = $row;
        }
    }

    // Query untuk Baru Baru Ini (8 berita terbaru dari kategori yang sama)
    $queryBaruBaruIni = "SELECT id, judul, tanggal_diterbitkan 
                         FROM berita 
                         WHERE kategori = '$randomCategory' 
                         AND visibilitas = 'public' 
                         ORDER BY tanggal_diterbitkan DESC 
                         LIMIT 8";
    $resultBaruBaruIni = $conn->query($queryBaruBaruIni);
    $baruBaruIniData = [];

    if ($resultBaruBaruIni) {
        while ($row = $resultBaruBaruIni->fetch_assoc()) {
            $row['timeAgo'] = timeAgo($row['tanggal_diterbitkan']);
            $baruBaruIniData[] = $row;
        }
    }

    $response = [
        'status' => 'success',
        'data' => [
            'newsCards' => $newsCardsData,
            'slider' => $sliderData,
            'beritaTerkini' => $beritaTerkini,
            'beritaPopuler' => $beritaPopuler,
            'beritaBaru' => $beritaBaru,
            'randomCategory' => $randomCategoryData,
            'randomExtra' => $randomExtraData,
            'beritaLainnya' => $beritaLainnyaData,
            'baruBaruIni' => $baruBaruIniData
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}