<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include 'config.php';

// Headers for allowing CORS and JSON response format
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Read the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to send JSON response
function send_response($status_code, $data) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Define valid ENUM values for the role
$valid_roles = ['admin', 'penulis', 'pembaca', 'peninjau'];

// Process the request based on HTTP method
switch($method) {
    case 'POST':
        // Create a new user
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->uid) && 
            !empty($data->nama_pengguna) && 
            !empty($data->password) && 
            !empty($data->email) && 
            !empty($data->role) && 
            !empty($data->nama_lengkap)
        ) {
            // Validate role
            if (!in_array($data->role, $valid_roles)) {
                send_response(400, ["message" => "Invalid role. Must be one of: 'admin', 'penulis', 'pembaca', 'peninjau'."]);
            }

            // Validate email format
            if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                send_response(400, ["message" => "Invalid email format."]);
            }

            // Validate password length
            if (strlen($data->password) < 8) {
                send_response(400, ["message" => "Password must be at least 8 characters long."]);
            }

            // Check if email already exists
            $checkEmailQuery = "SELECT COUNT(*) as count FROM user WHERE email = :email";
            $stmt = $conn->prepare($checkEmailQuery);
            $stmt->execute(['email' => $data->email]);
            $emailRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($emailRow['count'] > 0) {
                send_response(409, ["message" => "Email is already registered."]);
            }

            // Check if UID already exists
            $checkQuery = "SELECT COUNT(*) as count FROM user WHERE uid = :uid";
            $stmt = $conn->prepare($checkQuery);
            $stmt->execute(['uid' => $data->uid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                send_response(409, ["message" => "User with this UID already exists."]);
            }

            // Hash the password
            $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);

            // Prepare the INSERT query
            $query = "INSERT INTO user (uid, nama_pengguna, password, email, profile_pic, role, kredensial, nama_lengkap) 
                      VALUES (:uid, :nama_pengguna, :password, :email, :profile_pic, :role, :kredensial, :nama_lengkap)";
            $stmt = $conn->prepare($query);

            // Default values for profile picture and credentials
            $defaultProfilePic = isset($data->profile_pic) ? $data->profile_pic : 'default.jpg';
            $defaultKredensial = isset($data->kredensial) ? $data->kredensial : '';

            // Parameters to bind to the query
            $params = [
                'uid' => $data->uid,
                'nama_pengguna' => $data->nama_pengguna,
                'password' => $hashedPassword,
                'email' => $data->email,
                'profile_pic' => $defaultProfilePic,
                'role' => $data->role,
                'kredensial' => $defaultKredensial,
                'nama_lengkap' => $data->nama_lengkap
            ];

            // Execute the query
            if ($stmt->execute($params)) {
                send_response(201, ["message" => "User created successfully."]);
            } else {
                send_response(500, ["message" => "Failed to create user."]);
            }
        } else {
            send_response(400, ["message" => "Incomplete data."]);
        }
        break;

    case 'GET':
        // Retrieve users or a single user by UID
        if (isset($_GET['uid'])) {
            $query = "SELECT uid, nama_pengguna, email, profile_pic, role, nama_lengkap FROM user WHERE uid = :uid";
            $stmt = $conn->prepare($query);
            $stmt->execute(['uid' => $_GET['uid']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            send_response(200, $user ? $user : ["message" => "User not found."]);
        } else {
            $query = "SELECT uid, nama_pengguna, email, profile_pic, role, nama_lengkap FROM user";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            send_response(200, $users);
        }
        break;

    default:
        send_response(405, ["message" => "Method not allowed."]);
        break;
}
?>