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
            fetch_favorit($_GET['id']);
        } else {
            fetch_favorits();
        }
        break;
    case 'POST':
        insert_favorit();
        break;
    case 'PUT':
        update_favorit();
        break;
    case 'DELETE':
        delete_favorit();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all favorit
function fetch_favorits() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM favorit");
        $stmt->execute();
        $favorits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $favorits, 'message' => 'Favorit fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single favorit by ID
function fetch_favorit($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM favorit WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $favorit = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($favorit) {
            echo json_encode(['data' => $favorit, 'message' => 'Favorit fetched successfully']);
        } else {
            echo json_encode(['message' => 'Favorit not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new favorit
function insert_favorit() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['user_id'], $data['berita_id'], $data['tanggal_disukai'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO favorit (user_id, berita_id, tanggal_disukai) 
                                    VALUES (:user_id, :berita_id, :tanggal_disukai)");
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':tanggal_disukai', $data['tanggal_disukai']);

            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_favorit($last_id); // Return the inserted favorit data
            } else {
                echo json_encode(['message' => 'Failed to insert favorit']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing favorit
function update_favorit() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'], $data['user_id'], $data['berita_id'], $data['tanggal_disukai'])) {
        try {
            $stmt = $conn->prepare("UPDATE favorit SET user_id = :user_id, berita_id = :berita_id, 
                                    tanggal_disukai = :tanggal_disukai WHERE id = :id");
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':tanggal_disukai', $data['tanggal_disukai']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_favorit($data['id']); // Return the updated favorit data
            } else {
                echo json_encode(['message' => 'Failed to update favorit']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a favorit by ID
function delete_favorit() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM favorit WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Favorit deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete favorit']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
