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
header("Access-Control-Allow-Methods: POST");
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
    if (empty($data->username) && empty($data->email)) {
        send_response(400, ["message" => "Username or email is required."]);
    }

    // Build the query dynamically based on the input
    $query = "SELECT uid, nama_pengguna, email FROM user WHERE ";
    $params = [];
    if (!empty($data->username)) {
        $query .= "nama_pengguna = :username";
        $params['username'] = $data->username;
    }
    if (!empty($data->email)) {
        if (!empty($params)) {
            $query .= " OR "; // Add OR if both username and email are provided
        }
        $query .= "email = :email";
        $params['email'] = $data->email;
    }
    $query .= " LIMIT 1";

    // Prepare and execute the query
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // User or email found
            send_response(200, [
                "exists" => true,
                "message" => "Username or email already exists.",
                "data" => $user
            ]);
        } else {
            // No matching user found
            send_response(200, [
                "exists" => false,
                "message" => "Username or email is available."
            ]);
        }
    } catch (PDOException $e) {
        send_response(500, ["message" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Method not allowed
    send_response(405, ["message" => "Method not allowed."]);
}
?>