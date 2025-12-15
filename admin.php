<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $hinh = $_POST['hinh'];
    $gia = $_POST['gia'];
    $gia_cu = $_POST['gia_cu'];
    $giam_gia = $_POST['giam_gia'];
    $nhan_vat = $_POST['nhan_vat'];
    echo "<script>
            alert('Đã thêm sản phẩm: " . $ten . " vào cơ sở dữ liệu!');
            // window.location.href = 'instock.php'; // Mở dòng này nếu muốn chuyển trang
          </script>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            width: 100%; max-width: 700px; 
            padding: 40px; 
            border: 2px solid #39c5bb; 
            border-radius: 20px; 
            background: white; 
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-input {
            width: 100%; padding: 12px; 
            border: 1px solid #ddd; border-radius: 5px; 
            outline: none; box-sizing: border-box; transition: 0.3s;
            font-size: 14px;
        }
        .form-input:focus { border-color: #39c5bb; box-shadow: 0 0 5px rgba(57, 197, 187, 0.3); }
        .row-2-col { display: flex; gap: 20px; }
        .col { flex: 1; }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">

    <?php include 'header.php'; ?>

    <div style="min-height: 800px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">

        <div class="admin-container">

            <h1 style="text-align: center; color: #333; margin-bottom: 10px;">QUẢN LÝ KHO HÀNG</h1>
            
            <div style="text-align: center; margin-bottom: 30px; font-size: 14px; color: #888;">
                <span>Nhập thông tin sản phẩm để thêm vào Database</span>
            </div>

            <form action="" method="POST">
                
                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">Mã sản phẩm (ID)</label>
                        <input type="number" name="id" class="form-input" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Tên file hình ảnh</label>
                        <input type="text" name="hinh" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="ten" class="form-input" required>
                </div>

                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">Giá bán chính thức</label>
                        <input type="text" name="gia" class="form-input" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Giá cũ (Để trống nếu không có)</label>
                        <input type="text" name="gia_cu" class="form-input">
                    </div>
                </div>

                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">Giảm giá</label>
                        <input type="text" name="giam_gia" class="form-input">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Nhân vật (Tag)</label>
                        <input type="text" name="nhan_vat" class="form-input" required>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" 
                            style="width: 100%; background: #39c5bb; color: white; border: none; padding: 15px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: 0.3s; font-size: 16px; text-transform: uppercase;"
                            onmouseover="this.style.backgroundColor='#2ca99f'"
                            onmouseout="this.style.backgroundColor='#39c5bb'">
                        <i class="fa-solid fa-save"></i> LƯU VÀO KHO
                    </button>
                </div>

            </form>

        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>