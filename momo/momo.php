<?php
// Lấy tổng tiền từ URL
$total_amount = isset($_GET['total']) ? intval($_GET['total']) : 0;
$ma_don_hang = "DH" . date('Y') . rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="momo.css">
</head>
<body>
    <div class="email-container">

        <div class="header">
            <h2 style="margin: 0;">THÔNG TIN THANH TOÁN</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Vui lòng quét mã để hoàn tất</p>
        </div>
        
        <div class="content">
            <i class="fa-solid fa-envelope-circle-check success-icon"></i>
            <h1 style="color: #333; font-size: 24px; margin-bottom: 10px;">Đặt hàng thành công!</h1>
            <p style="color: #666; line-height: 1.6;">
                Cảm ơn bạn đã mua sắm tại Null Eater Store.<br>
                Vui lòng quét mã QR bên dưới để thanh toán.
            </p>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            
            <h3 style="color: #a50064; margin: 0;">QUÉT MÃ THANH TOÁN</h3>
            <p style="color: #888; font-size: 13px;">Mở ứng dụng Momo hoặc Banking và quét mã bên dưới</p>
            
            <div class="momo">
                <img src="../img/momo.jpg" alt="Mã QR" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 10px;">
            </div>
            
            <div class="order-info">
                <p style="margin: 5px 0;"><strong>Mã đơn hàng:</strong> <?php echo $ma_don_hang; ?></p>
                <p style="margin: 5px 0;"><strong>Tổng thanh toán:</strong> <span style="color: #d32f2f; font-weight: bold;"><?php echo number_format($total_amount, 0, ',', '.'); ?>đ</span></p>
                <p style="margin: 5px 0;"><strong>Nội dung CK:</strong> <?php echo $ma_don_hang; ?> Tên SĐT</p>
            </div>
            
            <a href="../index.php" class="btn-home">
                <i class="fa-solid fa-house"></i> VỀ TRANG CHỦ
            </a>
        </div>
    </div>
</body>

</html>