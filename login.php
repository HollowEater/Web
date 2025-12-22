<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Kiểm tra nếu là tài khoản admin
    if ($email === "admin@gmail.com" && $password === "123") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_username'] = "Admin";
        
        // Chuyển đến trang quản lý admin
        echo "<script>
                alert('Đăng nhập Admin thành công!');
                window.location.href = 'admin.php';
              </script>";
        exit();
    } else {
        // Đây là tài khoản khách hàng thông thường
        // Sau này code lưu vào Database sẽ viết ở đây.
        
        echo "<script>
                alert('Đăng nhập thành công! Mời bạn mua sắm.');
                window.location.href = 'index.php';
              </script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: white;">

    <?php include 'header.php'; ?>

    <div style="min-height: 600px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">
        <?php //khung ?>
        <div style="width: 100%; max-width: 500px; padding: 40px; border: 2px solid #39c5bb; border-radius: 20px; background: white; box-shadow: 0 0 50px rgba(57, 197, 187, 0.2);">
            <?php //box-shadow: => ngang-dọc-nhòe ?>
            <h1 style="text-align: center; color: #333; margin-bottom: 10px;">ĐĂNG NHẬP</h1>

            <div style="text-align: center; margin-bottom: 30px; font-size: 14px; color: #888;">
                <a href="index.php" style="text-decoration: none; color: #39c5bb;">Trang chủ</a> / <span>Đăng nhập tài khoản</span>
            </div>

            <form action="" method="POST">
                <div style="margin-bottom: 20px;">
                    <input type="email" name="email" placeholder="Email" required
                        style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                        onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                        onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                </div>

                <div style="margin-bottom: 25px;">
                    <input type="password" name="password" placeholder="Mật khẩu" required
                        style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                        onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                        onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <button type="submit"
                        style="background: #39c5bb; color: white; border: 2px solid #39c5bb; padding: 12px 35px; font-weight: bold; border-radius: 30px; cursor: pointer; transition: 0.3s; font-size: 16px;"
                        onmouseover="this.style.backgroundColor='white'; this.style.color='#39c5bb'"
                        onmouseout="this.style.backgroundColor='#39c5bb'; this.style.color='white'">
                        ĐĂNG NHẬP
                    </button>

                    <a href="password.php" style="text-decoration: none; color: #666; font-size: 14px; transition: 0.3s;"
                        onmouseover="this.style.color='#39c5bb'"
                        onmouseout="this.style.color='#666'">
                        Quên mật khẩu?
                    </a>
                </div>

                <div style="text-align: center; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                    <span style="color: #666;">Bạn chưa có tài khoản?</span>
                    <a href="register.php" style="text-decoration: none; color: #39c5bb; font-weight: bold; margin-left: 5px;"
                        onmouseover="this.style.textDecoration='underline'"
                        onmouseout="this.style.textDecoration='none'">
                        Đăng ký ngay
                    </a>
                </div>
            </form>

        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>