<?php
session_start();
require_once 'config.php';

$ten = "";
$ho = "";
$email = "";
$loi = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten = clean_input($_POST['ten']);
    $ho = clean_input($_POST['ho']);
    $email = clean_input($_POST['email']);
    $matkhau = $_POST['matkhau'];
    $xacnhan = $_POST['nhaplaimatkhau'];
    
    // Kiểm tra mật khẩu khớp
    if ($matkhau !== $xacnhan) {
        $loi = "password_mismatch";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT id FROM nguoi_dung WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $loi = "email_exists";
        } else {
            // Mã hóa mật khẩu
            $mat_khau_hash = password_hash($matkhau, PASSWORD_DEFAULT);
            
            // Thêm người dùng vào database
            $insert_sql = "INSERT INTO nguoi_dung (ten, ho, email, mat_khau) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssss", $ten, $ho, $email, $mat_khau_hash);
            
            if ($insert_stmt->execute()) {
                echo "<script>
                        alert('Đăng ký thành công! Mời bạn đăng nhập.');
                        window.location.href = 'login.php';
                      </script>";
                exit();
            } else {
                $loi = "database_error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: white;">

    <?php include 'header.php'; ?>

    <div style="min-height: 700px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">

        <div style="width: 100%; max-width: 500px; padding: 40px; border: 2px solid #39c5bb; border-radius: 20px; background: white; box-shadow: 0 0 50px rgba(57, 197, 187, 0.2);">

            <h1 style="text-align: center; color: #333; margin-bottom: 10px;">ĐĂNG KÝ TÀI KHOẢN</h1>
            
            <div style="text-align: center; margin-bottom: 30px; font-size: 14px; color: #888;">
                <a href="index.php" style="text-decoration: none; color: #39c5bb;">Trang chủ</a> / <span>Đăng ký tài khoản</span>
            </div>

            <?php if ($loi == "email_exists"): ?>
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <i class="fa-solid fa-exclamation-circle"></i> Email đã được đăng ký. Vui lòng sử dụng email khác!
                </div>
            <?php elseif ($loi == "database_error"): ?>
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <i class="fa-solid fa-exclamation-circle"></i> Lỗi hệ thống. Vui lòng thử lại sau!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <input type="text" name="ten" placeholder="Tên" required value="<?php echo htmlspecialchars($ten); ?>"
                           style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; transition: 0.3s;"
                           onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                           onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                    
                    <input type="text" name="ho" placeholder="Họ" required value="<?php echo htmlspecialchars($ho); ?>"
                           style="flex: 1; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; transition: 0.3s;"
                           onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                           onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                </div>

                <div style="margin-bottom: 20px;">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>"
                           style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                           onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                           onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                </div>

                <div style="margin-bottom: 20px;">
                    <input type="password" name="matkhau" placeholder="Mật khẩu" required 
                           style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                           onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                           onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'">
                </div>

                <div style="margin-bottom: 30px;">
                    <input type="password" id="input_xacnhan" name="nhaplaimatkhau" placeholder="Xác nhận mật khẩu" required 
                           style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 30px; outline: none; box-sizing: border-box; transition: 0.3s;"
                           onfocus="this.style.borderColor='#39c5bb'; this.style.boxShadow='0 0 5px #39c5bb'"
                           onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none'"
                           oninput="this.setCustomValidity('')">
                </div>

                <div>
                    <button type="submit" 
                            style="width: 100%; background: #39c5bb; color: white; border: 2px solid #39c5bb; padding: 15px; font-weight: bold; border-radius: 30px; cursor: pointer; transition: 0.3s; font-size: 16px; text-transform: uppercase;"
                            onmouseover="this.style.backgroundColor='white'; this.style.color='#39c5bb'"
                            onmouseout="this.style.backgroundColor='#39c5bb'; this.style.color='white'">
                        ĐĂNG KÝ NGAY
                    </button>
                </div>

                <div style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <span style="color: #666;">Bạn đã có tài khoản?</span>
                    <a href="login.php" style="text-decoration: none; color: #39c5bb; font-weight: bold; margin-left: 5px;"
                       onmouseover="this.style.textDecoration='underline'"
                       onmouseout="this.style.textDecoration='none'">
                        Đăng nhập ngay
                    </a>
                </div>

            </form>

        </div>
    </div>

    <?php include 'footer.php'; ?>

    <?php if ($loi == "password_mismatch"): ?>
    <script>
        var inputXacNhan = document.getElementById("input_xacnhan");
        inputXacNhan.setCustomValidity("Mật khẩu xác nhận không khớp! Vui lòng nhập lại.");
        inputXacNhan.reportValidity();
    </script>
    <?php endif; ?>

</body>
</html>