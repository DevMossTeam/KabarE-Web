<?php
// Memulai session untuk mendapatkan user_id
session_start();
header('Content-Type: application/json');

class FeedbackHandler {
    private $conn;
    private $table = "pesan";

    public function __construct($dbConfig) {
        $this->connect($dbConfig);
    }

    // Koneksi database
    private function connect($dbConfig) {
        $this->conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['database']);

        if ($this->conn->connect_error) {
            die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $this->conn->connect_error]));
        }
    }

    // Tutup koneksi
    public function __destruct() {
        $this->conn->close();
    }

    // Validasi input
    private function validateInput($pesan, $user_id) {
        if (empty($pesan)) {
            return ['status' => 'error', 'message' => 'Pesan tidak boleh kosong!'];
        }
        if (empty($user_id)) {
            return ['status' => 'error', 'message' => 'Pengguna tidak terdeteksi.'];
        }
        return null;
    }

    // Generate ID acak
    private function generateRandomId($length = 12) {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
    }

    // Simpan pesan ke database
    public function saveMessage($user_id, $pesan) {
        $validationError = $this->validateInput($pesan, $user_id);
        if ($validationError) {
            return $validationError;
        }

        $id = $this->generateRandomId();
        $status_read = "belum";
        $status = "masukan";
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO {$this->table} (id, user_id, pesan, created_at, status_read, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ssssss', $id, $user_id, $pesan, $created_at, $status_read, $status);
            if ($stmt->execute()) {
                $stmt->close();
                return ['status' => 'success', 'message' => 'Pesan berhasil dikirim!'];
            } else {
                $stmt->close();
                return ['status' => 'error', 'message' => 'Gagal mengirim pesan.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Query gagal: ' . $this->conn->error];
        }
    }
}

// Konfigurasi database
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'kabare_db'
];

// Cek apakah ada data POST yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pesan = isset($_POST['message']) ? $_POST['message'] : null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Inisialisasi handler
    $feedbackHandler = new FeedbackHandler($dbConfig);

    // Simpan pesan dan tampilkan respons
    $response = $feedbackHandler->saveMessage($user_id, $pesan);
    echo json_encode($response);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diperbolehkan.']);
}
?>
