<?php
/**
 * FILE CẤU HÌNH KẾT NỐI DATABASE
 * NULL EATER STORE - WEBSITE BÁN FIGURE
 */

// =============================================
// THÔNG TIN KẾT NỐI DATABASE
// =============================================

define('DB_HOST', 'localhost');        // Địa chỉ máy chủ database
define('DB_USER', 'root');             // Tên đăng nhập MySQL
define('DB_PASS', '');                 // Mật khẩu MySQL (để trống nếu dùng XAMPP/WAMP)
define('DB_NAME', 'null_eater_store'); // Tên database
define('DB_CHARSET', 'utf8mb4');       // Mã hóa ký tự

// =============================================
// KẾT NỐI MYSQLI
// =============================================

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        throw new Exception("Kết nối database thất bại: " . $conn->connect_error);
    }
    
    // Set charset UTF-8
    if (!$conn->set_charset(DB_CHARSET)) {
        throw new Exception("Lỗi khi thiết lập charset UTF-8: " . $conn->error);
    }
    
    // Thiết lập timezone
    $conn->query("SET time_zone = '+07:00'");
    
} catch (Exception $e) {
    // Ghi log lỗi
    error_log($e->getMessage());
    
    // Hiển thị thông báo lỗi cho người dùng
    die("Không thể kết nối database. Vui lòng thử lại sau.");
}

// =============================================
// HÀM HỖ TRỢ
// =============================================

/**
 * Làm sạch dữ liệu đầu vào để tránh SQL Injection
 */
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

/**
 * Format giá tiền VNĐ
 */
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . 'đ';
}

/**
 * Format ngày tháng
 */
function format_date($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Format ngày giờ
 */
function format_datetime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

/**
 * Chuyển đổi giá từ string sang số
 * VD: "9.900.000đ" => 9900000
 */
function price_to_number($price_string) {
    return intval(str_replace(['.', 'đ', ' '], '', $price_string));
}

/**
 * Tạo slug từ tiêu đề
 */
function create_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Kiểm tra user đã đăng nhập chưa
 */
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Kiểm tra user có phải admin không
 */
function check_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
        header("Location: index.php");
        exit();
    }
}

/**
 * Lấy thông tin giỏ hàng
 */
function get_cart_count() {
    global $conn;
    
    if (!isset($_SESSION['user_id'])) {
        return 0;
    }
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(so_luong) as total FROM gio_hang WHERE id_nguoi_dung = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] ?? 0;
}

// =============================================
// KHỞI TẠO SESSION
// =============================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>