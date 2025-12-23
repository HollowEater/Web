<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
        'require_login' => true
    ]);
    exit();
}

// Kiểm tra dữ liệu POST
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu thông tin sản phẩm!'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Kiểm tra số lượng hợp lệ
if ($quantity < 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Số lượng không hợp lệ!'
    ]);
    exit();
}

try {
    // Kiểm tra sản phẩm tồn tại
    $check_product = "SELECT id, ten, gia, so_luong_ton FROM san_pham WHERE id = ?";
    $stmt_check = $conn->prepare($check_product);
    $stmt_check->bind_param("i", $product_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại!'
        ]);
        exit();
    }
    
    $product = $result->fetch_assoc();
    
    // Kiểm tra số lượng tồn kho
    if ($product['so_luong_ton'] < $quantity) {
        echo json_encode([
            'success' => false,
            'message' => 'Không đủ số lượng trong kho!'
        ]);
        exit();
    }
    
    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    $check_cart = "SELECT id, so_luong FROM gio_hang WHERE id_nguoi_dung = ? AND id_san_pham = ?";
    $stmt_cart = $conn->prepare($check_cart);
    $stmt_cart->bind_param("ii", $user_id, $product_id);
    $stmt_cart->execute();
    $cart_result = $stmt_cart->get_result();
    
    if ($cart_result->num_rows > 0) {
        // Sản phẩm đã có trong giỏ -> Cập nhật số lượng
        $cart_item = $cart_result->fetch_assoc();
        $new_quantity = $cart_item['so_luong'] + $quantity;
        
        // Kiểm tra số lượng mới có vượt quá tồn kho không
        if ($new_quantity > $product['so_luong_ton']) {
            echo json_encode([
                'success' => false,
                'message' => 'Số lượng trong giỏ hàng vượt quá số lượng tồn kho!'
            ]);
            exit();
        }
        
        $update_sql = "UPDATE gio_hang SET so_luong = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ii", $new_quantity, $cart_item['id']);
        $stmt_update->execute();
        
        $message = "Đã cập nhật số lượng sản phẩm trong giỏ hàng!";
    } else {
        // Thêm sản phẩm mới vào giỏ
        $insert_sql = "INSERT INTO gio_hang (id_nguoi_dung, id_san_pham, so_luong) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt_insert->execute();
        
        $message = "Đã thêm sản phẩm vào giỏ hàng!";
    }
    
    // Lấy tổng số lượng sản phẩm trong giỏ hàng
    $count_sql = "SELECT SUM(so_luong) as total FROM gio_hang WHERE id_nguoi_dung = ?";
    $stmt_count = $conn->prepare($count_sql);
    $stmt_count->bind_param("i", $user_id);
    $stmt_count->execute();
    $count_result = $stmt_count->get_result();
    $cart_count = $count_result->fetch_assoc()['total'] ?? 0;
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'cart_count' => $cart_count
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
}
?>