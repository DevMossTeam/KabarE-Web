<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';


$queryBulletin = "SELECT * FROM `newsletter`";
$stmtBulletin = $koneksi->prepare($queryBulletin); 
$stmtBulletin->execute();  
$resultBulletin = $stmtBulletin->get_result();  

// Initialize an array to hold all rows
$dataBulletin = [];

// Loop through all rows and add them to the array
while ($row = $resultBulletin->fetch_assoc()) {
    // Process the footer content
    $htmlContent = $row['footer_content'] ?? '';
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlContent);
    libxml_clear_errors();

    // Extract content paragraphs
    $footerContent = '';
    foreach ($dom->getElementsByTagName('p') as $p) {
        $footerContent .= $p->textContent . "\n";
    }

    // Add the processed data to the array
    $dataBulletin[] = [
        'id' => $row['id'] ?? '',
        'judul_bulletin' => $row['judul_bulletin'] ?? '',
        'status' => $row['status'] ?? '',
        'tipe_content' => $row['tipe_content'] ?? '',
        'kategori' => $row['kategori'] ?? '',
        'footer_content' => $footerContent,
        'hari_pengiriman' => $row['hari_pengiriman'] ?? '',
        'jam_pengiriman' => $row['jam_pengiriman'] ?? '',
        'jumlah_berita' => $row['jumlah_berita'] ?? 0
    ];
}

echo "<script>";
echo "console.log('Data Bulletin:', " . json_encode($dataBulletin) . ");";
echo "</script>";


$query = "SELECT 
                berita.*, 
                user.nama_pengguna,                                                                         
                COUNT(CASE WHEN reaksi.jenis_reaksi = 'Suka' THEN 1 END) AS suka_count, 
                COUNT(CASE WHEN reaksi.jenis_reaksi = 'Tidak Suka' THEN 1 END) AS bukan_suka_count 
            FROM 
                berita 
            JOIN 
                user ON berita.user_id = user.uid 
            LEFT JOIN 
                reaksi ON reaksi.berita_id = berita.id 
            GROUP BY 
                berita.id, user.uid;";

$stmt = $koneksi->prepare($query); 
$stmt->execute();  
$result = $stmt->get_result();  

// Initialize an array to hold all rows
$dataBeritaNews = [];

// Loop through all rows and add them to the array
while ($row = $result->fetch_assoc()) {
    // Process the content of the article (konten_artikel)
    $htmlContent = $row['konten_artikel'] ?? '';
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlContent);
    libxml_clear_errors();

    // Extract content paragraphs
    $content = '';
    foreach ($dom->getElementsByTagName('p') as $p) {
        $content .= $p->textContent . "\n";
    }

    // Add the processed data to the array
    $dataBeritaNews[] = [
        'id' => $row['id'] ?? '',
        'judul' => $row['judul'] ?? '',
        'tanggal_diterbitkan' => $row['tanggal_diterbitkan'] ?? '',
        'view_count' => $row['view_count'] ?? 0,
        'user_id' => $row['user_id'] ?? '',
        'nama_pengguna' => $row['nama_pengguna'] ?? '',
        'email' => $row['email'] ?? '',
        'kategori' => $row['kategori'] ?? '',
        'konten_artikel' => $content,
        'visibilitas' => $row['visibilitas'] ?? '',
        'suka_count' => $row['suka_count'] ?? 0
    ];
}

// Log the processed data to the JavaScript console
echo "<script>console.log('Query Result:', " . json_encode($dataBeritaNews) . ");</script>";

$stmt->close();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$apiUrl = "http://localhost/KabarE-Web/api/user.php";

$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    die('Error occurred while fetching data');
}

$dataEmail = json_decode($response, true);

if (empty($dataEmail['data'])) {
    echo "No data found.";
    exit;
}

echo '<script>';
echo 'console.log(' . json_encode(array_column($dataEmail['data'], 'email')) . ');';
echo '</script>';

// Function to fetch all users from the database


