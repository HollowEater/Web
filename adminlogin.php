<?php
session_start();

// Nếu đã đăng nhập thì chuyển về trang admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Kiểm tra tài khoản admin
    // Email: admin@gmail.com | Password: 123
    if ($email === "admin@gmail.com" && $password === "123") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_username'] = "Admin";
        header("Location: admin.php");
        exit();
    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header i {
            font-size: 60px;
            color: #39c5bb;
            margin-bottom: 15px;
        }
        
        .login-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #888;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            outline: none;
        }
        
        .form-input:focus {
            border-color: #39c5bb;
            box-shadow: 0 0 10px rgba(57, 197, 187, 0.2);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: #39c5bb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
        }
        
        .btn-login:hover {
            background: #2ca99f;
            box-shadow: 0 5px 15px rgba(57, 197, 187, 0.3);
            transform: translateY(-2px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .back-home {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-home a {
            color: #39c5bb;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .back-home a:hover {
            color: #2ca99f;
            text-decoration: underline;
        }
        
        .admin-info {
            background: #f0f9ff;
            border: 1px solid #39c5bb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
        }
        
        .admin-info strong {
            color: #39c5bb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fa-solid fa-shield-halved"></i>
            <h2>ĐĂNG NHẬP ADMIN</h2>
            <p>Vui lòng đăng nhập để quản lý hệ thống</p>
        </div>
        
        <!-- Thông tin đăng nhập (chỉ hiển thị trong môi trường dev) -->
        <div class="admin-info">
            <i class="fa-solid fa-info-circle"></i> 
            <strong>Tài khoản admin:</strong><br>
            Email: <strong>admin@gmail.com</strong><br>
            Password: <strong>123</strong>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fa-solid fa-exclamation-triangle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" class="form-input" placeholder="Email" required autofocus>
            </div>
            
            <div class="form-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" class="form-input" placeholder="Mật khẩu" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
            </button>
        </form>
        
        <div class="back-home">
            <a href="index.php">
                <i class="fa-solid fa-arrow-left"></i> Quay về trang chủ
            </a>
        </div>
    </div>
</body>
</html>