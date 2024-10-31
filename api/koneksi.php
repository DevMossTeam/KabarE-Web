<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$server = 'localhost';
$db = 'kabare_db';
$username = 'root';
$password = '';

// Create a connection
$koneksi = mysqli_connect($server, $username, $password, $db);

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(['message' => 'Koneksi gagal: ' . mysqli_connect_error()]);
    exit; // Stop script execution if connection fails
}

// Function to return the database connection
function getKoneksi() {
    global $koneksi; // Declare the global variable to access the connection
    return $koneksi; // Return the connection
}
?>