function sendEmail($recipientEmail, $subject, $htmlMessage)
{
    $senderEmail = 'ardianthauno@gmail.com'; // Ganti dengan email pengirim Anda
    $senderName = 'KabarE'; // Ganti dengan nama pengirim Anda
    $senderPassword = 'phok infp xkkp rvwy'; // Ganti dengan password atau App Password pengirim

    // Buat objek PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $senderEmail;
        $mail->Password = $senderPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlMessage;

        // Send email
        $mail->send();
        return 'Message has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailAddresses = array_column($dataEmail['data'], 'email');
    $subject = "TEST KOMPONEN BARU ubah Here"; // Subject email

    // Start the message with the initial HTML structure
    $message = '
            <!DOCTYPE html>
            <html>
            <body style="background-color: #f7fafc; font-family: Arial, sans-serif; margin: 0; padding: 0;">
                <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f7fafc; margin: 0; padding: 0;">
                    <tr>
                        <td align="center">
                            <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; padding: 20px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                                <tr>
                                    <td align="center" style="padding: 5px;">
                                        <h1 style="font-size: 24px; color: #3b82f6; font-weight: bold; margin: 0;">KabarE</h1>
                                        <p style="color: #6b7280; font-size: 14px; margin: 4px 0;">Stories from your recommendation</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #e2e8f0; padding: 6px 0;"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h2 style="color: #4a5568; font-size: 24px; font-weight: 600;">Weekly Tech Update</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
                                </tr>';

            // Limit to first 5 items
            $dataBeritaNews = array_slice($dataBeritaNews, 0, 5);
            
            // Loop over the processed data to generate the email content dynamically
            foreach ($dataBeritaNews as $item) {
                $judul = htmlspecialchars($item['judul']);
                $namaPengguna = htmlspecialchars($item['nama_pengguna']);
                // $email = htmlspecialchars($item['email']);
                // $profilePic = htmlspecialchars($item['profile_pic']);
                $kategori = htmlspecialchars($item['kategori']);
                $kontenArtikel = htmlspecialchars($item['konten_artikel']);
                // $images = $item['images'] ?? [];
                // $mainImage = !empty($images) ? htmlspecialchars($images[0]) : 'default-image.jpg';
                $view_count = htmlspecialchars($item['view_count'] ?? 0);
                $suka_count = htmlspecialchars($item['suka_count'] ?? 0);
                // $bukan_suka_count = htmlspecialchars($item['bukan_suka_count'] ?? 0); 

                // Add the dynamic content to the message
                $message .= '
                <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 10px 0;">
                    <tr>
                        <td width="50%" valign="top">
                            <table width="100%" cellspacing="0" cellpadding="0" style="padding-right: 16px;">
                                <tr>
                                    <td style="padding: 0; text-align: left;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="color: #4a5568; font-weight: 500; font-size: 12px;">
                                                    '. $namaPengguna .'
                                                </td>
                                                <td style="text-align: right; padding-right: 6px;">
                                                    <p style="color: white; font-weight: 500; font-size: 12px; margin-left: 6px; background-color: red; border-radius: 5px; padding: 4px; display: inline;">
                                                    '. $kategori .'    
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <a href="../category/news-detail.php?id=' . $item['id']  . '" style="text-decoration: none; color: inherit;">
                                            <h3 style="font-size: 18px; font-weight: bold; color: #1a202c; margin: 8px 0;">
                                                ' . substr($judul, 0, 300) . '
                                            </h3>
                                            <p style="color: #a0aec0; font-size: 16px; margin: 4px 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                                ' .  substr($kontenArtikel, 0, 180) . '...' . '
                                            </p>
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="margin-top: 16px; display: flex; align-items: center;">
                                        <img src="https://icons.veryicon.com/png/o/miscellaneous/streamline-bold-icon/view-11.png" alt="Likes" style="width: 20px; height: 20px;">
                                        <div style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">
                                            ' . $view_count . '
                                        </div>
                                        <img src="https://icons.veryicon.com/png/o/miscellaneous/yuanql/icon-like.png" alt="Likes" style="width: 16px; height: 16px; margin-left: 20px;">
                                        <div style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">
                                            ' . $suka_count . '
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
        </tr>';
    }

    // Close the HTML structure
    $message .= '
            <tr>
                <td align="center">
                    <p style="color: #a0aec0; font-size: 12px; margin-top: 24px;">Artikel ini membahas perkembangan terbaru dalam teknologi.</p>
                </td>
            </tr>
        </table>
        <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #101827; padding: 20px;">
            <tr>
                <td align="center" style="color: #ffffff; font-size: 16px; margin-bottom: 16px;">Baca Dimana saja.</td>
            </tr>
            <tr>
                <td align="center">
                    <img src="https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png?hl=id" alt="Play Store Badge" style="height: 50px; width: auto;">
                </td>
            </tr>
            <tr>
                <td style="border-top: 1px solid #e2e8f0; padding: 16px 0;"></td>
            </tr>
            <tr>
                <td align="center" style="color: #a0aec0; font-size: 12px; margin-bottom: 20px;">© 2024 KabarE. All Rights Reserved.</td>
            </tr>
        </table>
    </body>
    </html>';

    // Loop through each email address and send the email
    foreach ($emailAddresses as $email) {
        sendEmail($email, $subject, $message);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
</head>

<body>
    <form action="pengirimanNewsletter.php" method="POST">
        <button type="submit">Send Email</button>
    </form>
</body>
</html>
