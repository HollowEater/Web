<?php
session_start();
require_once 'config.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);
    $action = $_GET['action'];

    // Xử lý Xóa
    if ($action == 'del') {
        $conn->query("DELETE FROM gio_hang WHERE id = $cart_id AND id_nguoi_dung = $user_id");
    }

    // Xử lý Tăng/Giảm
    elseif ($action == 'inc' || $action == 'dec') {
        // Kiểm tra tồn kho trước khi tăng
        $check = $conn->query("SELECT gh.so_luong, sp.so_luong_ton FROM gio_hang gh JOIN san_pham sp ON gh.id_san_pham = sp.id WHERE gh.id = $cart_id");

        if ($check->num_rows > 0) {
            $curr = $check->fetch_assoc();

            if ($action == 'inc') {
                if ($curr['so_luong'] < $curr['so_luong_ton']) {
                    $conn->query("UPDATE gio_hang SET so_luong = so_luong + 1 WHERE id = $cart_id");
                } else {
                    echo "<script>alert('Số lượng đã đạt giới hạn tồn kho!');</script>";
                }
            } elseif ($action == 'dec') {
                if ($curr['so_luong'] > 1) {
                    $conn->query("UPDATE gio_hang SET so_luong = so_luong - 1 WHERE id = $cart_id");
                }
            }
        }
    }

    // Load lại trang để cập nhật số liệu
    echo "<script>window.location.href = 'cart.php';</script>";
    exit();
}
// 3. LẤY DỮ LIỆU GIỎ HÀNG ĐỂ HIỂN THỊ
$cart_items = [];
$total_amount = 0;

$sql = "SELECT gh.id as cart_id, gh.so_luong, sp.ten, sp.hinh, sp.gia, sp.giam_gia, sp.so_luong_ton 
        FROM gio_hang gh 
        JOIN san_pham sp ON gh.id_san_pham = sp.id 
        WHERE gh.id_nguoi_dung = ? 
        ORDER BY gh.ngay_them DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Xử lý giá tiền để tính toán (Bỏ dấu chấm, chữ đ)
    $price = intval(str_replace(['.', 'đ', ','], '', $row['gia']));
    $row['subtotal'] = $price * $row['so_luong'];
    $total_amount += $row['subtotal'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            gap: 30px;
            padding: 0 20px;
        }

        .cart-left {
            width: 65%;
        }

        .cart-right {
            width: 35%;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #39c5bb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .cart-item {
            background: white;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .item-img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            border: 1px solid #eee;
            object-fit: cover;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            background: #f0f0f0;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #e0e0e0;
        }

        .qty-input {
            width: 40px;
            height: 28px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
            font-weight: bold;
        }

        .summary-box {
            background: white;
            padding: 25px;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <div class="cart-container">

        <div class="cart-left">
            <div class="cart-header">
                <h2 style="margin: 0; color: #333; font-size: 24px;">Giỏ hàng</h2>
                <span style="color: #666;"><?php echo count($cart_items); ?> sản phẩm</span>
            </div>

            <?php if (empty($cart_items)): ?>
                <div style="background: white; padding: 50px; text-align: center; border-radius: 8px; border: 1px solid #eee;">
                    <i class="fa-solid fa-cart-arrow-down" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
                    <p style="font-size: 18px; color: #555;">Giỏ hàng của bạn đang trống!</p>
                    <a href="instock.php" style="display: inline-block; margin-top: 10px; color: white; background: #39c5bb; padding: 10px 25px; text-decoration: none; border-radius: 30px; font-weight: bold;">
                        Mua sắm ngay
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="imginstock/<?php echo $item['hinh']; ?>" class="item-img" onerror="this.src='img/no-image.jpg'">

                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">
                                <?php echo $item['ten']; ?>
                            </h3>
                            <div style="font-size: 12px; color: #888;">
                                <?php if ($item['giam_gia']): ?>
                                    <span style="background: #ff6b6b; color: white; padding: 2px 6px; border-radius: 3px;">
                                        Giảm <?php echo $item['giam_gia']; ?>
                                    </span>
                                <?php endif; ?>
                                <span style="margin-left: <?php echo $item['giam_gia'] ? '10px' : '0'; ?>;">
                                    Kho: <?php echo $item['so_luong_ton']; ?>
                                </span>
                            </div>
                        </div>

                        <div style="text-align: center; min-width: 120px;">
                            <div style="font-weight: bold; color: #e74c3c; font-size: 16px; margin-bottom: 8px;">
                                <?php echo $item['gia']; ?>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: center;">
                                <a href="cart.php?action=dec&id=<?php echo $item['cart_id']; ?>" class="qty-btn"><i class="fa-solid fa-minus"></i></a>
                                <input type="text" value="<?php echo $item['so_luong']; ?>" readonly class="qty-input">
                                <a href="cart.php?action=inc&id=<?php echo $item['cart_id']; ?>" class="qty-btn"><i class="fa-solid fa-plus"></i></a>
                            </div>
                        </div>

                        <a href="cart.php?action=del&id=<?php echo $item['cart_id']; ?>"
                            onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')"
                            style="color: #999; padding: 10px; margin-left: 10px; transition: 0.2s;"
                            onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#999'">
                            <i class="fa-solid fa-trash-can" style="font-size: 18px;"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-right">
            <div class="summary-box">
                <div style="background: white; border: 3px solid #39c5bb; border-radius: 10px; margin-bottom: 10px;">
                    <img src="img/cart.jpg" style="width: 100%; height: auto; border-radius: 5px; display: block;">
                </div>
                <h3 style="margin: 0 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #eee; font-size: 18px;">
                    Thông tin đơn hàng
                </h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <span style="color: #555; font-weight: bold;">Tạm tính:</span>
                    <span style="color: #e74c3c; font-weight: bold; font-size: 20px;">
                        <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                    </span>
                </div>

                <?php if (!empty($cart_items)): ?>
                    <a href="payment.php" style="display: block; width: 100%; background: #39c5bb; color: white; text-align: center; text-decoration: none; padding: 15px 0; font-weight: bold; border-radius: 30px; transition: 0.3s; box-sizing: border-box;"
                        onmouseover="this.style.backgroundColor='#2ca99f'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.backgroundColor='#39c5bb'; this.style.transform='translateY(0)'">
                        TIẾN HÀNH THANH TOÁN
                    </a>
                <?php else: ?>
                    <button disabled style="width: 100%; background: #ccc; color: white; padding: 15px 0; font-weight: bold; border: none; border-radius: 30px; cursor: not-allowed;">
                        TIẾN HÀNH THANH TOÁN
                    </button>
                <?php endif; ?>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="instock.php" style="text-decoration: none; color: #39c5bb; font-weight: bold; font-size: 14px;">
                        <i class="fa-solid fa-arrow-left"></i> Tiếp tục xem hàng
                    </a>
                </div>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>

</body>

</html>