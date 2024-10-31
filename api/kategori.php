<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_kategori($_GET['id']);
        } else {
            fetch_kategoris();
        }
        break;
    case 'POST':
        insert_kategori();
        break;
    case 'PUT':
        update_kategori();
        break;
    case 'DELETE':
        delete_kategori();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all categories
function fetch_kategoris() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM kategori");
        $stmt->execute();
        $kategoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $kategoris, 'message' => 'Categories fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single category by ID
function fetch_kategori($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM kategori WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $kategori = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($kategori) {
            echo json_encode(['data' => $kategori, 'message' => 'Category fetched successfully']);
        } else {
            echo json_encode(['message' => 'Category not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new category
function insert_kategori() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_kategori'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (:nama_kategori)");
            $stmt->bindParam(':nama_kategori', $data['nama_kategori']);
            
            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_kategori($last_id); // Return the inserted category data
            } else {
                echo json_encode(['message' => 'Failed to insert category']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing category
function update_kategori() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_kategori'], $data['id'])) {
        try {
            $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = :nama_kategori WHERE id = :id");
            $stmt->bindParam(':nama_kategori', $data['nama_kategori']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_kategori($data['id']); // Return the updated category data
            } else {
                echo json_encode(['message' => 'Failed to update category']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a category by ID
function delete_kategori() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM kategori WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Category deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete category']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
