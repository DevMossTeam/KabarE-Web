<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'umum';

switch ($page) {
    case 'umum':
        include 'umum.php';
        break;
    case 'notifikasi':
        include 'notifikasi.php';
        break;
    case 'keamanan':
        include 'keamanan.php';
        break;
    default:
        include 'umum.php';
        break;
}
?>
