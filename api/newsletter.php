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
            $query = "SELECT id, judul_bulletin, status, tipe_content, kategori, footer_content, hari_pengiriman, jam_pengiriman, jumlah_berita FROM newsletter";
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
            if (empty($id) || !preg_match('/^[A-Za-z0-9]+$/', $id)) {  // Changed from is_numeric to regex check
                echo json_encode(['error' => 'Invalid ID parameter']);
                return;
            }
    
            $query = "SELECT * FROM newsletter WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }
    
            mysqli_stmt_bind_param($stmt, 's', $id); // id is a string (varchar)
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
            // Generate a random ID for the newsletter
            $id = $this->generateRandomId();
    
            $query = "INSERT INTO newsletter (id, judul_bulletin, status, tipe_content, kategori, footer_content, hari_pengiriman, jam_pengiriman, jumlah_berita) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }
    
            $params = [
                $id, // Insert the generated ID
                $data['judul_bulletin'] ?? null,
                $data['status'] ?? null,
                $data['tipe_content'] ?? null,
                $data['kategori'] ?? null,
                $data['footer_content'] ?? null,
                $data['hari_pengiriman'] ?? null,
                $data['jam_pengiriman'] ?? null,
                $data['jumlah_berita'] ?? null
            ];
    
            // Bind parameters dynamically based on data
            $types = 'sssssssss'; // Adjust types for each field accordingly
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
    
    // Method to generate a random ID (alphanumeric)
    private function generateRandomId($length = 8) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomId = '';
        for ($i = 0; $i < $length; $i++) {
            $randomId .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomId;
    }
    
    // Update a newsletter
    public function updateNewsletter($id, $data) {
        // Validate ID (ensure it's a non-empty alphanumeric string)
        if (empty($id) || !preg_match('/^[A-Za-z0-9]+$/', $id)) {  // Changed to regex check for alphanumeric IDs
            echo json_encode(['message' => 'Invalid ID']);
            return;
        }
    
        $set_clause = [];
        $params = [];
        $types = '';
    
        // Prepare dynamic SET clause based on provided data
        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                $set_clause[] = "$key = ?";
                $params[] = $value;
                $types .= 's'; // Assuming all fields are strings, adjust if necessary
            }
        }
    
        // Join SET clauses for the SQL query
        $set_clause_str = implode(', ', $set_clause);
    
        // Prepare the SQL query
        $query = "UPDATE newsletter SET $set_clause_str WHERE id = ?";
    
        $stmt = mysqli_prepare($this->conn, $query);
        if (!$stmt) {
            echo json_encode(['message' => 'Failed to prepare statement']);
            return;
        }
    
        // Add ID to parameters and adjust types to include the ID as a string
        $params[] = $id;
        $types .= 's';  // Add ID as a string parameter
    
        // Bind parameters dynamically
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    
        try {
            // Execute the update query
            if (mysqli_stmt_execute($stmt)) {
                $this->fetchNewsletterById($id); // Fetch updated newsletter details
            } else {
                echo json_encode(['message' => 'Failed to update newsletter']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }    

    // Delete a newsletter
    public function deleteNewsletter($id) {
        try {
            // Validate ID using regex for alphanumeric format
            if (empty($id) || !preg_match('/^[A-Za-z0-9]+$/', $id)) {
                echo json_encode(['error' => 'Invalid ID parameter']);
                return;
            }
    
            $query = "DELETE FROM newsletter WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception('Statement preparation failed');
            }
    
            mysqli_stmt_bind_param($stmt, 's', $id); // id is a string (varchar)
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['message' => 'Newsletter deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete newsletter']);
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