<?php
include 'koneksi.php'; // Include your connection file

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
            fetch_reaksi($_GET['id']);
        } else {
            fetch_all_reaksi();
        }
        break;
    case 'POST':
        insert_reaksi();
        break;
    case 'PUT':
        update_reaksi();
        break;
    case 'DELETE':
        delete_reaksi();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all reactions
function fetch_all_reaksi() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM reaksi_berita");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $data, 'message' => 'Reactions fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a specific reaction by id
function fetch_reaksi($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM reaksi_berita WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            echo json_encode(['data' => $data, 'message' => 'Reaction fetched successfully']);
        } else {
            echo json_encode(['message' => 'Reaction not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new reaction
function insert_reaksi() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['pengguna_id'], $input['berita_id'], $input['jenis_reaksi'], $input['tanggal_reaksi'], $input['count'])) {
        try {
            $pengguna_id = $input['pengguna_id'];
            $berita_id = $input['berita_id'];
            $jenis_reaksi = $input['jenis_reaksi'];
            $tanggal_reaksi = $input['tanggal_reaksi'];
            $count = $input['count'];

            $stmt = $conn->prepare("INSERT INTO reaksi_berita (pengguna_id, berita_id, jenis_reaksi, tanggal_reaksi, count) VALUES (:pengguna_id, :berita_id, :jenis_reaksi, :tanggal_reaksi, :count)");
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_INT);
            $stmt->bindParam(':jenis_reaksi', $jenis_reaksi, PDO::PARAM_STR);
            $stmt->bindParam(':tanggal_reaksi', $tanggal_reaksi, PDO::PARAM_STR);
            $stmt->bindParam(':count', $count, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Reaction inserted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to insert reaction']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update a reaction
function update_reaksi() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'], $input['pengguna_id'], $input['berita_id'], $input['jenis_reaksi'], $input['tanggal_reaksi'], $input['count'])) {
        try {
            $id = $input['id'];
            $pengguna_id = $input['pengguna_id'];
            $berita_id = $input['berita_id'];
            $jenis_reaksi = $input['jenis_reaksi'];
            $tanggal_reaksi = $input['tanggal_reaksi'];
            $count = $input['count'];
            
            $stmt = $conn->prepare("UPDATE reaksi_berita SET pengguna_id = :pengguna_id, berita_id = :berita_id, jenis_reaksi = :jenis_reaksi, tanggal_reaksi = :tanggal_reaksi, count = :count WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':berita_id', $berita_id, PDO::PARAM_INT);
            $stmt->bindParam(':jenis_reaksi', $jenis_reaksi, PDO::PARAM_STR);
            $stmt->bindParam(':tanggal_reaksi', $tanggal_reaksi, PDO::PARAM_STR);
            $stmt->bindParam(':count', $count, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Reaction updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update reaction']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a reaction
function delete_reaksi() {
    global $conn;
    parse_str(file_get_contents("php://input"), $delete_vars);

    if (!empty($delete_vars['id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM reaksi_berita WHERE id = :id");
            $stmt->bindParam(':id', $delete_vars['id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Reaction deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete reaction']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>