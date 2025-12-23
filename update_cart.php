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
if (!isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu thông tin!'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);
$new_quantity = intval($_POST['quantity']);

// Kiểm tra số lượng hợp lệ
if ($new_quantity < 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Số lượng tối thiểu là 1!'
    ]);
    exit();
}

try {
    // Kiểm tra giỏ hàng thuộc về user hiện tại
    $check_sql = "SELECT gh.id, gh.id_san_pham, sp.so_luong_ton 
                  FROM gio_hang gh 
                  INNER JOIN san_pham sp ON gh.id_san_pham = sp.id 
                  WHERE gh.id = ? AND gh.id_nguoi_dung = ?";
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
    
    $cart_item = $result->fetch_assoc();
    
    // Kiểm tra số lượng tồn kho
    if ($new_quantity > $cart_item['so_luong_ton']) {
        echo json_encode([
            'success' => false,
            'message' => 'Số lượng vượt quá tồn kho!'
        ]);
        exit();
    }
    
    // Cập nhật số lượng
    $update_sql = "UPDATE gio_hang SET so_luong = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("ii", $new_quantity, $cart_id);
    
    if ($stmt_update->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã cập nhật số lượng!'
        ]);
    } else {
        throw new Exception('Lỗi khi cập nhật!');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}
?>