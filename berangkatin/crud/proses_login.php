<?php
// Gunakan ob_start agar tidak ada output sebelum header dikirim
ob_start();

// 1. Pastikan session dimulai dengan parameter aman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Matikan display errors agar tidak merusak format JSON
ini_set('display_errors', 0);
error_reporting(E_ALL); 

header('Content-Type: application/json');

try {
    require_once '../config/connection.php';
    
    // Bersihkan buffer agar output benar-benar hanya JSON
    if (ob_get_length()) ob_clean();

    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    // LOGIC GUEST
    if ($email === 'guest@berangkatin.com' || (empty($email) && empty($pass))) {
        session_unset(); 

        $_SESSION['session_active'] = 'no';
        $_SESSION['user_name'] = 'Tamu';
        $_SESSION['user_id'] = 0;
        
        // Simpan ke Cookie dengan path '/' agar terbaca di semua folder
        setcookie("session_active", "no", time() + 3600, "/", "", false, true);
        setcookie("user_name", "Tamu", time() + 3600, "/", "", false, true);

        echo json_encode([
            "status" => "success", 
            "message" => "Masuk sebagai Tamu", 
            "target" => "dashboard.php"
        ]);
        exit;
    }

    // LOGIC MEMBER
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Cek password (mendukung hash password_verify atau plain text untuk testing)
        if (password_verify($pass, $user['password']) || $pass === $user['password']) {
            
            // Bersihkan session lama
            session_unset();
            
            // Set Session Baru
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_name'] = $user['nama'];
            $_SESSION['session_active'] = 'yes';

            // Set Cookie Baru (Gunakan HttpOnly agar lebih aman dan stabil di hosting)
            $expiry = time() + 3600;
            setcookie("session_active", "yes", $expiry, "/", "", false, true);
            setcookie("user_name", $user['nama'], $expiry, "/", "", false, true);
            setcookie("user_id", $user['id_user'], $expiry, "/", "", false, true);

            // Pastikan session tersimpan ke disk/database hosting sebelum respons dikirim
            session_write_close();

            echo json_encode([
                "status" => "success", 
                "message" => "Selamat datang " . $user['nama'],
                "target" => "dashboard.php"
            ]);
            exit;
        }
    }

    echo json_encode(["status" => "error", "message" => "Email atau Password salah"]);

} catch (Exception $e) {
    if (ob_get_length()) ob_clean();
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan server: " . $e->getMessage()]);
}
ob_end_flush();
exit;