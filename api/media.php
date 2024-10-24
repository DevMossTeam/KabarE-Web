<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; // Include your connection file here

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
            fetch_media($_GET['id']);
        } else {
            fetch_all_media();
        }
        break;
    case 'POST':
        insert_media();
        break;
    case 'PUT':
        update_media();
        break;
    case 'DELETE':
        delete_media();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all media records
function fetch_all_media() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM media");
        $stmt->execute();
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $media, 'message' => 'Media fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a specific media record by ID
function fetch_media($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM media WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($media) {
            echo json_encode(['data' => $media, 'message' => 'Media fetched successfully']);
        } else {
            echo json_encode(['message' => 'Media not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new media record
function insert_media() {
    global $conn;
    if (!empty($_FILES['media']['tmp_name']) && isset($_POST['berita_id'], $_POST['jenis_media'])) {
        try {
            // Reading the file content into a variable
            $media_data = file_get_contents($_FILES['media']['tmp_name']);
            $berita_id = $_POST['berita_id'];
            $jenis_media = $_POST['jenis_media'];
            
            $stmt = $conn->prepare("INSERT INTO media (berita_id, media, jenis_media) VALUES (:berita_id, :media, :jenis_media)");
            $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_INT);
            $stmt->bindParam(':media', $media_data, PDO::PARAM_LOB); // Bind media data as BLOB
            $stmt->bindParam(':jenis_media', $jenis_media, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Media uploaded successfully']);
            } else {
                echo json_encode(['message' => 'Failed to upload media']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input or file missing']);
    }
}

// Update an existing media record
function update_media() {
    global $conn;
    parse_str(file_get_contents("php://input"), $put_vars); // Parsing input from PUT request
    
    if (!empty($put_vars['id']) && isset($put_vars['berita_id'], $put_vars['jenis_media'])) {
        try {
            $id = $put_vars['id'];
            $berita_id = $put_vars['berita_id'];
            $jenis_media = $put_vars['jenis_media'];
            
            $media_data = null;
            if (!empty($_FILES['media']['tmp_name'])) {
                $media_data = file_get_contents($_FILES['media']['tmp_name']); // Optional file update
            }

            $query = "UPDATE media SET berita_id = :berita_id, jenis_media = :jenis_media";
            if ($media_data !== null) {
                $query .= ", media = :media"; // Only update media if new file is uploaded
            }
            $query .= " WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_INT);
            $stmt->bindParam(':jenis_media', $jenis_media, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($media_data !== null) {
                $stmt->bindParam(':media', $media_data, PDO::PARAM_LOB);
            }

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Media updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update media']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a media record by ID
function delete_media() {
    global $conn;
    parse_str(file_get_contents("php://input"), $delete_vars); // Parsing input from DELETE request

    if (!empty($delete_vars['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM media WHERE id = :id");
            $stmt->bindParam(':id', $delete_vars['id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Media deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete media']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
