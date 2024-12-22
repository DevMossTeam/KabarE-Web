<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    
        if (isset($data['nama_pengguna'], $data['password'], $data['email'], $data['role'])) {
            try {
                $check_stmt = $this->conn->prepare("SELECT * FROM user WHERE nama_pengguna = :nama_pengguna OR email = :email");
                $check_stmt->bindParam(':nama_pengguna', $data['nama_pengguna']);
                $check_stmt->bindParam(':email', $data['email']);
                $check_stmt->execute();
    
                if ($check_stmt->rowCount() > 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
                } else {
                    $stmt = $this->conn->prepare("INSERT INTO user (uid, nama_pengguna, password, email, role, kredensial, nama_lengkap, profile_pic) 
                                                VALUES (:uid, :nama_pengguna, :password, :email, :role, :kredensial, :nama_lengkap, :profile_pic)");
                    $stmt->bindParam(':uid', $uid);
                    $stmt->bindParam(':nama_pengguna', $data['nama_pengguna']);
                    $stmt->bindParam(':password', $data['password']);
                    $stmt->bindParam(':email', $data['email']);
                    $stmt->bindParam(':role', $data['role']);
                    $stmt->bindParam(':kredensial', $data['kredensial']);
                    $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
                    $stmt->bindParam(':profile_pic', $data['profile_pic']);
    
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

    public function updateUser($id, $data) {
        try {
            $query = "UPDATE user SET ";
            $params = [];
        
            if (isset($data['nama_pengguna'])) {
                $query .= "nama_pengguna = :nama_pengguna, ";
                $params[':nama_pengguna'] = $data['nama_pengguna'];
            }
            if (isset($data['password'])) {
                $query .= "password = :password, ";
                $params[':password'] = $data['password'];
            }
            if (isset($data['email'])) {
                $query .= "email = :email, ";
                $params[':email'] = $data['email'];
            }
            if (isset($data['role'])) {
                $query .= "role = :role, ";
                $params[':role'] = $data['role'];
            }
            if (isset($data['kredensial'])) {
                $query .= "kredensial = :kredensial, ";
                $params[':kredensial'] = $data['kredensial'];
            }
            if (isset($data['nama_lengkap'])) {
                $query .= "nama_lengkap = :nama_lengkap, ";
                $params[':nama_lengkap'] = $data['nama_lengkap'];
            }
        
            // Remove the trailing comma
            $query = rtrim($query, ', ');
        
            // Add the condition to match the user ID
            $query .= " WHERE uid = :id";
        
            // Bind the user ID to the query
            $params[':id'] = $id;
        
            // Prepare and execute the statement
            $stmt = $this->conn->prepare($query);
            
            // Execute the update
            if ($stmt->execute($params)) {
                $this->fetchUser($id);  // Return the updated user data
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
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

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
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
?>