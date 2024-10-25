<?php
require '../vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client(['client_id' => '894068159772-teaqelumke1vmctgtg04921otomv6oa6.apps.googleusercontent.com']);
$id_token = json_decode(file_get_contents('php://input'), true)['id_token'];

try {
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        $userid = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];

        // Lakukan login atau registrasi pengguna berdasarkan $userid
        // Misalnya, periksa apakah pengguna sudah ada di database
        include '../connection/config.php';

        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Pengguna sudah ada, lakukan login
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            echo json_encode(['success' => true]);
        } else {
            // Pengguna baru, lakukan registrasi
            $stmt = $conn->prepare("INSERT INTO user (email, name, google_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $name, $userid);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['email'] = $email;
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mendaftarkan pengguna baru.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Token tidak valid.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

