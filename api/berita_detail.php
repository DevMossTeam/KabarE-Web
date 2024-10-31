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
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    exit;
}

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_berita_detail($_GET['id']);
        } elseif (isset($_GET['berita_id'])) {
            fetch_berita_details_by_berita_id($_GET['berita_id']);
        } else {
            fetch_berita_details();
        }
        break;
    case 'POST':
        insert_berita_detail();
        break;
    case 'PUT':
        update_berita_detail();
        break;
    case 'DELETE':
        delete_berita_detail();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all berita_detail
function fetch_berita_details() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita_detail");
        $stmt->execute();
        $berita_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $berita_details, 'message' => 'Berita detail fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single berita_detail by ID
function fetch_berita_detail($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita_detail WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $berita_detail = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($berita_detail) {
            echo json_encode(['data' => $berita_detail, 'message' => 'Berita detail fetched successfully']);
        } else {
            echo json_encode(['message' => 'Berita detail not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch all berita_detail for a specific berita_id
function fetch_berita_details_by_berita_id($berita_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita_detail WHERE berita_id = :berita_id");
        $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_INT);
        $stmt->execute();
        $berita_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $berita_details, 'message' => 'Berita details fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new berita_detail
function insert_berita_detail() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['berita_id'], $data['content_section'], $data['section_image'], $data['section_image_name'], $data['section_order'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO berita_detail (berita_id, content_section, section_image, section_image_name, section_order) 
                                    VALUES (:berita_id, :content_section, :section_image, :section_image_name, :section_order)");
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':content_section', $data['content_section']);
            $stmt->bindParam(':section_image', $data['section_image'], PDO::PARAM_LOB);
            $stmt->bindParam(':section_image_name', $data['section_image_name']);
            $stmt->bindParam(':section_order', $data['section_order'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_berita_detail($last_id); // Return the inserted berita_detail data
            } else {
                echo json_encode(['message' => 'Failed to insert berita_detail']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing berita_detail
function update_berita_detail() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'], $data['berita_id'], $data['content_section'], $data['section_image'], $data['section_image_name'], $data['section_order'])) {
        try {
            $stmt = $conn->prepare("UPDATE berita_detail SET berita_id = :berita_id, content_section = :content_section, 
                                    section_image = :section_image, section_image_name = :section_image_name, section_order = :section_order 
                                    WHERE id = :id");
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':content_section', $data['content_section']);
            $stmt->bindParam(':section_image', $data['section_image'], PDO::PARAM_LOB);
            $stmt->bindParam(':section_image_name', $data['section_image_name']);
            $stmt->bindParam(':section_order', $data['section_order'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_berita_detail($data['id']); // Return the updated berita_detail data
            } else {
                echo json_encode(['message' => 'Failed to update berita_detail']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a berita_detail by ID
function delete_berita_detail() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM berita_detail WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Berita detail deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete berita_detail']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
