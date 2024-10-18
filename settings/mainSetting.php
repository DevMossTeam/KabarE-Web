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
    case 'riwayat':
        include 'riwayat.php';
        break;
    case 'bantuan':
        include 'bantuan/pusat_bantuan.php';
        break;
    case 'hubungi':
        include 'bantuan/hubungi_kami.php';
        break;
    case 'tentang':
        include 'tentang/appAbout.php';
        break;
    case 'app':
        include 'tentang/appAbout.php';
        break;
    case 'license':
        include 'tentang/appLicense.php';
        break;
    default:
        include 'umum.php';
        break;
}
?>
