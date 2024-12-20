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
    
            if ($result && mysqli_num_rows($result) > 0) {
                $pesan = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    // Check if profile_pic is not null and convert it to base64
                    if ($row['profile_pic']) {
                        $row['profile_pic'] = base64_encode($row['profile_pic']);
                    }
                    $pesan[] = $row;
                }
    
                echo json_encode(['data' => $pesan, 'message' => 'Pesan fetched successfully']);
            } else {
                echo json_encode(['data' => [], 'message' => 'No pesan found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }    
        
    // Fetch pesan by alphanumeric ID
    public function fetchPesanById($id) {
        try {
            if (empty($id)) {
                echo json_encode(['error' => 'Invalid ID parameter']);
                return;
            }

            $query = "SELECT pesan.*, user.nama_pengguna, user.email, user.profile_pic 
                    FROM pesan 
                    JOIN user ON pesan.user_id = user.uid 
                    WHERE pesan.id = ?";
            
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }

            mysqli_stmt_bind_param($stmt, 's', $id); // 's' for string binding
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (!$result) {
                throw new Exception('Query execution failed');
            }

            $pesan = mysqli_fetch_assoc($result);

            if ($pesan) {
                // Check if profile_pic is a BLOB and convert it to base64
                if ($pesan['profile_pic']) {
                    $pesan['profile_pic'] = base64_encode($pesan['profile_pic']);
                }

                echo json_encode(['data' => $pesan, 'message' => 'Pesan fetched successfully']);
            } else {
                echo json_encode(['message' => 'Pesan not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Update pesan by alphanumeric ID
    public function updatePesan($id, $data) {
        if (empty($id)) {
            echo json_encode(['message' => 'Invalid ID']);
            return;
        }

        $set_clause = [];
        $params = [];
        $types = '';

        foreach ($data as $key => $value) {
            $set_clause[] = "$key = ?";
            $params[] = $value;
            $types .= 's'; 
        }

        $set_clause_str = implode(', ', $set_clause);

        $query = "UPDATE pesan SET $set_clause_str WHERE id = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            echo json_encode(['message' => 'Failed to prepare statement']);
            return;
        }

        $params[] = $id; 
        $types .= 's';  // Use 's' for string binding since ID is alphanumeric

        mysqli_stmt_bind_param($stmt, $types, ...$params);

        try {
            if (mysqli_stmt_execute($stmt)) {
                $this->fetchPesanById($id); 
            } else {
                echo json_encode(['message' => 'Failed to update pesan']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Delete pesan by alphanumeric ID
    public function deletePesan($id) {
        try {
            if (empty($id)) {
                echo json_encode(['message' => 'Invalid ID']);
                return;
            }

            // Check if the pesan exists
            $query = "SELECT * FROM pesan WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $id); // 's' for string binding
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $pesan = mysqli_fetch_assoc($result);

            if ($pesan) {
                $delete_query = "DELETE FROM pesan WHERE id = ?";
                $delete_stmt = mysqli_prepare($this->conn, $delete_query);
                mysqli_stmt_bind_param($delete_stmt, 's', $id); // 's' for string binding
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
