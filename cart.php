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

            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ff6b6b; padding-bottom: 10px; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 24px; color: #39c5bb;">Giỏ hàng:</h2>
                <span style="color: #888;">0 Sản phẩm</span>
            </div>

            <div style="background: white; padding: 50px 20px; text-align: center; border: 1px solid #eee; border-radius: 5px;">
                <p style="color: #333; margin-bottom: 20px;">
                    Giỏ hàng của bạn đang trống. Mời bạn mua thêm sản phẩm
                    <a href="instock.php" style="color: #d32f2f; text-decoration: none; font-weight: bold;">tại đây</a>.
                </p>
            </div>

        </div>

        <div style="width: 35%;">

            <div style="background: white; border: 3px solid #39c5bb; border-radius: 10px; box-shadow: 0 0 20px #39c5bb">
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
                    <span style="font-weight: bold; color: #d32f2f; font-size: 24px;">0đ</span>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <input type="checkbox" id="hoadon" style="cursor: pointer; margin-right: 10px;">
                    <label for="hoadon" style="cursor: pointer; color: #555; font-size: 14px;">Xuất hoá đơn</label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Ghi chú đơn hàng</label>
                    <textarea placeholder="Ghi chú" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; outline: none; box-sizing: border-box; font-family: Arial;"></textarea>
                </div>

                <a href="payment.php" style="display: block; box-sizing: border-box; width: 100%; background: black; color: white; text-align: center; text-decoration: none; padding: 15px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; margin-bottom: 15px;"
                    onmouseover="this.style.backgroundColor='#39c5bb'"
                    onmouseout="this.style.backgroundColor='black'">
                    THANH TOÁN NGAY
                </a>

                <div style="text-align: center;">
                    <a href="instock.php" style="text-decoration: none; color: #333; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 5px;">
                        Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>