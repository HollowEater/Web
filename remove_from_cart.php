<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập!'
    ]);
    exit();
}

// Kiểm tra dữ liệu POST
if (!isset($_POST['cart_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu thông tin!'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);

try {
    // Kiểm tra giỏ hàng thuộc về user hiện tại
    $check_sql = "SELECT id FROM gio_hang WHERE id = ? AND id_nguoi_dung = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $cart_id, $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'
        ]);
        exit();
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    $delete_sql = "DELETE FROM gio_hang WHERE id = ?";
    $stmt_delete = $conn->prepare($delete_sql);
    $stmt_delete->bind_param("i", $cart_id);
    
    if ($stmt_delete->execute()) {
        // Lấy tổng số lượng sản phẩm còn lại trong giỏ hàng
        $count_sql = "SELECT SUM(so_luong) as total FROM gio_hang WHERE id_nguoi_dung = ?";
        $stmt_count = $conn->prepare($count_sql);
        $stmt_count->bind_param("i", $user_id);
        $stmt_count->execute();
        $count_result = $stmt_count->get_result();
        $cart_count = $count_result->fetch_assoc()['total'] ?? 0;
        
        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
            'cart_count' => $cart_count
        ]);
    } else {
        throw new Exception('Lỗi khi xóa!');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}
?>