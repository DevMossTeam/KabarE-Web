<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

header("Content-Type: application/json");

$request_method = $_SERVER['REQUEST_METHOD'];
switch ($request_method) {
    case 'GET':
        isset($_GET['id']) ? fetch_peran($_GET['id']) : fetch_perans();
        break;
    case 'POST':
        insert_peran();
        break;
    case 'PUT':
        update_peran();
        break;
    case 'DELETE':
        delete_peran();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function fetch_perans() {
    global $koneksi;
    $result = mysqli_query($koneksi, "SELECT * FROM peran");

    if ($result) {
        $perans = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(['data' => $perans, 'message' => 'Roles fetched successfully']);
    } else {
        echo json_encode(['message' => 'Failed to execute query']);
    }
}

// Fetch a single role by ID
function fetch_peran($id) {
    global $koneksi;
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM peran WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $peran = mysqli_fetch_assoc($result);
            echo json_encode(['data' => $peran, 'message' => 'Role fetched successfully']);
        } else {
            echo json_encode(['message' => 'Role not found']);
        }
    } else {
        echo json_encode(['message' => 'Failed to execute query']);
    }
}

// Insert a new role
function insert_peran() {
    global $koneksi;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_peran'])) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO peran (nama_peran) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $data['nama_peran']);
        
        if (mysqli_stmt_execute($stmt)) {
            $last_id = mysqli_insert_id($koneksi);
            fetch_peran($last_id); // Fetch the inserted role
        } else {
            echo json_encode(['message' => 'Failed to insert role']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update an existing role
function update_peran() {
    global $koneksi;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama_peran'], $data['id'])) {
        $stmt = mysqli_prepare($koneksi, "UPDATE peran SET nama_peran = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $data['nama_peran'], $data['id']);
        
        if (mysqli_stmt_execute($stmt)) {
            fetch_peran($data['id']); 
        } else {
            echo json_encode(['message' => 'Failed to update role']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a role
function delete_peran() {
    global $koneksi;
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM peran WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $data['id']);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['message' => 'Role deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete role']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?> 