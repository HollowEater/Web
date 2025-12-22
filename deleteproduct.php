<?php
session_start();

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

// Kiểm tra có ID sản phẩm không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['thong_bao'] = "Không tìm thấy ID sản phẩm!";
    $_SESSION['loai_thong_bao'] = "error";
    header("Location: admin.php");
    exit();
}

$product_id = intval($_GET['id']);

try {
    // Lấy thông tin sản phẩm trước khi xóa
    $sql_get = "SELECT ten, hinh FROM san_pham WHERE id = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $product_id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Không tìm thấy sản phẩm với ID: $product_id");
    }
    
    $product = $result->fetch_assoc();
    $product_name = $product['ten'];
    $product_image = $product['hinh'];
    
    // Xóa sản phẩm khỏi database
    $sql_delete = "DELETE FROM san_pham WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $product_id);
    
    if ($stmt_delete->execute()) {
        // Xóa file hình ảnh nếu tồn tại (tùy chọn)
        $image_path = "imginstock/" . $product_image;
        if (file_exists($image_path)) {
            // Không bắt buộc phải xóa file, có thể giữ lại cho lịch sử
            // unlink($image_path);
        }
        
        $_SESSION['thong_bao'] = "Đã xóa sản phẩm thành công! ID: $product_id - Tên: $product_name";
        $_SESSION['loai_thong_bao'] = "success";
    } else {
        throw new Exception("Lỗi khi xóa sản phẩm: " . $stmt_delete->error);
    }
    
    $stmt_get->close();
    $stmt_delete->close();
    
} catch (Exception $e) {
    $_SESSION['thong_bao'] = $e->getMessage();
    $_SESSION['loai_thong_bao'] = "error";
}

$conn->close();

// Chuyển về trang admin
header("Location: admin.php");
exit();
?>