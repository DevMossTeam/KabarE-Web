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
echo json_encode($dataBeritaNews);

// Use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Fetch user emails from the API
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

// Function to send an email with HTML content
function sendEmail($recipientEmail, $subject, $htmlMessage)
{
    $senderEmail = 'ardianthauno@gmail.com'; 
    $senderName = 'KabarE'; 
    $senderPassword = 'phok infp xkkp rvwy'; 

    // Create a PHPMailer object
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

        // Send the email
        if ($mail->send()) {
            return "Message has been sent to " . $recipientEmail;
        } else {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each user and send newsletters
    foreach ($dataEmail['data'] as $user) {
        $email = $user['email'];
        
        // Loop through each newsletter and send it to the user (one email per newsletter)
        foreach ($dataBulletin as $index => $bulletin) {
            // Only send newsletters that are 'Aktif' (active)
            if ($bulletin['status'] == 'Aktif') {
                $subject = $bulletin['judul_bulletin']; // Subject of the newsletter
                $htmlMessage = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>' . $bulletin['judul_bulletin'] . '</title>
                </head>
                <body>
                    <h2>' . $bulletin['judul_bulletin'] . '</h2>
                    <p>' . $bulletin['footer_content'] . '</p>
                    <p><strong>Status:</strong> ' . $bulletin['status'] . '</p>
                    <p><strong>Kategori:</strong> ' . $bulletin['kategori'] . '</p>
                    <p><strong>Jumlah Berita:</strong> ' . $bulletin['jumlah_berita'] . '</p>
                    <p><strong>Hari Pengiriman:</strong> ' . $bulletin['hari_pengiriman'] . '</p>
                    <p><strong>Jam Pengiriman:</strong> ' . $bulletin['jam_pengiriman'] . '</p>
                </body>
                </html>';

                // Send email and log status
                $statusMessage = sendEmail($email, $subject, $htmlMessage);
                echo "<script>console.log('".$statusMessage."');</script>";

                // Remove the sent newsletter from the array so it is not sent again
                unset($dataBulletin[$index]);  // Remove the newsletter after sending
            }
        }
    }
    echo '<script>console.log("All active emails are sent!");</script>';
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
    <form action="newsletterPengiriman.php" method="POST">
        <button type="submit">Send All Newsletters</button>
    </form>
</body>
</html>
