<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$server = 'localhost';
$db = 'kabare_db';
$username = 'root';
$password = '';
$koneksi = mysqli_connect($server, $username, $password, $db);

if (mysqli_connect_errno()) {
    echo json_encode(['message' => 'Koneksi gagal: ' . mysqli_connect_error()]);
    exit; // Stop script execution if connection fails
}
?>