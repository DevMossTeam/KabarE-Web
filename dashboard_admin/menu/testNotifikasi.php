<?php
function NotfikasiCreate() {
    $url = 'http://localhost/KabarE-Web/api/notifikasi.php';
    $komenId = null;
    $fromUserId = null;
    $tipeNotif = null;
    $pilihtipe = null;

    switch ($pilihtipe)
    {
        case "dislike";
        $tipeNotif = "dislike";
        break;
        case "like";
        $tipeNotif = "like";
        break;
        case "komen";
        $tipeNotif = "komen";    
        break;
    }
}
?>