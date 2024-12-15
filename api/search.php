<?php
// Menghubungkan ke database
include 'config.php';

if (isset($_GET['katakunci']) || isset($_GET['kategori']) || isset($_GET['tag'])) {
    $katakunci = isset($_GET['katakunci']) ? htmlspecialchars($_GET['katakunci']) : '';
    $kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : '';
    $tag = isset($_GET['tag']) ? htmlspecialchars($_GET['tag']) : '';

    $query = "
        SELECT b.id, b.judul, b.kategori, b.tanggal_diterbitkan, b.konten_artikel
        FROM berita b
        LEFT JOIN tag t ON b.id = t.berita_id
        WHERE (b.judul LIKE :katakunci OR t.nama_tag LIKE :katakunci OR :katakunci = '')
        AND (b.kategori = :kategori OR :kategori = '')
        AND (t.nama_tag = :tag OR :tag = '')    
        GROUP BY b.id
        ORDER BY b.tanggal_diterbitkan DESC "
        ;

    $stmt = $conn->prepare($query);

    $katakunci_search = '%' . $katakunci . '%';
    $stmt->bindParam(':katakunci', $katakunci_search, PDO::PARAM_STR);
    $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR);
    $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);

    try {
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $berita = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'message' => null, 'result' => $berita]);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Berita tidak ditemukan', 'result' => []]);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(), 'result' => null]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parameter pencarian tidak ditemukan', 'result' => null]);
}

?>
