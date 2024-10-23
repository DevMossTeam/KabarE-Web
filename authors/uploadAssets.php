<?php
   // authors/uploadAssets.php

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       if (isset($_FILES['upload'])) {
           $file = $_FILES['upload'];
           $uploadDirectory = 'uploads/'; // Pastikan direktori ini ada dan dapat ditulis

           // Buat nama file unik
           $fileName = uniqid() . '-' . basename($file['name']);
           $uploadPath = $uploadDirectory . $fileName;

           // Pindahkan file yang diunggah ke direktori tujuan
           if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
               // Kirim respons JSON yang diharapkan oleh CKEditor
               echo json_encode([
                   'uploaded' => true,
                   'url' => '/' . $uploadPath
               ]);
           } else {
               echo json_encode([
                   'uploaded' => false,
                   'error' => ['message' => 'Failed to move uploaded file.']
               ]);
           }
       } else {
           echo json_encode([
               'uploaded' => false,
               'error' => ['message' => 'No file uploaded.']
           ]);
       }
   } else {
       echo json_encode([
           'uploaded' => false,
           'error' => ['message' => 'Invalid request method.']
       ]);
   }
   ?>