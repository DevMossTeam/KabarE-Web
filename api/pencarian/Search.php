<?php
class Search {
    private $conn;
    private $limit = 30;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function searchNews($query, $page = 1) {
        $offset = ($page - 1) * $this->limit;
        $searchTerm = "%$query%";

        try {
            // Query pencarian
            $sql = "SELECT DISTINCT b.id, b.judul, b.konten_artikel, b.kategori, b.tanggal_diterbitkan
                    FROM berita b
                    LEFT JOIN tag t ON b.id = t.berita_id
                    WHERE (b.judul LIKE ? OR b.kategori LIKE ? OR t.nama_tag LIKE ?)
                    AND b.visibilitas = 'public'
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssii', $searchTerm, $searchTerm, $searchTerm, $this->limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Query total hasil
            $totalQuery = "SELECT COUNT(DISTINCT b.id) as total 
                          FROM berita b
                          LEFT JOIN tag t ON b.id = t.berita_id
                          WHERE (b.judul LIKE ? OR b.kategori LIKE ? OR t.nama_tag LIKE ?)
                          AND b.visibilitas = 'public'";
            $totalStmt = $this->conn->prepare($totalQuery);
            $totalStmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
            $totalStmt->execute();
            $totalResult = $totalStmt->get_result();
            $totalRow = $totalResult->fetch_assoc();

            // Format hasil
            $news = [];
            while ($row = $result->fetch_assoc()) {
                $firstImage = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                    $firstImage = $image['src'];
                }
                $description = strip_tags(html_entity_decode($row['konten_artikel']));
                $description = substr($description, 0, 100) . '...';

                $news[] = [
                    'id' => $row['id'],
                    'judul' => $row['judul'],
                    'gambar' => $firstImage ?: 'https://via.placeholder.com/400x300',
                    'deskripsi' => $description,
                    'tanggal' => $row['tanggal_diterbitkan'],
                    'kategori' => $row['kategori']
                ];
            }

            return [
                'status' => 'success',
                'total' => $totalRow['total'],
                'total_pages' => ceil($totalRow['total'] / $this->limit),
                'current_page' => $page,
                'data' => $news
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}