<?php
session_start();
require_once 'config.php';

// Kiểm tra đăng nhập
$cart_items = [];
$total_amount = 0;

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $user_id = $_SESSION['user_id'];
    
    // Lấy danh sách sản phẩm trong giỏ hàng
    $sql = "SELECT gh.id as cart_id, gh.so_luong, sp.id, sp.ten, sp.hinh, sp.gia, sp.gia_cu, sp.giam_gia, sp.so_luong_ton 
            FROM gio_hang gh 
            INNER JOIN san_pham sp ON gh.id_san_pham = sp.id 
            WHERE gh.id_nguoi_dung = ?
            ORDER BY gh.ngay_them DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Tính tổng tiền cho từng sản phẩm
        $price = price_to_number($row['gia']);
        $row['subtotal'] = $price * $row['so_luong'];
        $total_amount += $row['subtotal'];
        
        $cart_items[] = $row;
    }
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
                <!-- Giỏ hàng trống -->
                <div style="background: white; padding: 50px 20px; text-align: center; border: 1px solid #eee; border-radius: 5px;">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                    <p style="color: #333; margin-bottom: 20px; font-size: 18px;">
                        Giỏ hàng của bạn đang trống. Mời bạn mua thêm sản phẩm
                        <a href="instock.php" style="color: #39c5bb; text-decoration: none; font-weight: bold;">tại đây</a>.
                    </p>
                </div>
            <?php else: ?>
                <!-- Danh sách sản phẩm trong giỏ -->
                <?php foreach ($cart_items as $item): ?>
                <div style="background: white; padding: 15px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 15px; display: flex; gap: 15px; align-items: center; transition: 0.3s;"
                     onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.boxShadow='none'">
                    
                    <!-- Hình ảnh sản phẩm -->
                    <div style="width: 100px; height: 100px; border: 1px solid #eee; border-radius: 5px; overflow: hidden;">
                        <img src="imginstock/<?php echo $item['hinh']; ?>" 
                             style="width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.src='img/no-image.jpg'">
                    </div>
                    
                    <!-- Thông tin sản phẩm -->
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #333;">
                            <?php echo $item['ten']; ?>
                        </h3>
                        <div style="font-size: 13px; color: #888; margin-bottom: 10px;">
                            <span>Mã SP: #<?php echo $item['id']; ?></span>
                            <?php if (!empty($item['giam_gia'])): ?>
                                <span style="background: #ff4757; color: white; padding: 2px 6px; border-radius: 3px; margin-left: 10px; font-size: 11px;">
                                    <?php echo $item['giam_gia']; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 14px; color: #666;">
                            <span>Còn lại: <strong style="color: <?php echo $item['so_luong_ton'] > 5 ? '#27ae60' : '#e74c3c'; ?>">
                                <?php echo $item['so_luong_ton']; ?> sản phẩm
                            </strong></span>
                        </div>
                    </div>
                    
                    <!-- Giá và số lượng -->
                    <div style="text-align: center; min-width: 150px;">
                        <div style="font-weight: bold; color: #e74c3c; font-size: 18px; margin-bottom: 10px;">
                            <?php echo $item['gia']; ?>
                        </div>
                        <?php if (!empty($item['gia_cu'])): ?>
                            <div style="text-decoration: line-through; color: #999; font-size: 13px; margin-bottom: 10px;">
                                <?php echo $item['gia_cu']; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Điều chỉnh số lượng -->
                        <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                            <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['so_luong'] - 1; ?>, <?php echo $item['so_luong_ton']; ?>)" 
                                    style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold; border-radius: 3px;"
                                    <?php if ($item['so_luong'] <= 1) echo 'disabled'; ?>>
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="text" value="<?php echo $item['so_luong']; ?>" readonly
                                   style="width: 50px; height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 3px;">
                            <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['so_luong'] + 1; ?>, <?php echo $item['so_luong_ton']; ?>)" 
                                    style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold; border-radius: 3px;"
                                    <?php if ($item['so_luong'] >= $item['so_luong_ton']) echo 'disabled'; ?>>
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        
                        <!-- Tổng tiền sản phẩm -->
                        <div style="font-size: 14px; color: #666;">
                            Tổng: <strong style="color: #39c5bb;"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ</strong>
                        </div>
                    </div>
                    
                    <!-- Nút xóa -->
                    <button onclick="removeFromCart(<?php echo $item['cart_id']; ?>, '<?php echo addslashes($item['ten']); ?>')"
                            style="background: #e74c3c; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; transition: 0.3s;"
                            onmouseover="this.style.backgroundColor='#c0392b'"
                            onmouseout="this.style.backgroundColor='#e74c3c'">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <!-- Sidebar thông tin đơn hàng -->
        <div style="width: 35%;">

            <div style="background: white; border: 3px solid #39c5bb; border-radius: 10px; box-shadow: 0 0 20px rgba(57, 197, 187, 0.3);">
                <div style="width: 100%; height: 500px; border-radius: 5px; margin-bottom: 10px; margin-top: 10px;">
                    <img src="img/cart.jpg" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>

            <div style="background: white; padding: 20px; border: 1px solid #eee; border-radius: 5px; margin-top: 10px;">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    Thông tin đơn hàng
                </h3>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span style="font-weight: bold; color: #333; font-size: 16px;">Tổng tiền:</span>
                    <span style="font-weight: bold; color: #e74c3c; font-size: 24px;">
                        <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                    </span>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <input type="checkbox" id="hoadon" style="cursor: pointer; margin-right: 10px;">
                    <label for="hoadon" style="cursor: pointer; color: #555; font-size: 14px;">Xuất hoá đơn</label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Ghi chú đơn hàng</label>
                    <textarea id="order-note" placeholder="Ghi chú" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; outline: none; box-sizing: border-box; font-family: Arial;"></textarea>
                </div>

                <?php if (!empty($cart_items)): ?>
                    <a href="payment.php" style="display: block; box-sizing: border-box; width: 100%; background: #39c5bb; color: white; text-align: center; text-decoration: none; padding: 15px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; margin-bottom: 15px; border-radius: 5px;"
                        onmouseover="this.style.backgroundColor='#2ca99f'"
                        onmouseout="this.style.backgroundColor='#39c5bb'">
                        <i class="fa-solid fa-credit-card"></i> THANH TOÁN NGAY
                    </a>
                <?php else: ?>
                    <button disabled style="display: block; box-sizing: border-box; width: 100%; background: #ccc; color: #666; text-align: center; padding: 15px; font-weight: bold; font-size: 16px; margin-bottom: 15px; border-radius: 5px; border: none; cursor: not-allowed;">
                        <i class="fa-solid fa-credit-card"></i> THANH TOÁN NGAY
                    </button>
                <?php endif; ?>

                <div style="text-align: center;">
                    <a href="instock.php" style="text-decoration: none; color: #39c5bb; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 5px; font-weight: bold;">
                        <i class="fa-solid fa-arrow-left"></i> Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>

    <script>
        // Cập nhật số lượng sản phẩm
        function updateQuantity(cartId, newQuantity, stockAvailable) {
            if (newQuantity < 1) {
                alert('Số lượng tối thiểu là 1!');
                return;
            }
            
            if (newQuantity > stockAvailable) {
                alert('Số lượng vượt quá tồn kho!');
                return;
            }
            
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cart_id=' + cartId + '&quantity=' + newQuantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload để cập nhật giá
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
        }
        
        // Xóa sản phẩm khỏi giỏ hàng
        function removeFromCart(cartId, productName) {
            if (!confirm('Bạn có chắc muốn xóa "' + productName + '" khỏi giỏ hàng?')) {
                return;
            }
            
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cart_id=' + cartId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng giỏ hàng ở header
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cart_count);
                    }
                    location.reload(); // Reload để cập nhật giao diện
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
        }
    </script>
</body>
</html>