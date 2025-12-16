<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: white;">

    <?php include 'header.php'; ?>

    <div style="min-height: 600px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">
        <div style="width: 100%; max-width: 500px; padding: 40px; border: 2px solid #39c5bb; border-radius: 20px; background: white; box-shadow: 0 0 50px rgba(57, 197, 187, 0.2);">
            <h1 style="text-align: center; color: #333; margin-bottom: 10px;">QUÊN MẬT KHẨU</h1>
            <div style="text-align: center; margin-bottom: 30px; font-size: 14px; color: #888;">
                Vui lòng nhập Email bạn đã đăng ký để lấy lại mật khẩu.
            </div>
            <form action="" method="POST">
                <div style="margin-bottom: 30px;">
                    <div style="position: relative;">
                        <i class="fa-solid fa-envelope" style="position: absolute; left: 15px; top: 15px; color: #999;"></i>
                        <input type="email" name="email" placeholder="Nhập địa chỉ Email của bạn" required 
                               style="width: 100%; padding: 15px 15px 15px 45px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                               onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                               onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                    </div>
                </div>
                <div>
                    <button type="submit" 
                            style="width: 100%; background: #39c5bb; color: white; border: 2px solid #39c5bb; padding: 15px; font-weight: bold; border-radius: 30px; cursor: pointer; transition: 0.3s; font-size: 16px; text-transform: uppercase;"
                            onmouseover="this.style.backgroundColor='white'; this.style.color='#39c5bb'"
                            onmouseout="this.style.backgroundColor='#39c5bb'; this.style.color='white'">
                        GỬI YÊU CẦU
                    </button>
                </div>
                <div style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <a href="login.php" style="text-decoration: none; color: #666; font-size: 14px;"
                       onmouseover="this.style.color='#39c5bb'"
                       onmouseout="this.style.color='#666'">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại Đăng nhập
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>