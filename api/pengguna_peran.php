<?php
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
        if (isset($_GET['pengguna_id']) && isset($_GET['peran_id'])) {
            fetch_pengguna_peran($_GET['pengguna_id'], $_GET['peran_id']);
        } else {
            fetch_all_pengguna_peran();
        }
        break;
    case 'POST':
        insert_pengguna_peran();
        break;
    case 'PUT':
        update_pengguna_peran();
        break;
    case 'DELETE':
        delete_pengguna_peran();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all records
function fetch_all_pengguna_peran() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM pengguna_peran");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $data, 'message' => 'Data fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a specific record
function fetch_pengguna_peran($pengguna_id, $peran_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM pengguna_peran WHERE pengguna_id = :pengguna_id AND peran_id = :peran_id");
        $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
        $stmt->bindParam(':peran_id', $peran_id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            echo json_encode(['data' => $data, 'message' => 'Data fetched successfully']);
        } else {
            echo json_encode(['message' => 'Data not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Insert a new record
function insert_pengguna_peran() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['pengguna_id'], $input['peran_id'])) {
        try {
            $pengguna_id = $input['pengguna_id'];
            $peran_id = $input['peran_id'];
            
            $stmt = $conn->prepare("INSERT INTO pengguna_peran (pengguna_id, peran_id) VALUES (:pengguna_id, :peran_id)");
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':peran_id', $peran_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Record inserted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to insert record']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing record
function update_pengguna_peran() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['pengguna_id'], $input['peran_id'])) {
        try {
            $pengguna_id = $input['pengguna_id'];
            $peran_id = $input['peran_id'];
            
            $stmt = $conn->prepare("UPDATE pengguna_peran SET peran_id = :peran_id WHERE pengguna_id = :pengguna_id");
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':peran_id', $peran_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Record updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update record']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a record
function delete_pengguna_peran() {
    global $conn;
    parse_str(file_get_contents("php://input"), $delete_vars);

    if (!empty($delete_vars['pengguna_id']) && !empty($delete_vars['peran_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM pengguna_peran WHERE pengguna_id = :pengguna_id AND peran_id = :peran_id");
            $stmt->bindParam(':pengguna_id', $delete_vars['pengguna_id'], PDO::PARAM_INT);
            $stmt->bindParam(':peran_id', $delete_vars['peran_id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Record deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete record']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>
