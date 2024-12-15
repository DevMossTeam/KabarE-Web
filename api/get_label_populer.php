<?php
require_once 'config.php'; 

header("Content-Type: application/json");
function getPopularTags($conn) {
    try {
        // Query untuk menghitung dan mendapatkan tag terpopuler
        $query = "SELECT nama_tag, COUNT(nama_tag) AS jumlah_tag 
                  FROM tag 
                  GROUP BY nama_tag 
                  ORDER BY jumlah_tag DESC
                  LIMIT 6";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // Mengecek jika hasil ditemukan
        if($stmt->rowCount() > 0) {
            $tags = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tags[] = [
                    'nama_tag' => $row['nama_tag'],
                    'jumlah_tag' => $row['jumlah_tag']
                ];
            }
            
            // Respon berhasil dengan data tag
            return [
                'status' => 'success',
                'message' => 'Data label terpopuler berhasil diambil',
                'result' => $tags
            ];
        } else {
            // Jika tidak ada data tag ditemukan
            return [
                'status' => 'success',
                'message' => 'Tidak ada label terpopuler ditemukan',
                'result' => []
            ];
        }
    } catch (Exception $e) {
        // Jika terjadi error pada proses
        return [
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ];
    }
}

// Memanggil fungsi dan mengirimkan hasilnya sebagai JSON
echo json_encode(getPopularTags($conn));
?>
