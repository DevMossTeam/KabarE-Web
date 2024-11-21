<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

header("Content-Type: application/json");

class PesanAPI {
    private $conn;

    public function __construct() {
        // Use the global mysqli connection
        $this->conn = getKoneksi();
    }

    // Fetch all pesan
    public function fetchPesan() {
        try {
            $query = "SELECT pesan.*, user.nama_pengguna, user.email, user.profile_pic 
                      FROM pesan 
                      JOIN user ON pesan.user_id = user.uid";
            $result = mysqli_query($this->conn, $query);
            
            if ($result) {
                $pesan = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode(['data' => $pesan, 'message' => 'Pesan fetched successfully']);
            } else {
                echo json_encode(['error' => 'Failed to fetch pesan']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Fetch a single pesan by ID
    public function fetchPesanById($id) {
        try {
            $query = "SELECT pesan.*, user.nama_pengguna, user.email, user.profile_pic 
                      FROM pesan 
                      JOIN user ON pesan.user_id = user.uid 
                      WHERE pesan.id_utama = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $pesan = mysqli_fetch_assoc($result);
            
            if ($pesan) {
                echo json_encode(['data' => $pesan, 'message' => 'Pesan fetched successfully']);
            } else {
                echo json_encode(['message' => 'Pesan not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // Update an existing pesan
    public function updatePesan($id, $data) {
        if (isset($data['user_id'], $data['pesan'], $data['status_read'], $data['status'])) {
            try {
                $query = "UPDATE pesan 
                          SET user_id = ?, pesan = ?, status_read = ?, status = ?, berita_id = ?, komen_id = ? 
                          WHERE id_utama = ?";
                $stmt = mysqli_prepare($this->conn, $query);
                mysqli_stmt_bind_param(
                    $stmt, 
                    'sssssii', 
                    $data['user_id'], $data['pesan'], $data['status_read'], $data['status'], 
                    $data['berita_id'], $data['komen_id'], $id
                );
                if (mysqli_stmt_execute($stmt)) {
                    $this->fetchPesanById($id); // Return the updated pesan data
                } else {
                    echo json_encode(['message' => 'Failed to update pesan']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    // Delete a pesan by ID
    public function deletePesan($id) {
        try {
            $query = "SELECT * FROM pesan WHERE id_utama = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $pesan = mysqli_fetch_assoc($result);

            if ($pesan) {
                $delete_query = "DELETE FROM pesan WHERE id_utama = ?";
                $delete_stmt = mysqli_prepare($this->conn, $delete_query);
                mysqli_stmt_bind_param($delete_stmt, 'i', $id);
                if (mysqli_stmt_execute($delete_stmt)) {
                    echo json_encode(['message' => 'Pesan deleted successfully']);
                } else {
                    echo json_encode(['message' => 'Failed to delete pesan']);
                }
            } else {
                echo json_encode(['message' => 'Pesan not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Instantiate the class and handle the request
$pesanAPI = new PesanAPI();

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $pesanAPI->fetchPesanById($_GET['id']);
        } else {
            $pesanAPI->fetchPesan();
        }
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $pesanAPI->updatePesan($query['id'], $data);
        } else {
            echo json_encode(['message' => 'ID is required for updating']);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $pesanAPI->deletePesan($_GET['id']);
        } else {
            echo json_encode(['message' => 'ID is required']);
        }
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}
?>