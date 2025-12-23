<?php
session_start();
require_once 'config.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Lấy thông tin giỏ hàng
$cart_items = [];
$total_amount = 0;

$sql = "SELECT gh.so_luong, sp.ten, sp.hinh, sp.gia 
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

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// 3. XỬ LÝ KHI BẤM THANH TOÁN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment'];
    
    // Xóa sạch giỏ hàng 
    $conn->query("DELETE FROM gio_hang WHERE id_nguoi_dung = $user_id");
    
    // Điều hướng dựa trên phương thức thanh toán
    if ($payment_method == 'cod') {
        echo "<script>
                alert('Đặt hàng thành công!\\nChúng tôi sẽ liên hệ để giao hàng sớm nhất.');
                window.location.href = 'index.php';
              </script>";
    } else {
        // Nếu là Bank hoặc Momo -> Chuyển thẳng sang trang quét mã (momo.php nằm ở thư mục gốc)
        header("Location: momo/momo.php?total=" . $total_amount);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Giữ nguyên CSS cũ của bạn */
        .option-box { border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 10px; cursor: pointer; display: flex; align-items: center; transition: 0.2s; }
        .option-box:hover { border-color: #39c5bb; }
        .option-box input { margin-right: 15px; transform: scale(1.2); accent-color: #39c5bb; }
        .input-field { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; outline: none; margin-bottom: 15px; font-family: Arial, sans-serif; box-sizing: border-box; }
        .input-field:focus { border-color: #39c5bb; box-shadow: 0 0 5px rgba(57, 197, 187, 0.3); }
        .product-item { display: flex; gap: 10px; padding: 10px; border-bottom: 1px solid #eee; align-items: center; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 1px solid #eee; }
    </style>
</head>
<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <form action="" method="POST" id="payment-form">
        <input type="hidden" name="phi_ship" id="hidden_phi_ship" value="0">
        
        <div style="max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px;">

            <div style="width: 60%;">
                <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 20px;">
                    <h3 style="margin-top: 0; color: #333;"><i class="fa-solid fa-truck"></i> THÔNG TIN GIAO HÀNG</h3>
                    
                    <div style="display: flex; gap: 20px;">
                        <input type="text" name="hoten" class="input-field" placeholder="Họ và tên" required>
                        <input type="text" name="sdt" class="input-field" placeholder="Số điện thoại" required>
                    </div>
                    <input type="text" name="diachi" class="input-field" placeholder="Địa chỉ nhận hàng (Số nhà, đường, phường...)" required>

                    <div style="margin-top: 30px;">
                        <h3 style="margin-bottom: 15px; color: #333;"><i class="fa-solid fa-credit-card"></i> THANH TOÁN</h3>
                        
                        <label class="option-box">
                            <input type="radio" name="payment" value="cod" checked>
                            <i class="fa-solid fa-money-bill-wave" style="color: #39c5bb; font-size: 20px; margin-right: 10px;"></i>
                            <span>Thanh toán khi giao hàng (COD)</span>
                        </label>

                        <label class="option-box">
                            <input type="radio" name="payment" value="qr">
                            <i class="fa-solid fa-qrcode" style="color: #a50064; font-size: 20px; margin-right: 10px;"></i>
                            <span>Quét mã QR - Momo / Ngân hàng</span>
                        </label>
                    </div>
                </div>
                <a href="cart.php" style="color: #39c5bb; text-decoration: none; font-weight: bold;"><i class="fa-solid fa-arrow-left"></i> Quay lại giỏ hàng</a>
            </div>

            <div style="width: 40%;">
                <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                    <h3 style="margin-top: 0; padding-bottom: 15px; border-bottom: 1px solid #eee;">Đơn hàng (<?php echo count($cart_items); ?> món)</h3>
                    
                    <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="product-item">
                            <img src="imginstock/<?php echo $item['hinh']; ?>" class="product-img" onerror="this.src='img/no-image.jpg'">
                            <div style="flex: 1;">
                                <div style="font-weight: bold; font-size: 13px;"><?php echo $item['ten']; ?></div>
                                <div style="font-size: 12px; color: #666;">
                                    <?php echo $item['gia']; ?> x <?php echo $item['so_luong']; ?>
                                </div>
                            </div>
                            <div style="font-weight: bold; color: #39c5bb; font-size: 14px;">
                                <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                        <span style="font-size: 16px; font-weight: bold;">Tổng cộng:</span>
                        <span style="font-size: 24px; font-weight: bold; color: #e74c3c;">
                            <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                        </span>
                    </div>

                    <button type="submit" style="width: 100%; background: #39c5bb; color: white; border: none; padding: 15px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s;"
                            onmouseover="this.style.backgroundColor='#2ca99f'"
                            onmouseout="this.style.backgroundColor='#39c5bb'">
                        <i class="fa-solid fa-check-circle"></i> ĐẶT HÀNG NGAY
                    </button>
                </div>
            </div>

        </div>
    </form>
    
    <?php include 'footer.php'; ?>
</body>
</html>