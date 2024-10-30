<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;

if ($email) {
    $_SESSION['email'] = $email;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email not provided']);
}