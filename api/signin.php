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
switch ($method) {
    case 'POST':
        // Sign in: validate username/email and password
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->username_or_email) && !empty($data->password)) {
            $username_or_email = $data->username_or_email;
            $password = $data->password;

            // Check if the username_or_email is an email or username
            $query = "SELECT uid, nama_pengguna, password, role, nama_lengkap FROM user WHERE nama_pengguna = :username_or_email OR email = :username_or_email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(['username_or_email' => $username_or_email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the provided password matches the hashed password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, respond with success
                    unset($user['password']);  // Do not send the password back
                    send_response(200, [
                        "message" => "Sign-In Successful",
                        "user" => $user
                    ]);
                } else {
                    // Incorrect password
                    send_response(401, ["message" => "Invalid password."]);
                }
            } else {
                // User not found
                send_response(404, ["message" => "User not found."]);
            }
        } elseif (!empty($data->google_account)) {
            // Google Sign-In: validate Google account
            $google_account = $data->google_account;

            if (!empty($google_account->email)) {
                $email = $google_account->email;
                $full_name = $google_account->name;
                $profile_picture = $google_account->profile_picture;

                // Check if the email already exists
                $query = "SELECT uid, nama_pengguna, email, nama_lengkap FROM user WHERE email = :email LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Email found, proceed with login
                    send_response(200, [
                        "message" => "Google Sign-In Successful",
                        "user" => $user
                    ]);
                } else {
                    // Email not found, create new user
                    $username = generateUsernameFromEmail($email);

                    // Ensure username is unique
                    $username = getUniqueUsername($username, $conn);

                    // Insert new user data into the database
                    $uid = generateUID();
                    $insertQuery = "INSERT INTO user (uid, nama_pengguna, email, nama_lengkap, role, profile_pic) VALUES (:uid, :username, :email, :full_name, 'pembaca', :profile_picture)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->execute([
                        'uid' => $uid,
                        'username' => $username,
                        'email' => $email,
                        'full_name' => $full_name,
                        'profile_picture' => $profile_picture
                    ]);

                    // Fetch the newly created user
                    $newUserId = $conn->lastInsertId();
                    $newUserQuery = "SELECT uid, nama_pengguna, nama_lengkap, email FROM user WHERE uid = :uid";
                    $stmt = $conn->prepare($newUserQuery);
                    $stmt->execute(['uid' => $newUserId]);
                    $newUser = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Respond with the new user data
                    send_response(201, [
                        "message" => "New account created successfully",
                        "user" => $newUser
                    ]);
                }
            } else {
                send_response(400, ["message" => "Google account data is incomplete."]);
            }
        } else {
            send_response(400, ["message" => "Incomplete data."]);
        }
        break;

    default:
        send_response(405, ["message" => "Method not allowed."]);
        break;
}

// Function to generate username from email (e.g., "john@gmail.com" -> "john")
function generateUsernameFromEmail($email) {
    return substr($email, 0, strpos($email, '@'));
}

// Function to ensure the username is unique
function getUniqueUsername($username, $conn) {
    // Check if username exists in the database
    $query = "SELECT COUNT(*) FROM user WHERE nama_pengguna = :username";
    $stmt = $conn->prepare($query);
    $stmt->execute(['username' => $username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // If the username exists, append a number to it
        $i = 1;
        while ($count > 0) {
            $newUsername = $username . $i;
            $stmt->execute(['username' => $newUsername]);
            $count = $stmt->fetchColumn();
            $i++;
        }
        return $newUsername;
    } else {
        return $username;
    }
}

// Function to generate a unique UID for new users
function generateUID() {
    return bin2hex(random_bytes(14)); // Generate 28 character UID
}
?>
