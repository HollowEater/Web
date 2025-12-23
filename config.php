<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'null_eater_store');
define('DB_CHARSET', 'utf8mb4');

// KẾT NỐI MYSQL
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

// HÀM HỖ TRỢ

/* Làm sạch dữ liệu đầu vào */
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $conn->real_escape_string($data);
}


function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . 'đ';
}


function format_date($date) {
    return date('d/m/Y', strtotime($date));
}


function format_datetime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

/* Chuyển đổi giá từ string sang số*/
function price_to_number($price_string) {
    return intval(str_replace(['.', 'đ', ' '], '', $price_string));
}

/* Chuyển đổi số sang định dạng giá */
function number_to_price($number) {
    return number_format($number, 0, ',', '.') . 'đ';
}

/* Tạo slug từ tiêu đề (cho SEO friendly URL) */
function create_slug($string) {
    // Chuyển đổi tiếng Việt
    $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $string);
    $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
    $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
    $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
    $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
    $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
    $string = preg_replace("/(đ)/", 'd', $string);
    
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/* Kiểm tra user đã đăng nhập chưa*/
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

/* Kiểm tra user có phải admin không*/
function check_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
        header("Location: index.php");
        exit();
    }
}

/*Lấy số lượng sản phẩm trong giỏ hàng*/
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

/*Tạo mã đơn hàng ngẫu nhiên*/
function generate_order_code() {
    $year = date('Y');
    $random = rand(1000, 9999);
    return "DH{$year}{$random}";
}

/* Gửi email*/
function send_email($to, $subject, $message) {
    return true;
}

/* Upload hình ảnh*/
function upload_image($file, $target_dir = 'imginstock/') {
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Kiểm tra file có phải ảnh không
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["success" => false, "message" => "File không phải là hình ảnh."];
    }
    
    // Kiểm tra kích thước file (giới hạn 5MB)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "File quá lớn. Tối đa 5MB."];
    }
    
    // Chỉ cho phép một số định dạng nhất định
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        return ["success" => false, "message" => "Chỉ cho phép JPG, JPEG, PNG & GIF."];
    }
    
    // Đổi tên file để tránh trùng lặp
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $new_filename];
    } else {
        return ["success" => false, "message" => "Có lỗi khi upload file."];
    }
}

/*Phân trang */
function paginate($total_records, $records_per_page, $current_page) {
    $total_pages = ceil($total_records / $records_per_page);
    $offset = ($current_page - 1) * $records_per_page;
    
    return [
        'total_pages' => $total_pages,
        'offset' => $offset,
        'current_page' => $current_page
    ];
}

/*Tính phần trăm giảm giá*/
function calculate_discount_percent($original_price, $sale_price) {
    $original = price_to_number($original_price);
    $sale = price_to_number($sale_price);
    
    if ($original == 0) return 0;
    
    $discount = (($original - $sale) / $original) * 100;
    return round($discount);
}

/* Kiểm tra sản phẩm còn hàng không*/
function check_stock($product_id) {
    global $conn;
    
    $sql = "SELECT so_luong_ton FROM san_pham WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['so_luong_ton'] > 0;
}

/* Lấy thông tin sản phẩm theo ID */
function get_product_by_id($product_id) {
    global $conn;
    
    $sql = "SELECT * FROM san_pham WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/* Cập nhật số lượng tồn kho*/
function update_stock($product_id, $quantity, $operation = 'decrease') {
    global $conn;
    
    if ($operation == 'decrease') {
        $sql = "UPDATE san_pham SET so_luong_ton = so_luong_ton - ? WHERE id = ?";
    } else {
        $sql = "UPDATE san_pham SET so_luong_ton = so_luong_ton + ? WHERE id = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $product_id);
    return $stmt->execute();
}

/*Ghi log hoạt động (cho admin)*/
function log_activity($user_id, $action, $description) {
    global $conn;
    error_log("User $user_id: $action - $description");
}

/*Làm sạch chuỗi tìm kiếm */
function clean_search_string($string) {
    $string = trim($string);
    $string = strip_tags($string);
    $string = htmlspecialchars($string);
    return $string;
}

// KHỞI TẠO SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CÁC HẰNG SỐ THƯỜNG DÙNG

define('SITE_NAME', 'Null Eater Store');
define('SITE_URL', 'http://localhost/null_eater_store/');
define('ADMIN_EMAIL', 'admin@nulleater.com');
define('ITEMS_PER_PAGE', 12);
define('UPLOAD_MAX_SIZE', 5242880); // 5MB

// ERROR REPORTING (Chỉ bật khi development)

// Comment dòng dưới khi đã deploy production
error_reporting(0);
ini_set('display_errors', 0);

?>