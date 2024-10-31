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
        if (isset($_GET['uid'])) {
            fetch_user($_GET['uid']);
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
    $conn = getKoneksi(); // Get the connection
    try {
        $stmt = mysqli_prepare($conn, "SELECT uid, username, email, profile_pic, role, kredensial FROM user");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(['data' => $users, 'message' => 'Users fetched successfully']);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Failed to fetch users', 'error' => $e->getMessage()]);
    }
}


// Fetch a single user by UID
function fetch_user($uid) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT uid, username, email, profile_pic, role, kredensial FROM user WHERE uid = :uid"); // Exclude password from selection
        $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
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
    
    // Check if required fields are present
    if (empty($data['username']) || empty($data['password']) || empty($data['profil_id'])) {
        echo json_encode(['message' => 'Required fields missing']);
        return;
    }
    
    try {
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT); // Hash the password
        $uid = generateUid(); // Generate a random UID

        $stmt = $conn->prepare("INSERT INTO user (uid, username, password, profil_id, email, profile_pic, token, token_expired_at, role, kredensial) 
                                VALUES (:uid, :username, :password, :profil_id, :email, :profile_pic, :token, :token_expired_at, :role, :kredensial)");
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $hashed_password); // Use hashed password here
        $stmt->bindParam(':profil_id', $data['profil_id'], PDO::PARAM_INT); 
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':profile_pic', $data['profile_pic'], PDO::PARAM_LOB); 
        $stmt->bindParam(':token', $data['token']);
        $stmt->bindParam(':token_expired_at', $data['token_expired_at']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':kredensial', $data['kredensial']);
        
        if ($stmt->execute()) {
            fetch_user($uid); // Fetch the newly created user
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
    
    // Check if uid is present for update
    if (empty($data['uid'])) {
        echo json_encode(['message' => 'UID is required for update']);
        return;
    }
    
    try {
        // If the password is being updated, hash it; otherwise, retain the existing password
        $password_to_update = isset($data['password']) && !empty($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null;

        $stmt = $conn->prepare("UPDATE user SET username = :username, password = COALESCE(:password, password), profil_id = :profil_id, email = :email, profile_pic = :profile_pic, token = :token, token_expired_at = :token_expired_at, role = :role, kredensial = :kredensial 
                                WHERE uid = :uid");
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $password_to_update); // Hash if provided
        $stmt->bindParam(':profil_id', $data['profil_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':profile_pic', $data['profile_pic'], PDO::PARAM_LOB);
        $stmt->bindParam(':token', $data['token']);
        $stmt->bindParam(':token_expired_at', $data['token_expired_at']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':kredensial', $data['kredensial']);
        $stmt->bindParam(':uid', $data['uid'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            fetch_user($data['uid']);
        } else {
            echo json_encode(['message' => 'Failed to update user']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to update user', 'error' => $e->getMessage()]);
    }
}

// Delete a user by UID
function delete_user() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Check if uid is present for deletion
    if (empty($data['uid'])) {
        echo json_encode(['message' => 'UID is required for deletion']);
        return;
    }
    
    try {
        $stmt = $conn->prepare("DELETE FROM user WHERE uid = :uid");
        $stmt->bindParam(':uid', $data['uid'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete user']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to delete user', 'error' => $e->getMessage()]);
    }
}

// Generate a random UID
function generateUid($length = 33) {
    return bin2hex(random_bytes($length / 2)); // Generate a random hexadecimal UID
}
?>