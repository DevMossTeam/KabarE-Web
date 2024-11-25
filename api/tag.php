<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

header("Content-Type: application/json");

class TagAPI {
    private $conn;

    public function __construct($server, $db, $username, $password) {
        try {
            $this->conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
            exit;
        }
    }

    // Fetch all tags
    public function fetchTags() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tag");
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $tags, 'message' => 'Tags fetched successfully']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Fetch a single tag by ID
    public function fetchTag($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tag WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $tag = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($tag) {
                echo json_encode(['data' => $tag, 'message' => 'Tag fetched successfully']);
            } else {
                echo json_encode(['message' => 'Tag not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Insert a new tag
    public function insertTag($data) {
        if (isset($data['nama_tag'])) {
            try {
                $stmt = $this->conn->prepare("INSERT INTO tag (nama_tag) VALUES (:nama_tag)");
                $stmt->bindParam(':nama_tag', $data['nama_tag']);
                if ($stmt->execute()) {
                    $last_id = $this->conn->lastInsertId(); // Get the last inserted ID
                    $this->fetchTag($last_id); // Return the inserted tag data
                } else {
                    echo json_encode(['message' => 'Failed to insert tag']);
                }
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    // Update an existing tag
    public function updateTag($id, $data) {
        if (isset($data['nama_tag'])) {
            try {
                $stmt = $this->conn->prepare("UPDATE tag SET nama_tag = :nama_tag WHERE id = :id");
                $stmt->bindParam(':nama_tag', $data['nama_tag']);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $this->fetchTag($id); // Return the updated tag data
                } else {
                    echo json_encode(['message' => 'Failed to update tag']);
                }
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    // Delete a tag by ID
    public function deleteTagByName($name) {
        try {
            // Use PDO to prepare and execute the query
            $stmt = $this->conn->prepare("SELECT * FROM tag WHERE nama_tag = :name");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (!empty($tags)) {
                $delete_stmt = $this->conn->prepare("DELETE FROM tag WHERE nama_tag = :name");
                $delete_stmt->bindParam(':name', $name);
                if ($delete_stmt->execute()) {
                    echo json_encode(['message' => 'Tags with the name "' . $name . '" deleted successfully']);
                } else {
                    echo json_encode(['message' => 'Failed to delete tags']);
                }
            } else {
                echo json_encode(['message' => 'No tags found with the name "' . $name . '"']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }    
}

// Instantiate the class and handle the request
$tagAPI = new TagAPI($server, $db, $username, $password);

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $tagAPI->fetchTag($_GET['id']);
        } else {
            $tagAPI->fetchTags();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $tagAPI->insertTag($data);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $tagAPI->updateTag($query['id'], $data);
        } else {
            echo json_encode(['message' => 'ID is required for updating']);
        }
        break;
        case 'DELETE':
            if (isset($_GET['name'])) {
                $tagAPI->deleteTagByName($_GET['name']); // Corrected variable name
            } else {
                echo json_encode(['message' => 'Name is required']);
            }
            break;
        
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}
?>
