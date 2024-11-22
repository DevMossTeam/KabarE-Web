<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

header("Content-Type: application/json");

try {
    $conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_tag($_GET['id']);
        } else {
            fetch_tags();
        }
        break;
    case 'POST':
        insert_tag();
        break;
    case 'PUT':
        update_tag();
        break;
    case 'DELETE':
        delete_tag();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all tags
function fetch_tags() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM tag");
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $tags, 'message' => 'Tags fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single tag by ID
function fetch_tag($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM tag WHERE id = :id");
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
function insert_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_tag'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO tag (nama_tag) VALUES (:nama_tag)");
            $stmt->bindParam(':nama_tag', $data['nama_tag']);
            
            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_tag($last_id); // Return the inserted tag data
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
function update_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_tag'], $data['id'])) {
        try {
            $stmt = $conn->prepare("UPDATE tag SET nama_tag = :nama_tag WHERE id = :id");
            $stmt->bindParam(':nama_tag', $data['nama_tag']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_tag($data['id']); // Return the updated tag data
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
function delete_tag() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM tag WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Tag deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete tag']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
