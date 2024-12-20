<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

header("Content-Type: application/json");

class NewsletterAPI {
    private $conn;

    public function __construct() {
        // Use the global mysqli connection
        $this->conn = getKoneksi();
    }

    // Fetch all newsletters
    public function fetchNewsletters() {
        try {
            $query = "SELECT * FROM newsletter";
            $result = mysqli_query($this->conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $newsletters = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $newsletters[] = $row;
                }

                echo json_encode(['data' => $newsletters, 'message' => 'Newsletters fetched successfully']);
            } else {
                echo json_encode(['data' => [], 'message' => 'No newsletters found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Fetch a newsletter by ID
    public function fetchNewsletterById($id) {
        try {
            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['error' => 'Invalid ID parameter']);
                return;
            }

            $query = "SELECT * FROM newsletter WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }

            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (!$result) {
                throw new Exception('Query execution failed');
            }

            $newsletter = mysqli_fetch_assoc($result);

            if ($newsletter) {
                echo json_encode(['data' => $newsletter, 'message' => 'Newsletter fetched successfully']);
            } else {
                echo json_encode(['message' => 'Newsletter not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Insert a new newsletter
    public function insertNewsletter($data) {
        try {
            $query = "INSERT INTO newsletter (send_date, preview_url, status, judul_bulletin, deskripsi, tipe_content, kategori, footer_content) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }

            $params = [
                $data['send_date'] ?? null,
                $data['preview_url'] ?? null,
                $data['status'] ?? null,
                $data['judul_bulletin'] ?? null,
                $data['deskripsi'] ?? null,
                $data['tipe_content'] ?? null,
                $data['kategori'] ?? null,
                $data['footer_content'] ?? null,                
            ];

            // Bind parameters dynamically based on data
            $types = str_repeat('s', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['message' => 'Newsletter inserted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to insert newsletter']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Update a newsletter
    public function updateNewsletter($id, $data) {
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['message' => 'Invalid ID']);
            return;
        }

        $set_clause = [];
        $params = [];
        $types = '';

        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                $set_clause[] = "$key = ?";
                $params[] = $value;
                $types .= 's'; // Assuming all are strings, adjust if necessary
            }
        }

        $set_clause_str = implode(', ', $set_clause);

        $query = "UPDATE newsletter SET $set_clause_str WHERE id = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            echo json_encode(['message' => 'Failed to prepare statement']);
            return;
        }

        $params[] = $id; 
        $types .= 'i'; // Add ID as an integer parameter

        mysqli_stmt_bind_param($stmt, $types, ...$params);

        try {
            if (mysqli_stmt_execute($stmt)) {
                $this->fetchNewsletterById($id); // Fetch updated newsletter
            } else {
                echo json_encode(['message' => 'Failed to update newsletter']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Delete a newsletter by ID
    public function deleteNewsletter($id) {
        try {
            $query = "SELECT * FROM newsletter WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $newsletter = mysqli_fetch_assoc($result);

            if ($newsletter) {
                $delete_query = "DELETE FROM newsletter WHERE id = ?";
                $delete_stmt = mysqli_prepare($this->conn, $delete_query);
                mysqli_stmt_bind_param($delete_stmt, 'i', $id);
                if (mysqli_stmt_execute($delete_stmt)) {
                    echo json_encode(['message' => 'Newsletter deleted successfully']);
                } else {
                    echo json_encode(['message' => 'Failed to delete newsletter']);
                }
            } else {
                echo json_encode(['message' => 'Newsletter not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Instantiate the class and handle the request
$newsletterAPI = new NewsletterAPI();

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $newsletterAPI->fetchNewsletterById($_GET['id']);
        } else {
            $newsletterAPI->fetchNewsletters();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $newsletterAPI->insertNewsletter($data);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $newsletterAPI->updateNewsletter($query['id'], $data);
        } else {
            echo json_encode(['message' => 'ID is required for updating']);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $newsletterAPI->deleteNewsletter($_GET['id']);
        } else {
            echo json_encode(['message' => 'ID is required']);
        }
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}
?>
