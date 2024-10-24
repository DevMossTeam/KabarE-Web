<?php
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Hapus cookie yang terkait dengan login
setcookie('user_id', '', time() - 3600, '/');
setcookie('email', '', time() - 3600, '/');

// Redirect ke halaman login
header("Location: ../user-auth/login.php");
exit();
?>
