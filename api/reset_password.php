<?php
// Enable error reporting for debugging (remove in production)
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

// Helper function to send JSON response
function send_response($status_code, $data) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(405, ["message" => "Method not allowed. Only POST is supported."]);
}

// Read the JSON payload
$input = file_get_contents("php://input");
$data = json_decode($input);

// Validate the input
if (!isset($data->email) || !isset($data->new_password) || empty($data->email) || empty($data->new_password)) {
    send_response(400, ["message" => "Both email and new password are required."]);
}

// Validate email format
if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    send_response(400, ["message" => "Invalid email format."]);
}

// Ensure the password meets security requirements
if (strlen($data->new_password) < 6) {
    send_response(400, ["message" => "Password must be at least 6 characters long."]);
}

// Update the password securely
try {
    // Hash the password for security
    $hashed_password = password_hash($data->new_password, PASSWORD_BCRYPT);

    // Prepare the SQL query
    $query = "UPDATE user SET password = :new_password WHERE email = :email";
    $stmt = $conn->prepare($query);

    // Bind parameters to prevent SQL injection
    $stmt->bindParam(':new_password', $hashed_password);
    $stmt->bindParam(':email', $data->email);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            send_response(200, ["message" => "Password reset successfully."]);
        } else {
            send_response(404, ["message" => "No user found with the provided email."]);
        }
    } else {
        send_response(500, ["message" => "Failed to reset password. Please try again later."]);
    }
} catch (PDOException $e) {
    // Log the error in a file for debugging (not exposed to users)
    error_log("Database error: " . $e->getMessage());
    send_response(500, ["message" => "An internal server error occurred."]);
}
?>
