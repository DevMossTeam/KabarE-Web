<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'POST':
        if (isset($_GET['action']) && $_GET['action'] === 'login') {
            login();
        }
        break;
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            logout();
        }
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Login function
function login() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        $stmt = $conn->prepare("SELECT uid, username, password, role FROM user WHERE username = :username");
        $stmt->bindParam(':username', $data['username']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['password'])) {
            // Generate token and its expiration
            $token = generateToken();
            $token_expired_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Update user token and expiration in the database
            $update_stmt = $conn->prepare("UPDATE user SET token = :token, token_expired_at = :token_expired_at WHERE uid = :uid");
            $update_stmt->bindParam(':token', $token);
            $update_stmt->bindParam(':token_expired_at', $token_expired_at);
            $update_stmt->bindParam(':uid', $user['uid']);
            $update_stmt->execute();

            // Successful login, generate a session
            session_start();
            $_SESSION['uid'] = $user['uid'];  // Change to uid
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo json_encode(['message' => 'Login successful', 'user' => $user, 'token' => $token, 'token_expired_at' => $token_expired_at]);
        } else {
            echo json_encode(['message' => 'Invalid username or password']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Login failed', 'error' => $e->getMessage()]);
    }
}

// Logout function
function logout() {
    session_start();
    session_destroy();
    echo json_encode(['message' => 'Logout successful']);
}
?>