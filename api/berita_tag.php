<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'koneksi.php'; 

header("Content-Type: application/json");

try {
    $conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    exit;
}

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_berita_tag($_GET['id']);
        } else {
            fetch_berita_tags();
        }
        break;
    case 'POST':
        insert_berita_tag();
        break;
    case 'PUT':
        update_berita_tag();
        break;
    case 'DELETE':
        delete_berita_tag();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all berita_tags
function fetch_berita_tags() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita_tag");
        $stmt->execute();
        $berita_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $berita_tags, 'message' => 'Berita Tags fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single berita_tag by ID
function fetch_berita_tag($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita_tag WHERE berita_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $berita_tag = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($berita_tag) {
            echo json_encode(['data' => $berita_tag, 'message' => 'Berita Tag fetched successfully']);
        } else {
            echo json_encode(['message' => 'Berita Tag not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new berita_tag
function insert_berita_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['berita_id'], $data['tag_id'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO berita_tag (berita_id, tag_id) VALUES (:berita_id, :tag_id)");
            $stmt->bindParam(':berita_id', $data['berita_id']);
            $stmt->bindParam(':tag_id', $data['tag_id']);
            
            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_berita_tag($last_id); // Return the inserted berita_tag data
            } else {
                echo json_encode(['message' => 'Failed to insert berita_tag']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing berita_tag
function update_berita_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['berita_id'], $data['tag_id'])) {
        try {
            $stmt = $conn->prepare("UPDATE berita_tag SET tag_id = :tag_id WHERE berita_id = :berita_id");
            $stmt->bindParam(':tag_id', $data['tag_id']);
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_berita_tag($data['berita_id']); // Return the updated berita_tag data
            } else {
                echo json_encode(['message' => 'Failed to update berita_tag']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a berita_tag by ID
function delete_berita_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['berita_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM berita_tag WHERE berita_id = :berita_id");
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Berita Tag deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete berita_tag']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
