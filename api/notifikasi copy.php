<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

header("Content-Type: application/json");

class NotifAPI {
    private $conn;

    public function __construct($server, $db, $username, $password) {
        try {
            $this->conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
            exit;
        }
    }

    // Fetch all notifications
    public function fetchNotifs() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM notifikasi");
            $stmt->execute();
            $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $notifs, 'message' => 'Notifications fetched successfully']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Fetch a single notification by ID
    public function fetchNotif($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM notifikasi WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $notif = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($notif) {
                echo json_encode(['data' => $notif, 'message' => 'Notification fetched successfully']);
            } else {
                echo json_encode(['message' => 'Notification not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function fetchNotifByUser($to_user) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM notifikasi WHERE to_user = :to_user");
            $stmt->bindParam(':to_user', $to_user, PDO::PARAM_STR);
            $stmt->execute();
            $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($notifs) {
                echo json_encode(['data' => $notifs, 'message' => 'Notifications fetched successfully']);
            } else {
                echo json_encode(['message' => 'No notifications found for the specified user']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }    

    // Insert a new notification
    public function insertNotif($data) {
        if (isset($data['tipe_notif'], $data['dari_user_id'], $data['to_user'])) {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO notifikasi (tipe_notif, dari_user_id, komen_id, to_user) 
                    VALUES (:tipe_notif, :dari_user_id, :komen_id, :to_user)
                ");
                $komen_id = $data['komen_id'] ?? null; // Optional field
                $stmt->bindParam(':tipe_notif', $data['tipe_notif']);
                $stmt->bindParam(':dari_user_id', $data['dari_user_id']);
                $stmt->bindParam(':komen_id', $komen_id);
                $stmt->bindParam(':to_user', $data['to_user']);
                if ($stmt->execute()) {
                    $last_id = $this->conn->lastInsertId();
                    $this->fetchNotif($last_id); // Return the inserted notification
                } else {
                    echo json_encode(['message' => 'Failed to insert notification']);
                }
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    // Update an existing notification
    public function updateNotif($id, $data) {
        if (isset($data['tipe_notif'], $data['dari_user_id'], $data['to_user'])) {
            try {
                $query = "UPDATE notifikasi SET tipe_notif = :tipe_notif, dari_user_id = :dari_user_id, to_user = :to_user";
                if (isset($data['komen_id'])) {
                    $query .= ", komen_id = :komen_id";
                }
                $query .= " WHERE id = :id";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':tipe_notif', $data['tipe_notif']);
                $stmt->bindParam(':dari_user_id', $data['dari_user_id']);
                $stmt->bindParam(':to_user', $data['to_user']);
                if (isset($data['komen_id'])) {
                    $stmt->bindParam(':komen_id', $data['komen_id']);
                }
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $this->fetchNotif($id); // Return the updated notification
                } else {
                    echo json_encode(['message' => 'Failed to update notification']);
                }
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['message' => 'Invalid input']);
        }
    }

    // Delete a notification by ID
    public function deleteNotif($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM notifikasi WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Notification deleted successfully']);
            } else {
                echo json_encode(['message' => 'Failed to delete notification']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Instantiate the class and handle the request
$notifAPI = new NotifAPI($server, $db, $username, $password);

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $notifAPI->fetchNotif($_GET['id']);
        } else {
            $notifAPI->fetchNotifs();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $notifAPI->insertNotif($data);
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (isset($query['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $notifAPI->updateNotif($query['id'], $data);
        } else {
            echo json_encode(['message' => 'ID is required for updating']);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $notifAPI->deleteNotif($_GET['id']);
        } else {
            echo json_encode(['message' => 'ID is required']);
        }
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}
?>