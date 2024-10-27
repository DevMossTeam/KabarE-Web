<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));

        // Periksa apakah file adalah gambar
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        // Batasi ukuran file
        if ($_FILES["profile_pic"]["size"] > 16000000) { // Batas untuk MEDIUMBLOB adalah 16MB
            echo "Maaf, file Anda terlalu besar.";
            $uploadOk = 0;
        }

        // Batasi format file
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Periksa jika $uploadOk adalah 0
        if ($uploadOk == 0) {
            echo "Maaf, file Anda tidak terunggah.";
        } else {
            // Baca file ke dalam variabel
            $fileData = file_get_contents($_FILES["profile_pic"]["tmp_name"]);

            // Simpan data biner ke database
            $stmt = $conn->prepare("UPDATE user SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("bi", $fileData, $user_id); // "b" untuk data biner
            if ($stmt->execute()) {
                $_SESSION['profile_pic'] = $fileData;
                header("Location: umum.php"); // Redirect ke halaman umum.php setelah berhasil
                exit();
            } else {
                echo "Terjadi kesalahan saat menyimpan ke database.";
            }
            $stmt->close();
        }
    } else {
        echo "User tidak ditemukan.";
    }
} else {
    echo "Tidak ada file yang diunggah.";
}
?>
