    <?php
    require_once 'config.php'; // Memuat file koneksi database

    // Fungsi untuk mengembalikan respons JSON
    function response($data, $status = 200) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // Fungsi untuk generate ID reaksi otomatis
    function generateReaksiId($length = 12) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    // Mendapatkan metode HTTP 
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST') {
        // Tambah atau toggle reaksi
        $data = json_decode(file_get_contents("php://input"), true);

        // Pastikan data JSON valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            response(['status' => 'error', 'message' => 'Data JSON tidak valid.'], 400);
        }

        // Pastikan semua data yang dibutuhkan ada
        if (isset($data['user_id'], $data['berita_id'], $data['aksi'])) {
            $user_id = $data['user_id'];
            $berita_id = $data['berita_id'];
            $aksi = $data['aksi']; // Nilai: 'Suka' atau 'Tidak Suka'

            // Validasi aksi
            $valid_actions = ['Suka', 'Tidak Suka'];
            if (!in_array($aksi, $valid_actions)) {
                response([
                    'status' => 'error',
                    'message' => 'Aksi tidak valid. Gunakan "Suka" atau "Tidak Suka".'
                ], 400);
            }

            $reaksi_id = generateReaksiId();

            // Cek apakah ada reaksi yang sesuai untuk user_id dan berita_id
            $checkSql = "SELECT * FROM reaksi WHERE user_id = :user_id AND berita_id = :berita_id";
            $stmt = $conn->prepare($checkSql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':berita_id', $berita_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $reaksi = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($reaksi['jenis_reaksi'] === $aksi) {
                    // Jika reaksi yang sama sudah ada, hapus reaksi
                    $deleteSql = "DELETE FROM reaksi WHERE user_id = :user_id AND berita_id = :berita_id";
                    $stmt = $conn->prepare($deleteSql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':berita_id', $berita_id);

                    if ($stmt->execute()) {
                        response([
                            'status' => 'success',
                            'message' => 'Reaksi berhasil dihapus.'
                        ]);
                    } else {
                        response([
                            'status' => 'error',
                            'message' => 'Gagal menghapus reaksi.'
                        ], 500);
                    }
                } else {
                    // Jika reaksi berbeda, update reaksi
                    $updateSql = "UPDATE reaksi SET jenis_reaksi = :jenis_reaksi, tanggal_reaksi = NOW() WHERE user_id = :user_id AND berita_id = :berita_id";
                    $stmt = $conn->prepare($updateSql);
                    $stmt->bindParam(':jenis_reaksi', $aksi);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':berita_id', $berita_id);

                    if ($stmt->execute()) {
                        response([
                            'status' => 'success',
                            'message' => 'Reaksi berhasil diperbarui.',
                            'result' => [
                                'user_id' => $user_id,
                                'berita_id' => $berita_id,
                                'jenis_reaksi' => $aksi
                            ]
                        ]);
                    } else {
                        response([
                            'status' => 'error',
                            'message' => 'Gagal memperbarui reaksi.'
                        ], 500);
                    }
                }
            } else {
                // Jika belum ada reaksi, tambahkan reaksi baru
                $insertSql = "INSERT INTO reaksi (id, user_id, berita_id, jenis_reaksi, tanggal_reaksi) VALUES (:id, :user_id, :berita_id, :jenis_reaksi, NOW())";
                $stmt = $conn->prepare($insertSql);
                $stmt->bindParam(':id', $reaksi_id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':berita_id', $berita_id);
                $stmt->bindParam(':jenis_reaksi', $aksi);

                if ($stmt->execute()) {
                    response([
                        'status' => 'success',
                        'message' => 'Reaksi berhasil ditambahkan.',
                        'result' => [
                            'user_id' => $user_id,
                            'berita_id' => $berita_id,
                            'jenis_reaksi' => $aksi
                        ]
                    ]);
                } else {
                    response([
                        'status' => 'error',
                        'message' => 'Gagal menambahkan reaksi.'
                    ], 500);
                }
            }
        } else {
            response([
                'status' => 'error',
                'message' => 'User ID, Berita ID, dan Aksi harus disertakan.'
            ], 400);
        }
    } elseif ($method == 'GET') {
        // Mendapatkan jumlah suka dan tidak suka untuk berita
        if (isset($_GET['berita_id'])) {
            $berita_id = $_GET['berita_id'];

            $query = "
            SELECT 
                (SELECT COUNT(*) FROM reaksi WHERE jenis_reaksi = 'Suka' AND berita_id = :berita_id) AS jumlah_suka,
                (SELECT COUNT(*) FROM reaksi WHERE jenis_reaksi = 'Tidak Suka' AND berita_id = :berita_id) AS jumlah_tidak_suka
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':berita_id', $berita_id);

            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                response([
                    'status' => 'success',
                    'message' => 'Jumlah reaksi berhasil diambil.',
                    'result' => $result
                ]);
            } else {
                response([
                    'status' => 'error',
                    'message' => 'Gagal mengambil jumlah reaksi.'
                ], 500);
            }
        } else {
            response([
                'status' => 'error',
                'message' => 'Berita ID harus disertakan.'
            ], 400);
        }
    } else {
        response([
            'status' => 'error',
            'message' => 'Metode HTTP tidak valid.'
        ], 405);
    }
    ?>
