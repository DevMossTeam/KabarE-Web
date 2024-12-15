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
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Read the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to send JSON response
function send_response($status_code, $data) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Process the request based on HTTP method
if ($method === 'POST') {
    // Read the incoming JSON payload
    $data = json_decode(file_get_contents("php://input"));

    // Validate the input
    if (empty($data->uid)) {
        send_response(400, ["message" => "UID is required."]);
    }

    // Prepare the query to fetch user data based on UID, including all fields
    $query = "SELECT uid, nama_pengguna, password, email, profile_pic, role, kredensial, nama_lengkap 
              FROM user WHERE uid = :uid LIMIT 1";
    $params = ['uid' => $data->uid];

    // Prepare and execute the query
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // If profile_pic exists, convert it to base64 encoding
            if ($user['profile_pic']) {
                $user['profile_pic'] = base64_encode($user['profile_pic']);
            }
            
            // Return the user profile data
            send_response(200, [
                "message" => "User profile retrieved successfully.",
                "data" => $user
            ]);
        } else {
            send_response(404, ["message" => "User not found."]);
        }
    } catch (PDOException $e) {
        send_response(500, ["message" => "Database error: " . $e->getMessage()]);
    }
} elseif ($method === 'GET') {
    // Prepare the query to fetch all user data, including all fields
    $query = "SELECT uid, nama_pengguna, password, email, profile_pic, role, kredensial, nama_lengkap FROM user";

    // Prepare and execute the query
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            // Convert profile_pic to base64 if present
            foreach ($users as &$user) {
                if ($user['profile_pic']) {
                    $user['profile_pic'] = base64_encode($user['profile_pic']);
                }
            }
            send_response(200, [
                "message" => "User data retrieved successfully.",
                "data" => $users
            ]);
        } else {
            send_response(404, ["message" => "No users found."]);
        }
    } catch (PDOException $e) {
        send_response(500, ["message" => "Database error: " . $e->getMessage()]);
    }
} elseif ($method === 'PUT') {
    // Read the incoming JSON payload
    $data = json_decode(file_get_contents("php://input"));

    // Validate the input
    if (empty($data->uid)) {
        send_response(400, ["message" => "UID is required."]);
    }

    // Prepare the fields to update
    $updateFields = [];
    $params = ['uid' => $data->uid];

    if (!empty($data->profile_pic)) {
        $updateFields[] = "profile_pic = :profile_pic";
        $params['profile_pic'] = base64_decode($data->profile_pic); // assuming profile_pic is base64 encoded
    }

    if (!empty($data->username)) {
        $updateFields[] = "nama_pengguna = :username";
        $params['username'] = $data->username;
    }

    if (!empty($data->nama_lengkap)) {
        $updateFields[] = "nama_lengkap = :nama_lengkap";
        $params['nama_lengkap'] = $data->nama_lengkap;
    }

    if (!empty($data->password)) {
        // Hash the password before storing it
        $updateFields[] = "password = :password";
        $params['password'] = password_hash($data->password, PASSWORD_DEFAULT);
    }

    if (!empty($data->email)) {
        $updateFields[] = "email = :email";
        $params['email'] = $data->email;
    }

    if (count($updateFields) > 0) {
        // Prepare the query to update user data
        $query = "UPDATE user SET " . implode(", ", $updateFields) . " WHERE uid = :uid";

        // Prepare and execute the update query
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Return success response
            send_response(200, ["message" => "User profile updated successfully."]);
        } catch (PDOException $e) {
            send_response(500, ["message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        send_response(400, ["message" => "No fields to update."]);
    }
} else {
    send_response(405, ["message" => "Method not allowed."]);
}
?>