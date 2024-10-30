<?php
session_start();
require 'firebase/config.php';

$firebase = new FirebaseConfig();
$auth = $firebase->getAuth();

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'];

try {
    // Verify the token using Firebase Auth
    $verifiedIdToken = $auth->verifyIdToken($token);
    $uid = $verifiedIdToken->getClaim('sub');

    // Store user ID in session
    $_SESSION['user_id'] = $uid;

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Log the error message
    error_log('Token verification error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
