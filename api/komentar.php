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
            fetch_komentar($_GET['id']);
        } else {
            fetch_komentars();
        }
        break;
    case 'POST':
        insert_komentar();
        break;
    case 'PUT':
        update_komentar();
        break;
    case 'DELETE':
        delete_komentar();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all komentars
function fetch_komentars() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM komentar");
        $stmt->execute();
        $komentars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $komentars, 'message' => 'Komentars fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function fetch_komentar($id) {
    global $conn;
    try {
        // Make sure the ID is not empty
        if (empty($id)) {
            echo json_encode(['message' => 'ID is required']);
            return;
        }

        // Prepare the SQL query with the correct ID parameter
        $stmt = $conn->prepare("SELECT * FROM komentar WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Using PDO::PARAM_STR for the id if it's a string

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $komentar = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a record was found
        if ($komentar) {
            echo json_encode(['data' => $komentar, 'message' => 'Komentar fetched successfully']);
        } else {
            echo json_encode(['message' => 'Komentar not found']);
        }
    } catch (PDOException $e) {
        // In case of an error, show the error message
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new komentar
function insert_komentar() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['user_id'], $data['berita_id'], $data['teks_komentar'], $data['tanggal_komentar'], $data['parent_komentar'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO komentar (user_id, berita_id, teks_komentar, tanggal_komentar, parent_komentar) 
                                    VALUES (:user_id, :berita_id, :teks_komentar, :tanggal_komentar, :parent_komentar)");
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':teks_komentar', $data['teks_komentar'], PDO::PARAM_STR);
            $stmt->bindParam(':tanggal_komentar', $data['tanggal_komentar']);
            $stmt->bindParam(':parent_komentar', $data['parent_komentar'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_komentar($last_id); // Return the inserted komentar data
            } else {
                echo json_encode(['message' => 'Failed to insert komentar']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing komentar
function update_komentar() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'], $data['user_id'], $data['berita_id'], $data['teks_komentar'], $data['tanggal_komentar'], $data['parent_komentar'])) {
        try {
            $stmt = $conn->prepare("UPDATE komentar SET user_id = :user_id, berita_id = :berita_id, teks_komentar = :teks_komentar, 
                                    tanggal_komentar = :tanggal_komentar, parent_komentar = :parent_komentar WHERE id = :id");
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
            $stmt->bindParam(':berita_id', $data['berita_id'], PDO::PARAM_INT);
            $stmt->bindParam(':teks_komentar', $data['teks_komentar'], PDO::PARAM_STR);
            $stmt->bindParam(':tanggal_komentar', $data['tanggal_komentar']);
            $stmt->bindParam(':parent_komentar', $data['parent_komentar'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_komentar($data['id']); // Return the updated komentar data
            } else {
                echo json_encode(['message' => 'Failed to update komentar']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a komentar by ID
// Delete a komentar by ID
function delete_komentar() {
    global $conn;
    // Ensure the 'id' is passed and is valid from the URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];  // Get the 'id' from the URL query parameter
        try {
            $stmt = $conn->prepare("DELETE FROM komentar WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);  // Use PDO::PARAM_STR if id is a string

            // Execute the statement and check if any row was deleted
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Komentar deleted successfully']);
            } else {
                echo json_encode(['message' => 'Komentar not found or could not be deleted']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input: Missing or empty ID']);
    }
}
?>
