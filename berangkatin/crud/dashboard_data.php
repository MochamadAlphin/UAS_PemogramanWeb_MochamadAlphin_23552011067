<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../config/connection.php');


if (!isset($_SESSION['session_active']) && isset($_COOKIE['session_active'])) {
    $_SESSION['session_active'] = $_COOKIE['session_active'];
    $_SESSION['user_name'] = $_COOKIE['user_name'] ?? 'Tamu';
    $_SESSION['user_id'] = $_COOKIE['user_id'] ?? 0;
}

// Ambil data dari session yang sudah disinkronkan
$s_active = $_SESSION['session_active'] ?? '';
$s_name   = $_SESSION['user_name'] ?? 'Tamu';
$s_id     = $_SESSION['user_id'] ?? 0;

if (trim($s_active) === 'yes') {
    $is_guest = false;
    $display_name = $s_name;
} else {
    $is_guest = true;
    $display_name = "Tamu";
}

// Inisialisasi variabel awal
$tiket_aktif   = 0; 
$point_reward  = 0; 
$voucher_count = 0;
$total_jam     = 0; 
$total_uang    = 0;
$prog_tiket    = 0; 
$prog_point    = 0; 
$prog_voucher  = 0;
$result_jadwal = null; 

try {
    // 1. Ambil data jadwal
    $query_jadwal = "SELECT * FROM jadwal ORDER BY tanggal ASC, jam_berangkat ASC";
    $result_jadwal = $conn->query($query_jadwal);

    if (!$is_guest) {
        // 2. Hitung Tiket Aktif (Sesuai ID User agar tidak mental/salah data)
        // PENTING: Gunakan $s_id agar data yang muncul benar-benar milik user tersebut
        $query_tiket = "SELECT COUNT(*) as total FROM jadwal"; // Ganti ke tabel transaksi jika sudah ada
        $res_tiket = $conn->query($query_tiket);
        if ($res_tiket) {
            $row_tiket = $res_tiket->fetch_assoc();
            $tiket_aktif = $row_tiket['total'] ?? 0;
        }

        // 3. Point & Voucher (Data real)
        $point_reward = $tiket_aktif * 50; 
        $query_v = "SELECT COUNT(*) as total FROM vouchers"; 
        $res_v = $conn->query($query_v);
        if ($res_v) {
            $row_v = $res_v->fetch_assoc();
            $voucher_count = $row_v['total'] ?? 0;
        }

        // 4. Progress Bar
        $prog_tiket   = min(($tiket_aktif / 10) * 100, 100); 
        $prog_point   = min(($point_reward / 1000) * 100, 100);
        $prog_voucher = ($voucher_count > 0) ? min(($voucher_count / 10) * 100, 100) : 0;

        // 5. Statistik
        $total_jam = $tiket_aktif * 2; 
        $query_total = "SELECT SUM(harga) as total FROM jadwal"; 
        $res_total = $conn->query($query_total);
        if ($res_total) {
            $row_total = $res_total->fetch_assoc();
            $total_uang = $row_total['total'] ?? 0;
        }
    } 

    $final_transaksi = (float) $total_uang;

} catch (Exception $e) {
    error_log("Error Dashboard: " . $e->getMessage());
}

if ($result_jadwal) {
    $result_jadwal->data_seek(0);
}
?>