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
            fetch_berita($_GET['id']);
        } else {
            fetch_beritas();
        }
        break;
    case 'POST':
        insert_berita();
        break;
    case 'PUT':
        update_berita();
        break;
    case 'DELETE':
        delete_berita();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all berita
function fetch_beritas() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita");
        $stmt->execute();
        $beritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $beritas, 'message' => 'Berita fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a single berita by ID
function fetch_berita($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM berita WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $berita = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($berita) {
            echo json_encode(['data' => $berita, 'message' => 'Berita fetched successfully']);
        } else {
            echo json_encode(['message' => 'Berita not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new berita
function insert_berita() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['judul'], $data['konten'], $data['status'], $data['tanggal_dibuat'])) {
        try {
            $stmt = $conn->prepare("INSERT INTO berita (judul, konten, pengguna_id, kategori_id, status, tanggal_dibuat, tanggal_diterbitkan) 
                                    VALUES (:judul, :konten, :pengguna_id, :kategori_id, :status, :tanggal_dibuat, :tanggal_diterbitkan)");
            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':konten', $data['konten']);
            $stmt->bindParam(':pengguna_id', $data['pengguna_id'], PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $data['kategori_id'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':tanggal_dibuat', $data['tanggal_dibuat']);
            $stmt->bindParam(':tanggal_diterbitkan', $data['tanggal_diterbitkan']);

            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId(); // Get the last inserted ID
                fetch_berita($last_id); // Return the inserted berita data
            } else {
                echo json_encode(['message' => 'Failed to insert berita']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing berita
function update_berita() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'], $data['judul'], $data['konten'], $data['status'], $data['tanggal_dibuat'])) {
        try {
            $stmt = $conn->prepare("UPDATE berita SET judul = :judul, konten = :konten, pengguna_id = :pengguna_id, 
                                    kategori_id = :kategori_id, status = :status, tanggal_dibuat = :tanggal_dibuat, 
                                    tanggal_diterbitkan = :tanggal_diterbitkan WHERE id = :id");
            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':konten', $data['konten']);
            $stmt->bindParam(':pengguna_id', $data['pengguna_id'], PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $data['kategori_id'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':tanggal_dibuat', $data['tanggal_dibuat']);
            $stmt->bindParam(':tanggal_diterbitkan', $data['tanggal_diterbitkan']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                fetch_berita($data['id']); // Return the updated berita data
            } else {
                echo json_encode(['message' => 'Failed to update berita']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a berita by ID
function delete_berita() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM berita WHERE id = :id");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Berita deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete berita']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
