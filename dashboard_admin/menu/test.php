<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

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
    $subject = "Test ubah Here"; // Subject email
    $message = '
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KabarE</title>
</head>

<body style="background-color: #f7fafc; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"
        style="background-color: #f7fafc; margin: 0; padding: 0;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0"
                    style="background-color: #ffffff; padding: 20px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 5px;">
                            <h1 style="font-size: 24px; color: #3b82f6; font-weight: bold; margin: 0;">KabarE</h1>
                            <p style="color: #6b7280; font-size: 14px; margin: 4px 0;">Stories from your recommendation
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0;"></td>
                    </tr>
                    <tr>
                        <td>
                            <h2 style="color: #4a5568; font-size: 24px; font-weight: 600;">Weekly Tech Update</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 10px 0;">
                                <tr>
                                    <td width="50%" valign="top">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                            style="padding-right: 16px;">
                                            <tr>
                                                <td
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div style="display: flex; align-items: center;">
                                                        <img src="https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp"
                                                            alt="Profile Picture"
                                                            style="border-radius: 50%; width: 40px; height: 40px; object-fit: cover;">
                                                        <p
                                                            style="color: #4a5568; font-weight: 500; font-size: 14px; margin-left: 8px;">
                                                            Satria Ardiantha Uno</p>
                                                    </div>                                         
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="http://localhost/KabarE-Web/category/news-detail.php?="
                                                        style="text-decoration: none; color: inherit;">
                                                        <h3
                                                            style="font-size: 16px; font-weight: bold; color: #1a202c; margin: 8px 0;">
                                                            Breaking News: AI</h3>
                                                    </a>
                                                    <p style="color: #a0aec0; font-size: 12px; margin: 4px 0;">Artikel
                                                        ini membahas perkembangan terbaru dalam teknologi.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin-top: 16px;">
                                                    <img src="https://icons.veryicon.com/png/o/miscellaneous/yuanql/icon-like.png"
                                                        alt="Likes" style="width: 20px; height: 20px;">
                                                    <span
                                                        style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">32</span>
                                                    <img src="https://img.icons8.com/ios_filled/200/speech-bubble.png"
                                                        alt="Comments"
                                                        style="width: 20px; height: 20px; margin-left: 16px;">
                                                    <span
                                                        style="color: #4a5568; font-size: 14px; font-weight: 500; margin-left: 4px;">45</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" align="center">
                                        <a href="http://localhost/KabarE-Web/category/news-detail.php?=" style="">
                                            <img src="https://via.placeholder.com/400x300" alt="Breaking News Image"
                                                style="max-width: 100%; height: auto; object-fit: contain; border-radius: 8px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 4px 0;"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p style="color: #a0aec0; font-size: 12px; margin-top: 24px;">Artikel ini membahas
                                perkembangan terbaru dalam teknologi.</p>
                        </td>
                    </tr>
                </table>
                <table width="600" border="0" cellspacing="0" cellpadding="0"
                    style="background-color: #101827; padding: 20px;">
                    <tr>
                        <td align="center" style="color: #ffffff; font-size: 16px; margin-bottom: 16px;">Baca Dimana
                            saja.</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <img src="https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png?hl=id"
                                alt="Play Store Badge" style="height: 50px; width: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #e2e8f0; padding: 16px 0;"></td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #a0aec0; font-size: 12px; margin-bottom: 20px;">
                            © 2024 KabarE. All Rights Reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
    ';
    $results = [];
    foreach ($emailAddresses as $email) {
        $result = sendEmail($email, $subject, $message);
        $results[] = "Email to {$email}: {$result}";
    }
    echo $result;
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
    <form action="test.php" method="POST">
        <button type="submit">Send Email</button>
    </form>
</body>
</html>
