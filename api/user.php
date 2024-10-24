<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

header("Content-Type: application/json");


$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_user($_GET['id']);
        } else {
            fetch_users();
        }
        break;
    case 'POST':
        insert_user();
        break;
    case 'PUT':
        update_user();
        break;
    case 'DELETE':
        delete_user();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all users
function fetch_users() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM user");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['data' => $users, 'message' => 'Users fetched successfully']);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to fetch users', 'error' => $e->getMessage()]);
    }
}

// Fetch a single user by ID
function fetch_user($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['data' => $user, 'message' => 'User fetched successfully']);
        } else {
            echo json_encode(['message' => 'User not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to fetch user', 'error' => $e->getMessage()]);
    }
}

// Insert a new user
function insert_user() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        $stmt = $conn->prepare("INSERT INTO user (username, password, profil_id, email, status_subcribtion) 
                                VALUES (:username, :password, :profil_id, :email, :status_subcribtion)");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_BCRYPT)); 
        $stmt->bindParam(':profil_id', $data['profil_id'], PDO::PARAM_INT); 
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':status_subcribtion', $data['status_subcribtion'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $last_id = $conn->lastInsertId(); // Get the last inserted ID
            fetch_user($last_id); // Return the inserted user data
        } else {
            echo json_encode(['message' => 'Failed to insert user']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to insert user', 'error' => $e->getMessage()]);
    }
}

// Update an existing user
function update_user() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        $stmt = $conn->prepare("UPDATE user SET username = :username, password = :password, profil_id = :profil_id, email = :email, status_subcribtion = :status_subcribtion 
                                WHERE id = :id");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $stmt->bindParam(':profil_id', $data['profil_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':status_subcribtion', $data['status_subcribtion'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            fetch_user($data['id']); // Return the updated user data
        } else {
            echo json_encode(['message' => 'Failed to update user']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to update user', 'error' => $e->getMessage()]);
    }
}

// Delete a user by ID
function delete_user() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        $stmt = $conn->prepare("DELETE FROM user WHERE id = :id");
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete user']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to delete user', 'error' => $e->getMessage()]);
    }
}
?>
