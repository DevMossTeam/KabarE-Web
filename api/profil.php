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
        if (isset($_GET['pengguna_id'])) {
            fetch_pengguna_profile($_GET['pengguna_id']);
        } else {
            fetch_all_pengguna_profiles();
        }
        break;
    case 'POST':
        insert_pengguna_profile();
        break;
    case 'PUT':
        update_pengguna_profile();
        break;
    case 'DELETE':
        delete_pengguna_profile();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all profiles
function fetch_all_pengguna_profiles() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM pengguna_profile");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $data, 'message' => 'Data fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Fetch a specific profile by pengguna_id
function fetch_pengguna_profile($pengguna_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM pengguna_profile WHERE pengguna_id = :pengguna_id");
        $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
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

// Insert a new profile
function insert_pengguna_profile() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['pengguna_id'], $input['nama_depan'], $input['nama_belakang'], $input['nomor_telepon'], $input['bio'])) {
        try {
            $pengguna_id = $input['pengguna_id'];
            $nama_depan = $input['nama_depan'];
            $nama_belakang = $input['nama_belakang'];
            $nomor_telepon = $input['nomor_telepon'];
            $bio = $input['bio'];
            
            $stmt = $conn->prepare("INSERT INTO pengguna_profile (pengguna_id, nama_depan, nama_belakang, nomor_telepon, bio) VALUES (:pengguna_id, :nama_depan, :nama_belakang, :nomor_telepon, :bio)");
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':nama_depan', $nama_depan, PDO::PARAM_STR);
            $stmt->bindParam(':nama_belakang', $nama_belakang, PDO::PARAM_STR);
            $stmt->bindParam(':nomor_telepon', $nomor_telepon, PDO::PARAM_STR);
            $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Profile inserted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to insert profile']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update a profile
function update_pengguna_profile() {
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['pengguna_id'], $input['nama_depan'], $input['nama_belakang'], $input['nomor_telepon'], $input['bio'])) {
        try {
            $pengguna_id = $input['pengguna_id'];
            $nama_depan = $input['nama_depan'];
            $nama_belakang = $input['nama_belakang'];
            $nomor_telepon = $input['nomor_telepon'];
            $bio = $input['bio'];
            
            $stmt = $conn->prepare("UPDATE pengguna_profile SET nama_depan = :nama_depan, nama_belakang = :nama_belakang, nomor_telepon = :nomor_telepon, bio = :bio WHERE pengguna_id = :pengguna_id");
            $stmt->bindParam(':pengguna_id', $pengguna_id, PDO::PARAM_INT);
            $stmt->bindParam(':nama_depan', $nama_depan, PDO::PARAM_STR);
            $stmt->bindParam(':nama_belakang', $nama_belakang, PDO::PARAM_STR);
            $stmt->bindParam(':nomor_telepon', $nomor_telepon, PDO::PARAM_STR);
            $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Profile updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update profile']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Delete a profile
function delete_pengguna_profile() {
    global $conn;
    parse_str(file_get_contents("php://input"), $delete_vars);

    if (!empty($delete_vars['pengguna_id'])) {
        try {
            $stmt = $conn->prepare("DELETE FROM pengguna_profile WHERE pengguna_id = :pengguna_id");
            $stmt->bindParam(':pengguna_id', $delete_vars['pengguna_id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Profile deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete profile']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
?>