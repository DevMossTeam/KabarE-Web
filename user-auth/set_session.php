<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;
$profile_pic = $data['profile_pic'] ?? null;

if ($email) {
    $_SESSION['email'] = $email;
    $_SESSION['profile_pic'] = $profile_pic;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email not provided']);
}