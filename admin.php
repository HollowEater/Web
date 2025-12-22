<?php
session_start();

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'config.php';

// Lấy thông báo từ session (nếu có từ trang delete)
$thong_bao = isset($_SESSION['thong_bao']) ? $_SESSION['thong_bao'] : "";
$loai_thong_bao = isset($_SESSION['loai_thong_bao']) ? $_SESSION['loai_thong_bao'] : "";

// Xóa thông báo khỏi session sau khi hiển thị
if (!empty($thong_bao)) {
    unset($_SESSION['thong_bao']);
    unset($_SESSION['loai_thong_bao']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ten = clean_input($_POST['ten']);
    $hinh = clean_input($_POST['hinh']);
    $gia = clean_input($_POST['gia']);
    $gia_cu = !empty($_POST['gia_cu']) ? clean_input($_POST['gia_cu']) : NULL;
    $giam_gia = !empty($_POST['giam_gia']) ? clean_input($_POST['giam_gia']) : NULL;
    $nhan_vat = clean_input($_POST['nhan_vat']);
    $so_luong_ton = isset($_POST['so_luong_ton']) ? intval($_POST['so_luong_ton']) : 0;
    $trang_thai = isset($_POST['trang_thai']) ? clean_input($_POST['trang_thai']) : 'In Stock';
    $mo_ta = !empty($_POST['mo_ta']) ? clean_input($_POST['mo_ta']) : NULL;
    
    // Kiểm tra xem có sử dụng ID tùy chỉnh không
    $id_tuy_chinh = !empty($_POST['id']) ? intval($_POST['id']) : NULL;
    
    try {
        if ($id_tuy_chinh) {
            // Kiểm tra ID đã tồn tại chưa
            $check_sql = "SELECT id FROM san_pham WHERE id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $id_tuy_chinh);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                throw new Exception("ID sản phẩm $id_tuy_chinh đã tồn tại! Vui lòng chọn ID khác.");
            }
            
            // Thêm với ID tùy chỉnh
            $sql = "INSERT INTO san_pham (id, ten, hinh, gia, gia_cu, giam_gia, nhan_vat, so_luong_ton, trang_thai, mo_ta) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssss", $id_tuy_chinh, $ten, $hinh, $gia, $gia_cu, $giam_gia, $nhan_vat, $so_luong_ton, $trang_thai, $mo_ta);
        } else {
            // Thêm với AUTO_INCREMENT
            $sql = "INSERT INTO san_pham (ten, hinh, gia, gia_cu, giam_gia, nhan_vat, so_luong_ton, trang_thai, mo_ta) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $ten, $hinh, $gia, $gia_cu, $giam_gia, $nhan_vat, $so_luong_ton, $trang_thai, $mo_ta);
        }
        
        if ($stmt->execute()) {
            $id_moi = $id_tuy_chinh ? $id_tuy_chinh : $conn->insert_id;
            $thong_bao = "Thêm sản phẩm thành công! ID: $id_moi - Tên: $ten";
            $loai_thong_bao = "success";
            
            // Reset form sau khi thêm thành công
            $_POST = array();
        } else {
            throw new Exception("Lỗi khi thêm sản phẩm: " . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $thong_bao = $e->getMessage();
        $loai_thong_bao = "error";
    }
}

// Lấy danh sách sản phẩm hiện có
$sql_list = "SELECT id, ten, hinh, gia, nhan_vat, so_luong_ton, trang_thai FROM san_pham ORDER BY id DESC LIMIT 10";
$result_list = $conn->query($sql_list);
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
            width: 100%; max-width: 900px; 
            padding: 40px; 
            border: 2px solid #39c5bb; 
            border-radius: 20px; 
            background: white; 
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px; 
            border: 1px solid #ddd; border-radius: 5px; 
            outline: none; box-sizing: border-box; transition: 0.3s;
            font-size: 14px; font-family: Arial, sans-serif;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { 
            border-color: #39c5bb; box-shadow: 0 0 5px rgba(57, 197, 187, 0.3); 
        }
        .row-2-col { display: flex; gap: 20px; }
        .row-3-col { display: flex; gap: 15px; }
        .col { flex: 1; }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .product-list {
            margin-top: 30px;
            border-top: 2px solid #39c5bb;
            padding-top: 20px;
        }
        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.2s;
        }
        .product-item:hover {
            background: #f9f9f9;
            border-color: #39c5bb;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-info {
            flex: 1;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f9f9f9;">

    <?php include 'header.php'; ?>

    <div style="min-height: 800px; display: flex; align-items: center; justify-content: center; padding: 50px 20px;">

        <div class="admin-container">

            <!-- Header với nút đăng xuất -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h1 style="text-align: left; color: #333; margin: 0;">
                        <i class="fa-solid fa-box"></i> QUẢN LÝ KHO HÀNG
                    </h1>
                    <div style="font-size: 14px; color: #888; margin-top: 5px;">
                        <i class="fa-solid fa-user-shield"></i> Xin chào, <strong style="color: #39c5bb;"><?php echo $_SESSION['admin_username']; ?></strong>
                    </div>
                </div>
                <a href="admin_logout.php" 
                   style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s; display: flex; align-items: center; gap: 8px;"
                   onmouseover="this.style.backgroundColor='#c82333'"
                   onmouseout="this.style.backgroundColor='#dc3545'"
                   onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
            
            <div style="text-align: center; margin-bottom: 30px; font-size: 14px; color: #888;">
                <span>Nhập thông tin sản phẩm để thêm vào Database</span>
            </div>

            <?php if (!empty($thong_bao)): ?>
                <div class="alert alert-<?php echo $loai_thong_bao; ?>">
                    <i class="fa-solid fa-<?php echo $loai_thong_bao == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <span><?php echo $thong_bao; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="row-2-col">
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-hashtag"></i> Mã sản phẩm (ID) 
                            <small style="color: #888;">(Tùy chọn - Để trống nếu muốn tự động)</small>
                        </label>
                        <input type="number" name="id" class="form-input" placeholder="VD: 401, 402... (hoặc để trống)">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-image"></i> Tên file hình ảnh
                        </label>
                        <input type="text" name="hinh" class="form-input" placeholder="VD: miku1.jpg" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-tag"></i> Tên sản phẩm
                    </label>
                    <input type="text" name="ten" class="form-input" placeholder="Nhập tên đầy đủ của sản phẩm" required>
                </div>

                <div class="row-3-col">
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-dollar-sign"></i> Giá bán
                        </label>
                        <input type="text" name="gia" class="form-input" placeholder="VD: 1.500.000đ" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-money-bill"></i> Giá cũ
                        </label>
                        <input type="text" name="gia_cu" class="form-input" placeholder="Để trống nếu không">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-percent"></i> Giảm giá
                        </label>
                        <input type="text" name="giam_gia" class="form-input" placeholder="VD: -15%">
                    </div>
                </div>

                <div class="row-3-col">
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-user"></i> Nhân vật (Tag)
                        </label>
                        <input type="text" name="nhan_vat" class="form-input" placeholder="VD: miku, kurumi, shiro" required>
                    </div>
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-cubes"></i> Số lượng tồn
                        </label>
                        <input type="number" name="so_luong_ton" class="form-input" value="0" min="0">
                    </div>
                    <div class="col form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-circle-check"></i> Trạng thái
                        </label>
                        <select name="trang_thai" class="form-select">
                            <option value="In Stock">In Stock</option>
                            <option value="Pre-order">Pre-order</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-align-left"></i> Mô tả sản phẩm <small style="color: #888;">(Tùy chọn)</small>
                    </label>
                    <textarea name="mo_ta" class="form-textarea" rows="3" placeholder="Nhập mô tả chi tiết về sản phẩm..."></textarea>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" 
                            style="flex: 1; background: #39c5bb; color: white; border: none; padding: 15px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: 0.3s; font-size: 16px; text-transform: uppercase;"
                            onmouseover="this.style.backgroundColor='#2ca99f'"
                            onmouseout="this.style.backgroundColor='#39c5bb'">
                        <i class="fa-solid fa-save"></i> LƯU VÀO KHO
                    </button>
                    <button type="reset" 
                            style="background: #6c757d; color: white; border: none; padding: 15px 30px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: 0.3s; font-size: 16px;"
                            onmouseover="this.style.backgroundColor='#5a6268'"
                            onmouseout="this.style.backgroundColor='#6c757d'">
                        <i class="fa-solid fa-eraser"></i> XÓA
                    </button>
                </div>

            </form>

            <?php if ($result_list && $result_list->num_rows > 0): ?>
            <div class="product-list">
                <h3 style="color: #333; margin-bottom: 15px;">
                    <i class="fa-solid fa-list"></i> Sản phẩm mới thêm gần đây (10 sản phẩm)
                </h3>
                <?php while($row = $result_list->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="imginstock/<?php echo $row['hinh']; ?>" alt="" class="product-img" onerror="this.src='img/no-image.jpg'">
                    <div class="product-info">
                        <div style="font-weight: bold; color: #333; margin-bottom: 3px;">
                            #<?php echo $row['id']; ?> - <?php echo $row['ten']; ?>
                        </div>
                        <div style="font-size: 12px; color: #666;">
                            <span style="color: #d63031; font-weight: bold;"><?php echo $row['gia']; ?></span> | 
                            <span>Nhân vật: <strong><?php echo $row['nhan_vat']; ?></strong></span> | 
                            <span>Tồn kho: <strong><?php echo $row['so_luong_ton']; ?></strong></span> |
                            <span class="badge badge-<?php echo $row['trang_thai'] == 'In Stock' ? 'success' : 'warning'; ?>">
                                <?php echo $row['trang_thai']; ?>
                            </span>
                        </div>
                    </div>
                    <button onclick="xoaSanPham(<?php echo $row['id']; ?>, '<?php echo addslashes($row['ten']); ?>')" 
                            style="background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; display: flex; align-items: center; gap: 5px;"
                            onmouseover="this.style.backgroundColor='#c82333'"
                            onmouseout="this.style.backgroundColor='#dc3545'">
                        <i class="fa-solid fa-trash"></i> Xóa
                    </button>
                </div>
                <?php endwhile; ?>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="instock.php" style="color: #39c5bb; text-decoration: none; font-weight: bold;">
                        <i class="fa-solid fa-arrow-right"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function xoaSanPham(id, ten) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?\n\nID: ' + id + '\nTên: ' + ten + '\n\nHành động này không thể hoàn tác!')) {
                window.location.href = 'delete_product.php?id=' + id;
            }
        }
    </script>

</body>
</html>