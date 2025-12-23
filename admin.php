<?php
session_start();
require_once 'config.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// 2. XỬ LÝ XÓA SẢN PHẨM (Chạy ngay trong file này)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM san_pham WHERE id = $id");
    echo "<script>alert('Đã xóa sản phẩm thành công!'); window.location.href = 'admin.php';</script>";
    exit();
}

// 3. XỬ LÝ THÊM SẢN PHẨM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten = $_POST['ten'];
    $hinh = $_POST['hinh'];
    $gia = $_POST['gia'];
    $gia_cu = !empty($_POST['gia_cu']) ? $_POST['gia_cu'] : '';
    $giam_gia = !empty($_POST['giam_gia']) ? $_POST['giam_gia'] : '';
    $nhan_vat = $_POST['nhan_vat'];
    $so_luong = intval($_POST['so_luong_ton']);
    $trang_thai = $_POST['trang_thai'];
    $mo_ta = $_POST['mo_ta'];

    // Câu lệnh thêm SQL
    $sql = "INSERT INTO san_pham (ten, hinh, gia, gia_cu, giam_gia, nhan_vat, so_luong_ton, trang_thai, mo_ta) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiss", $ten, $hinh, $gia, $gia_cu, $giam_gia, $nhan_vat, $so_luong, $trang_thai, $mo_ta);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
    }
}

// 4. Lấy danh sách sản phẩm để hiện bên dưới
$result_list = $conn->query("SELECT * FROM san_pham ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            width: 100%; max-width: 900px; padding: 40px; 
            border: 2px solid #39c5bb; border-radius: 20px; 
            background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; 
            outline: none; box-sizing: border-box; transition: 0.3s; font-family: Arial;
        }
        .form-input:focus { border-color: #39c5bb; }
        .row-2-col { display: flex; gap: 20px; }
        .row-3-col { display: flex; gap: 15px; }
        .col { flex: 1; }
        
        .product-item {
            display: flex; align-items: center; gap: 15px; padding: 10px; 
            border: 1px solid #eee; border-radius: 5px; margin-bottom: 10px;
        }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">

    <?php include 'header.php'; ?>

    <div style="min-height: 800px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">

        <div class="admin-container">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #333; margin: 0;">QUẢN LÝ KHO HÀNG</h1>
                <a href="adminout.php" onclick="return confirm('Đăng xuất?')" style="color: red; text-decoration: none; font-weight: bold;">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="ten" class="form-input" required>
                </div>

                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">Hình ảnh (VD: miku.jpg)</label>
                        <input type="text" name="hinh" class="form-input" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Nhân vật (Tag)</label>
                        <input type="text" name="nhan_vat" class="form-input" required>
                    </div>
                </div>

                <div class="row-3-col">
                    <div class="col form-group">
                        <label class="form-label">Giá bán</label>
                        <input type="text" name="gia" class="form-input" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Giá cũ</label>
                        <input type="text" name="gia_cu" class="form-input">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Giảm giá</label>
                        <input type="text" name="giam_gia" class="form-input">
                    </div>
                </div>

                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">Số lượng tồn</label>
                        <input type="number" name="so_luong_ton" class="form-input" value="10">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="In Stock">In Stock</option>
                            <option value="Pre-order">Pre-order</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" class="form-textarea" rows="2"></textarea>
                </div>

                <button type="submit" style="width: 100%; background: #39c5bb; color: white; border: none; padding: 15px; font-weight: bold; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    <i class="fa-solid fa-save"></i> LƯU VÀO KHO
                </button>

            </form>

            <div style="margin-top: 40px; border-top: 2px solid #39c5bb; padding-top: 20px;">
                <h3 style="color: #333;">Sản phẩm mới nhất</h3>
                
                <?php while($row = $result_list->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="imginstock/<?php echo $row['hinh']; ?>" class="product-img" onerror="this.src='img/no-image.jpg'">
                    <div style="flex: 1;">
                        <div style="font-weight: bold;"><?php echo $row['ten']; ?></div>
                        <div style="font-size: 13px; color: #666;">
                            <?php echo $row['gia']; ?> | Tồn: <?php echo $row['so_luong_ton']; ?>
                        </div>
                    </div>
                    <a href="admin.php?action=delete&id=<?php echo $row['id']; ?>" 
                       onclick="return confirm('Xóa sản phẩm này?')"
                       style="color: white; background: #e74c3c; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                        Xóa
                    </a>
                </div>
                <?php endwhile; ?>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="instock.php" style="color: #39c5bb; font-weight: bold; text-decoration: none;">Xem trang cửa hàng &rarr;</a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; //if (!is_numeric($gia)) {
       // echo "<script>alert('Lỗi: Giá bán phải là một con số (Ví dụ: 100000)!'); window.history.back();</script>";
       // exit(); }?>
</body>
</html>