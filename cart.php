<?php
session_start();
require_once 'config.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Xử lý Xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'del' && isset($_GET['id'])) {
    $id_cart = intval($_GET['id']);
    $conn->query("DELETE FROM gio_hang WHERE id = $id_cart AND id_nguoi_dung = $user_id");
    header("Location: cart.php"); // Load lại trang để cập nhật
    exit();
}

// Xử lý Tăng/Giảm số lượng
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_cart = intval($_GET['id']);
    
    // Lấy thông tin hiện tại để kiểm tra tồn kho
    $check = $conn->query("SELECT gh.so_luong, sp.so_luong_ton FROM gio_hang gh JOIN san_pham sp ON gh.id_san_pham = sp.id WHERE gh.id = $id_cart");
    
    if ($check->num_rows > 0) {
        $curr = $check->fetch_assoc();
        $sl_hien_tai = $curr['so_luong'];
        $ton_kho = $curr['so_luong_ton'];

        // Tăng
        if ($_GET['action'] == 'inc') {
            if ($sl_hien_tai < $ton_kho) {
                $conn->query("UPDATE gio_hang SET so_luong = so_luong + 1 WHERE id = $id_cart");
            } else {
                echo "<script>alert('Đã đạt giới hạn tồn kho!');</script>";
            }
        }
        // Giảm
        elseif ($_GET['action'] == 'dec') {
            if ($sl_hien_tai > 1) {
                $conn->query("UPDATE gio_hang SET so_luong = so_luong - 1 WHERE id = $id_cart");
            }
        }
        header("Location: cart.php");
        exit();
    }
}
// LẤY DỮ LIỆU GIỎ HÀNG ĐỂ HIỂN THỊ
$cart_items = [];
$total_amount = 0;

$sql = "SELECT gh.id as cart_id, gh.so_luong, sp.ten, sp.hinh, sp.gia, sp.gia_cu, sp.giam_gia, sp.so_luong_ton 
        FROM gio_hang gh 
        INNER JOIN san_pham sp ON gh.id_san_pham = sp.id 
        WHERE gh.id_nguoi_dung = ? ORDER BY gh.ngay_them DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $price = price_to_number($row['gia']);
    $row['subtotal'] = $price * $row['so_luong'];
    $total_amount += $row['subtotal'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <div style="max-width: 1200px; margin: 50px auto; display: flex; gap: 30px; padding: 0 20px;">

        <div style="width: 65%;">

            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #39c5bb; padding-bottom: 10px; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 24px; color: #39c5bb;">Giỏ hàng:</h2>
                <span style="color: #888;"><?php echo count($cart_items); ?> Sản phẩm</span>
            </div>

            <?php if (empty($cart_items)): ?>
                <div style="background: white; padding: 50px 20px; text-align: center; border: 1px solid #eee; border-radius: 5px;">
                    <p style="color: #333; margin-bottom: 20px;">
                        Giỏ hàng của bạn đang trống.
                        <a href="instock.php" style="color: #d32f2f; text-decoration: none; font-weight: bold;">Mua ngay</a>.
                    </p>
                </div>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                <div style="background: white; padding: 15px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 15px; display: flex; gap: 15px; align-items: center;">
                    
                    <div style="width: 100px; height: 100px; border: 1px solid #eee; border-radius: 5px; overflow: hidden;">
                        <img src="imginstock/<?php echo $item['hinh']; ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='img/no-image.jpg'">
                    </div>
                    
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #333;"><?php echo $item['ten']; ?></h3>
                        <div style="font-size: 13px; color: #888; margin-bottom: 10px;">
                            <?php if (!empty($item['giam_gia'])): ?>
                                <span style="background: #ff4757; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    <?php echo $item['giam_gia']; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div style="text-align: center; min-width: 150px;">
                        <div style="font-weight: bold; color: #e74c3c; font-size: 18px; margin-bottom: 10px;">
                            <?php echo $item['gia']; ?>
                        </div>
                        
                        <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                            <a href="cart.php?action=dec&id=<?php echo $item['cart_id']; ?>" 
                               style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #333; border-radius: 3px;">
                                <i class="fa-solid fa-minus"></i>
                            </a>

                            <input type="text" value="<?php echo $item['so_luong']; ?>" readonly
                                   style="width: 40px; height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 3px;">

                            <a href="cart.php?action=inc&id=<?php echo $item['cart_id']; ?>" 
                               style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #333; border-radius: 3px;">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                        
                        <div style="font-size: 14px; color: #666;">
                            Tổng: <strong style="color: #39c5bb;"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ</strong>
                        </div>
                    </div>
                    
                    <a href="cart.php?action=del&id=<?php echo $item['cart_id']; ?>" 
                       onclick="return confirm('Bạn có chắc muốn xóa không?');"
                       style="background: #e74c3c; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block;">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <div style="width: 35%;">
            <div style="background: white; border: 3px solid #39c5bb; border-radius: 10px; margin-bottom: 10px;">
                <img src="img/cart.jpg" style="width: 100%; height: auto; border-radius: 5px; display: block;">
            </div>

            <div style="background: white; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    Thông tin đơn hàng
                </h3>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span style="font-weight: bold; color: #333; font-size: 16px;">Tổng tiền:</span>
                    <span style="font-weight: bold; color: #d32f2f; font-size: 24px;">
                        <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                    </span>
                </div>

                <?php if (!empty($cart_items)): ?>
                    <a href="payment.php" style="display: block; width: 100%; background: #39c5bb; color: white; text-align: center; text-decoration: none; padding: 15px 0; font-weight: bold; font-size: 16px; border-radius: 5px; transition: 0.3s;"
                       onmouseover="this.style.backgroundColor='#2ca99f'"
                       onmouseout="this.style.backgroundColor='#39c5bb'">
                        THANH TOÁN NGAY
                    </a>
                <?php else: ?>
                    <button disabled style="width: 100%; background: #ccc; color: #666; padding: 15px 0; font-weight: bold; border: none; border-radius: 5px; cursor: not-allowed;">
                        THANH TOÁN NGAY
                    </button>
                <?php endif; ?>

                <div style="text-align: center; margin-top: 15px;">
                    <a href="instock.php" style="text-decoration: none; color: #39c5bb; font-size: 14px; font-weight: bold;">
                        <i class="fa-solid fa-arrow-left"></i> Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>