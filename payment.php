<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phuong_thuc = $_POST['payment'];
    $msg_email = "";
    if ($phuong_thuc == 'qr') {
        $email_nhan = $_POST['email_qr'];
        $msg_email = "\\n- Mã QR và thông tin đơn hàng đã được gửi về email: $email_nhan";
    }

    echo "<script>
            alert('Đặt hàng thành công!$msg_email\\n- Cảm ơn bạn đã mua sắm.');
            window.location.href = 'index.php';
          </script>";
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
    </style>
</head>
<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <?php
    $gio_hang_demo = []; 
    $tong_tien_hang = 0;
    foreach($gio_hang_demo as $sp) $tong_tien_hang += $sp['gia'] * $sp['sl'];
    ?>

    <form action="" method="POST"> <div style="max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px;">

        <div style="width: 60%;">
            
            <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #eee; margin-bottom: 20px;">
                <h3 style="margin-top: 0; color: #333;">THÔNG TIN GIAO HÀNG</h3>
                
                <div style="display: flex; gap: 20px;">
                    <input type="text" name="hoten" class="input-field" placeholder="Họ và tên" required>
                    <input type="text" name="sdt" class="input-field" placeholder="Số điện thoại" required>
                </div>
                <input type="email" name="email_khach" class="input-field" placeholder="Email liên hệ (Không bắt buộc)">

                <div style="display: flex; gap: 20px;">
                    <select class="input-field" id="tinh_thanh" onchange="tinhPhiShip()">
                        <option value="0">-- Chọn Tỉnh / Thành --</option>
                        <option value="20000">Hồ Chí Minh</option>
                        <option value="35000">Hà Nội</option>
                        <option value="30000">Đà Nẵng</option>
                        <option value="40000">Các tỉnh khác</option>
                    </select>
                    <select class="input-field">
                        <option>-- Quận / Huyện --</option>
                        <option>Quận 1</option>
                        <option>Quận Tân Bình</option>
                        <option>Thủ Đức</option>
                    </select>
                </div>
                <input type="text" name="diachi" class="input-field" placeholder="Số nhà, tên đường, phường/xã..." required>

                <div id="box-van-chuyen" style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <h4 style="margin: 0 0 15px 0;">Phương thức vận chuyển</h4>
                    <label class="option-box">
                        <input type="radio" name="ship" checked>
                        <div style="flex: 1; font-weight: bold;">Giao hàng tiêu chuẩn</div>
                        <div style="color: #39c5bb; font-weight: bold;" id="hien_thi_ship">0đ</div>
                    </label>
                </div>

                <div style="margin-top: 30px;">
                    <h3 style="margin-bottom: 15px; color: #333;">PHƯƠNG THỨC THANH TOÁN</h3>
                    
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
                        <span>Quét mã QR - Momo</span>
                    </label>

                    <div id="box-email-qr" style="display: none; margin-left: 30px; padding: 15px; background: #f0fffe; border: 1px dashed #39c5bb; border-radius: 5px;">
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #555;">
                            <i class="fa-solid fa-envelope"></i> Nhập Email để chúng tôi gửi mã QR thanh toán:
                        </p>
                        <input type="email" name="email_qr" id="input_email_qr" class="input-field" placeholder="Nhập địa chỉ Email của bạn..." style="margin-bottom: 0; background: white;">
                    </div>

                </div>

            </div>
            
            <a href="cart.php" style="color: #39c5bb; text-decoration: none;">&lt; Quay lại giỏ hàng</a>

        </div>

        <div style="width: 40%;">
            <div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
                <h3 style="margin-top: 0; padding-bottom: 15px; border-bottom: 1px solid #eee;">Đơn hàng</h3>

                <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                    <?php if(empty($gio_hang_demo)): ?>
                        <div style="text-align: center; color: #999; padding: 20px;">
                            (Sản phẩm sẽ được lấy từ Database)
                        </div>
                    <?php else: ?>
                        <?php foreach($gio_hang_demo as $sp): ?>
                            <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 15px;">

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #555;">
                    <span>Tạm tính</span>
                    <span><?php echo number_format($tong_tien_hang, 0, ',', '.'); ?>đ</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; color: #555;">
                    <span>Phí vận chuyển</span>
                    <span id="phi_ship_text">—</span> </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span style="font-size: 16px; font-weight: bold;">Tổng cộng</span>
                    <span style="font-size: 24px; font-weight: bold; color: #39c5bb;" id="tong_thanh_toan">
                        <?php echo number_format($tong_tien_hang, 0, ',', '.'); ?>đ
                    </span>
                </div>

                <button type="submit" style="width: 100%; background: #39c5bb; color: white; border: none; padding: 15px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s;"
                        onmouseover="this.style.backgroundColor='#2ca99f'"
                        onmouseout="this.style.backgroundColor='#39c5bb'">
                    ĐẶT HÀNG
                </button>

            </div>
        </div>

    </div>
    </form> <?php include 'footer.php'; ?>

    <script>
        var tongTienHang = <?php echo $tong_tien_hang; ?>;

        function tinhPhiShip() {
            var selectBox = document.getElementById("tinh_thanh");
            var phiShip = parseInt(selectBox.value);
            
            if (phiShip === 0) {
                document.getElementById("box-van-chuyen").style.display = "none";
                document.getElementById("phi_ship_text").innerText = "—";
                document.getElementById("tong_thanh_toan").innerText = formatTien(tongTienHang) + "đ";
                return;
            }

            document.getElementById("box-van-chuyen").style.display = "block";
            document.getElementById("hien_thi_ship").innerText = formatTien(phiShip) + "đ";
            document.getElementById("phi_ship_text").innerText = formatTien(phiShip) + "đ";
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
    </script>

</body>
</html>