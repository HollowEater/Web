<?php
session_start();
require_once 'config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin giỏ hàng
$cart_items = [];
$total_amount = 0;

$sql = "SELECT gh.id as cart_id, gh.so_luong, sp.id, sp.ten, sp.hinh, sp.gia, sp.gia_cu, sp.giam_gia 
        FROM gio_hang gh 
        INNER JOIN san_pham sp ON gh.id_san_pham = sp.id 
        WHERE gh.id_nguoi_dung = ?
        ORDER BY gh.ngay_them DESC";

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

// Nếu giỏ hàng trống, chuyển về trang giỏ hàng
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Xử lý đặt hàng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment'];
    $customer_name = clean_input($_POST['hoten']);
    $customer_phone = clean_input($_POST['sdt']);
    $customer_email = isset($_POST['email_khach']) ? clean_input($_POST['email_khach']) : '';
    $customer_address = clean_input($_POST['diachi']);
    $shipping_fee = isset($_POST['phi_ship']) ? intval($_POST['phi_ship']) : 0;
    $total_payment = $total_amount + $shipping_fee;
    
    // Nếu thanh toán bằng Momo, chuyển sang trang Momo (để sau)
    if ($payment_method == 'qr') {
        echo "<script>
                alert('Chức năng thanh toán Momo đang được phát triển!');
                window.location.href = 'payment.php';
              </script>";
        exit();
    }
    
    // Xử lý thanh toán COD và Bank Transfer
    try {
        // Bắt đầu transaction
        $conn->begin_transaction();
        
        // Tạo mã đơn hàng
        $order_code = generate_order_code();
        
        // Lưu đơn hàng vào bảng don_hang
        $insert_order = "INSERT INTO don_hang (ma_don_hang, id_nguoi_dung, ten_khach, sdt_khach, email_khach, dia_chi, 
                         phuong_thuc_thanh toan, phi_van_chuyen, tong_tien, trang_thai) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'cho_xac_nhan')";
        $stmt_order = $conn->prepare($insert_order);
        $stmt_order->bind_param("sisssssii", $order_code, $user_id, $customer_name, $customer_phone, 
                                $customer_email, $customer_address, $payment_method, $shipping_fee, $total_payment);
        $stmt_order->execute();
        $order_id = $conn->insert_id;
        
        // Lưu chi tiết đơn hàng
        foreach ($cart_items as $item) {
            $insert_detail = "INSERT INTO chi_tiet_don_hang (id_don_hang, id_san_pham, ten_san_pham, gia, so_luong) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmt_detail = $conn->prepare($insert_detail);
            $price = price_to_number($item['gia']);
            $stmt_detail->bind_param("iisii", $order_id, $item['id'], $item['ten'], $price, $item['so_luong']);
            $stmt_detail->execute();
            
            // Giảm số lượng tồn kho
            update_stock($item['id'], $item['so_luong'], 'decrease');
        }
        
        // Xóa giỏ hàng
        $delete_cart = "DELETE FROM gio_hang WHERE id_nguoi_dung = ?";
        $stmt_delete = $conn->prepare($delete_cart);
        $stmt_delete->bind_param("i", $user_id);
        $stmt_delete->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Thông báo thành công
        $payment_text = $payment_method == 'cod' ? 'Thanh toán khi giao hàng (COD)' : 'Chuyển khoản ngân hàng';
        echo "<script>
                alert('Đặt hàng thành công!\\n\\nMã đơn hàng: $order_code\\nPhương thức: $payment_text\\nTổng tiền: " . number_format($total_payment, 0, ',', '.') . "đ\\n\\nCảm ơn bạn đã mua sắm!');
                window.location.href = 'index.php';
              </script>";
        exit();
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        $error_message = "Có lỗi xảy ra: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .option-box {
            border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 10px;
            cursor: pointer; display: flex; align-items: center; transition: 0.2s;
        }
        .option-box:hover { border-color: #39c5bb; }
        .option-box input { margin-right: 15px; transform: scale(1.2); accent-color: #39c5bb; }
        .input-field {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; 
            outline: none; margin-bottom: 15px; font-family: Arial, sans-serif;
            box-sizing: border-box; 
        }
        .input-field:focus { border-color: #39c5bb; box-shadow: 0 0 5px rgba(57, 197, 187, 0.3); }
        #box-email-qr { transition: all 0.3s ease; }
        .product-item {
            display: flex;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <?php if (isset($error_message)): ?>
        <div style="max-width: 1200px; margin: 20px auto; padding: 15px; background: #fee; border: 1px solid #fcc; color: #c33; border-radius: 5px;">
            <i class="fa-solid fa-exclamation-triangle"></i> <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" id="payment-form">
        <input type="hidden" name="phi_ship" id="hidden_phi_ship" value="0">
        
        <div style="max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px;">

            <!-- Phần bên trái: Thông tin giao hàng -->
            <div style="width: 60%;">
                
                <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 20px;">
                    <h3 style="margin-top: 0; color: #333;">
                        <i class="fa-solid fa-truck"></i> THÔNG TIN GIAO HÀNG
                    </h3>
                    
                    <div style="display: flex; gap: 20px;">
                        <input type="text" name="hoten" class="input-field" placeholder="Họ và tên" required>
                        <input type="text" name="sdt" class="input-field" placeholder="Số điện thoại" required>
                    </div>
                    <input type="email" name="email_khach" class="input-field" placeholder="Email liên hệ (Không bắt buộc)">

                    <div style="display: flex; gap: 20px;">
                        <select class="input-field" id="tinh_thanh" onchange="tinhPhiShip()" required>
                            <option value="0">-- Chọn Tỉnh / Thành --</option>
                            <option value="20000">Hồ Chí Minh</option>
                            <option value="35000">Hà Nội</option>
                            <option value="30000">Đà Nẵng</option>
                            <option value="40000">Các tỉnh khác</option>
                        </select>
                        <select class="input-field" required>
                            <option>-- Quận / Huyện --</option>
                            <option>Quận 1</option>
                            <option>Quận Tân Bình</option>
                            <option>Thủ Đức</option>
                        </select>
                    </div>
                    <input type="text" name="diachi" class="input-field" placeholder="Số nhà, tên đường, phường/xã..." required>

                    <div id="box-van-chuyen" style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                        <h4 style="margin: 0 0 15px 0;">
                            <i class="fa-solid fa-shipping-fast"></i> Phương thức vận chuyển
                        </h4>
                        <label class="option-box">
                            <input type="radio" name="ship" checked>
                            <div style="flex: 1; font-weight: bold;">Giao hàng tiêu chuẩn</div>
                            <div style="color: #39c5bb; font-weight: bold;" id="hien_thi_ship">0đ</div>
                        </label>
                    </div>

                    <div style="margin-top: 30px;">
                        <h3 style="margin-bottom: 15px; color: #333;">
                            <i class="fa-solid fa-credit-card"></i> PHƯƠNG THỨC THANH TOÁN
                        </h3>
                        
                        <label class="option-box">
                            <input type="radio" name="payment" value="cod" checked onclick="toggleEmailQR()">
                            <i class="fa-solid fa-money-bill-wave" style="color: #39c5bb; font-size: 20px; margin-right: 10px;"></i>
                            <span>Thanh toán khi giao hàng (COD)</span>
                        </label>

                        <label class="option-box">
                            <input type="radio" name="payment" value="bank" onclick="toggleEmailQR()">
                            <i class="fa-solid fa-building-columns" style="color: #39c5bb; font-size: 20px; margin-right: 10px;"></i>
                            <span>Chuyển khoản ngân hàng</span>
                        </label>

                        <label class="option-box">
                            <input type="radio" name="payment" value="qr" id="payment_qr" onclick="toggleEmailQR()">
                            <i class="fa-solid fa-qrcode" style="color: #39c5bb; font-size: 20px; margin-right: 10px;"></i>
                            <span>Quét mã QR - Momo (Đang phát triển)</span>
                        </label>

                        <div id="box-email-qr" style="display: none; margin-left: 30px; padding: 15px; background: #f0fffe; border: 1px dashed #39c5bb; border-radius: 5px;">
                            <p style="margin: 0 0 10px 0; font-size: 13px; color: #555;">
                                <i class="fa-solid fa-envelope"></i> Nhập Email để chúng tôi gửi mã QR thanh toán:
                            </p>
                            <input type="email" name="email_qr" id="input_email_qr" class="input-field" placeholder="Nhập địa chỉ Email của bạn..." style="margin-bottom: 0; background: white;">
                        </div>
                    </div>
                </div>
                
                <a href="cart.php" style="color: #39c5bb; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: bold;">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại giỏ hàng
                </a>
            </div>

            <!-- Phần bên phải: Đơn hàng -->
            <div style="width: 40%;">
                <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                    <h3 style="margin-top: 0; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                        <i class="fa-solid fa-shopping-bag"></i> Đơn hàng (<?php echo count($cart_items); ?> sản phẩm)
                    </h3>

                    <!-- Danh sách sản phẩm -->
                    <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                        <?php foreach ($cart_items as $item): ?>
                        <div class="product-item">
                            <img src="imginstock/<?php echo $item['hinh']; ?>" class="product-img" onerror="this.src='img/no-image.jpg'">
                            <div style="flex: 1;">
                                <div style="font-size: 13px; color: #333; margin-bottom: 3px;">
                                    <?php echo $item['ten']; ?>
                                </div>
                                <div style="font-size: 12px; color: #666;">
                                    <span style="color: #e74c3c; font-weight: bold;"><?php echo $item['gia']; ?></span>
                                    <span style="margin-left: 5px;">x <?php echo $item['so_luong']; ?></span>
                                </div>
                            </div>
                            <div style="font-weight: bold; color: #39c5bb; font-size: 14px;">
                                <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 15px;">

                    <!-- Tính tiền -->
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #555;">
                        <span>Tạm tính</span>
                        <span><?php echo number_format($total_amount, 0, ',', '.'); ?>đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; color: #555;">
                        <span>Phí vận chuyển</span>
                        <span id="phi_ship_text">—</span>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="font-size: 16px; font-weight: bold;">Tổng cộng</span>
                        <span style="font-size: 24px; font-weight: bold; color: #e74c3c;" id="tong_thanh_toan">
                            <?php echo number_format($total_amount, 0, ',', '.'); ?>đ
                        </span>
                    </div>

                    <button type="submit" style="width: 100%; background: #39c5bb; color: white; border: none; padding: 15px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;"
                            onmouseover="this.style.backgroundColor='#2ca99f'"
                            onmouseout="this.style.backgroundColor='#39c5bb'">
                        <i class="fa-solid fa-check-circle"></i> ĐẶT HÀNG
                    </button>

                    <div style="margin-top: 15px; text-align: center; font-size: 12px; color: #888;">
                        <i class="fa-solid fa-shield-halved"></i> Thông tin của bạn được bảo mật
                    </div>
                </div>
            </div>

        </div>
    </form>
    
    <?php include 'footer.php'; ?>

    <script>
        var tongTienHang = <?php echo $total_amount; ?>;

        function tinhPhiShip() {
            var selectBox = document.getElementById("tinh_thanh");
            var phiShip = parseInt(selectBox.value);
            
            if (phiShip === 0) {
                document.getElementById("box-van-chuyen").style.display = "none";
                document.getElementById("phi_ship_text").innerText = "—";
                document.getElementById("tong_thanh_toan").innerText = formatTien(tongTienHang) + "đ";
                document.getElementById("hidden_phi_ship").value = 0;
                return;
            }

            document.getElementById("box-van-chuyen").style.display = "block";
            document.getElementById("hien_thi_ship").innerText = formatTien(phiShip) + "đ";
            document.getElementById("phi_ship_text").innerText = formatTien(phiShip) + "đ";
            document.getElementById("hidden_phi_ship").value = phiShip;
            
            var tongCong = tongTienHang + phiShip;
            document.getElementById("tong_thanh_toan").innerText = formatTien(tongCong) + "đ";
        }

        function toggleEmailQR() {
            var radioQR = document.getElementById("payment_qr");
            var boxEmail = document.getElementById("box-email-qr");
            var inputEmail = document.getElementById("input_email_qr");

            if (radioQR.checked) {
                boxEmail.style.display = "block";
                inputEmail.required = true; 
            } else {
                boxEmail.style.display = "none";
                inputEmail.required = false; 
                inputEmail.value = ""; 
            }
        }

        function formatTien(so) {
            return so.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Xác nhận trước khi đặt hàng
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            var paymentMethod = document.querySelector('input[name="payment"]:checked').value;
            var paymentText = '';
            
            if (paymentMethod == 'cod') {
                paymentText = 'Thanh toán khi giao hàng (COD)';
            } else if (paymentMethod == 'bank') {
                paymentText = 'Chuyển khoản ngân hàng';
            } else {
                paymentText = 'Quét mã QR - Momo';
            }
            
            var totalAmount = document.getElementById('tong_thanh_toan').innerText;
            
            if (!confirm('Xác nhận đặt hàng?\n\nPhương thức: ' + paymentText + '\nTổng tiền: ' + totalAmount)) {
                e.preventDefault();
            }
        });
    </script>

</body>
</html>