<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
include 'koneksi.php'; 
=======
include 'koneksi.php'; // Include connection settings

header("Content-Type: application/json");

class UserAPI {
    private $conn;

    public function __construct($server, $db, $username, $password) {
        try {
            $this->conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $e->getMessage()]);
            exit;
        }
    }

    public function fetchUsers() {
        try {
            // Prepare and execute the query
            $stmt = $this->conn->prepare("SELECT * FROM user");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Set header to inform the client that the response is JSON
            header('Content-Type: application/json');
        
            // Check if users were found
            if (empty($users)) {
                echo json_encode(['status' => 'error', 'message' => 'No users found']);
            } else {
                // Process BLOB data (e.g., profile_pic) by converting to base64
                foreach ($users as &$user) {
                    if (isset($user['profile_pic']) && $user['profile_pic'] !== NULL) {
                        // Convert BLOB to base64 string for JSON compatibility
                        $user['profile_pic'] = base64_encode($user['profile_pic']);
                    }
                }
    
                echo json_encode(['status' => 'success', 'data' => $users, 'message' => 'Users fetched successfully']);
            }
        } catch (PDOException $e) {
            // Return error message if there is an exception
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }    

    // Fetch a single user by ID
    public function fetchUser($id) {
        try {
            // Prepare and execute the query to fetch the user by ID
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE uid = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user data exists
            if ($user) {
                // Handle BLOB data (profile_pic) and convert it to base64 if not NULL
                if (isset($user['profile_pic']) && $user['profile_pic'] !== NULL) {
                    $user['profile_pic'] = base64_encode($user['profile_pic']);
                }
    
                echo json_encode(['status' => 'success', 'data' => $user, 'message' => 'User fetched successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    

    // Function to generate a random alphanumeric UID of a specified length
    private function generateUID() {
        try {
            // Fetch the highest existing UID
            $stmt = $this->conn->prepare("SELECT MAX(CAST(SUBSTRING(uid, 2) AS UNSIGNED)) AS max_uid FROM user");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there's no UID in the database, start with a base value (e.g., 1)
            $max_uid = isset($result['max_uid']) ? $result['max_uid'] : 0;

            // Increment the UID
            $new_uid = 'U' . ($max_uid + 1);

            return $new_uid;
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return null;
        }
    }


    // Insert a new user
    public function insertUser($data) {
        $uid = $this->generateUID();

        if (isset($data['nama_pengguna'], $data['password'], $data['email'], $data['role'], $data['kredensial'], $data['waktu_login'], $data['nama_lengkap'])) {
            try {
                $check_stmt = $this->conn->prepare("SELECT * FROM user WHERE nama_pengguna = :nama_pengguna OR email = :email");
                $check_stmt->bindParam(':nama_pengguna', $data['nama_pengguna']);
                $check_stmt->bindParam(':email', $data['email']);
                $check_stmt->execute();

                if ($check_stmt->rowCount() > 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
                } else {
                    $stmt = $this->conn->prepare("INSERT INTO user (uid, nama_pengguna, password, email, role, kredensial, nama_lengkap, waktu_login) 
                                                VALUES (:uid, :nama_pengguna, :password, :email, :role, :kredensial, :nama_lengkap, :waktu_login)");
                    $stmt->bindParam(':uid', $uid);
                    $stmt->bindParam(':nama_pengguna', $data['nama_pengguna']);
                    $stmt->bindParam(':password', $data['password']);
                    $stmt->bindParam(':email', $data['email']);
                    $stmt->bindParam(':role', $data['role']);
                    $stmt->bindParam(':kredensial', $data['kredensial']);
                    $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
                    $stmt->bindParam(':waktu_login', $data['waktu_login']);

                    if ($stmt->execute()) {
                        $this->fetchUser($uid);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to insert user']);
                    }
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }

    // Update an existing user
    public function updateUser($id, $data) {
        if (isset($data['nama_pengguna'], $data['password'], $data['email'], $data['role'], $data['kredensial'], $data['waktu_login'], $data['nama_lengkap'])) {
            try {
                $stmt = $this->conn->prepare("UPDATE user SET nama_pengguna = :nama_pengguna, password = :password, email = :email, 
                                             role = :role, kredensial = :kredensial, nama_lengkap = :nama_lengkap, waktu_login = :waktu_login WHERE uid = :id");
                $stmt->bindParam(':nama_pengguna', $data['nama_pengguna']);
                $stmt->bindParam(':password', $data['password']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':role', $data['role']);
                $stmt->bindParam(':kredensial', $data['kredensial']);
                $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
                $stmt->bindParam(':waktu_login', $data['waktu_login']);
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $this->fetchUser($id);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }

    // Delete a user by ID
    public function deleteUser($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE uid = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $delete_stmt = $this->conn->prepare("DELETE FROM user WHERE uid = :id");
                $delete_stmt->bindParam(':id', $id, PDO::PARAM_STR);
                if ($delete_stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

// Instantiate the class and handle the request
$userAPI = new UserAPI($server, $db, $username, $password);
>>>>>>> 29b8b0facbd178c030519b31d9ac2de19a2d971d

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
<<<<<<< HEAD
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
=======
        if (isset($_GET['id'])) {
            $userAPI->fetchUser($_GET['id']);
        } else {
            $userAPI->fetchUsers();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $userAPI->insertUser($data);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $userAPI->updateUser($query['id'], $data);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
        }
        break;
    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $userAPI->deleteUser($query['id']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
        }
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}
>>>>>>> 29b8b0facbd178c030519b31d9ac2de19a2d971d
?>