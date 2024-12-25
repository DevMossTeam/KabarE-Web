<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

header("Content-Type: application/json");

try {
    $conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    exit;
}

// Handle request method
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            fetch_berita($_GET['id']);
        } else {
            fetch_beritas();
        }
        break;
    case 'POST':
        insert_berita();
        break;
    case 'PUT':
        update_berita();
        break;
    case 'DELETE':
        delete_berita();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fetch all berita
function fetch_beritas() {
    global $conn;  // Assuming $conn is the PDO connection you already have

    try {
        // Prepare SQL query to fetch data
        $stmt = $conn->prepare("SELECT 
                                    berita.*, 
                                    user.nama_pengguna,                                                                         
                                    COUNT(CASE WHEN reaksi.jenis_reaksi = 'Suka' THEN 1 END) AS suka_count, 
                                    COUNT(CASE WHEN reaksi.jenis_reaksi = 'Tidak Suka' THEN 1 END) AS bukan_suka_count 
                                FROM 
                                    berita 
                                JOIN 
                                    user ON berita.user_id = user.uid 
                                LEFT JOIN 
                                    reaksi ON reaksi.berita_id = berita.id 
                                GROUP BY 
                                    berita.id, user.uid;
                                ");
        $stmt->execute();

        // Fetch all rows
        $beritas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the result as a JSON response
        echo json_encode(['data' => $beritas, 'message' => 'Berita fetched successfully']);
    } catch (PDOException $e) {
        // If there's an error, return the error message
        echo json_encode(['error' => $e->getMessage()]);
    }
}