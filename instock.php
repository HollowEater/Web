<?php
require_once 'config.php';

$muc_gia = isset($_GET['muc_gia']) ? $_GET['muc_gia'] : 'all';
$chon_char = isset($_GET['chon_char']) ? $_GET['chon_char'] : 'all';

$sql = "SELECT * FROM san_pham WHERE 1=1";
$params = [];
$types = "";

if ($chon_char != 'all') {
    $sql .= " AND nhan_vat = ?";
    $params[] = $chon_char;
    $types .= "s";
}

if ($muc_gia != 'all') {
    switch ($muc_gia) {
        case '1':
            $sql .= " AND CAST(REPLACE(REPLACE(gia, '.', ''), 'đ', '') AS UNSIGNED) < 500000";
            break;
        case '2': 
            $sql .= " AND CAST(REPLACE(REPLACE(gia, '.', ''), 'đ', '') AS UNSIGNED) BETWEEN 500000 AND 2000000";
            break;
        case '3': 
            $sql .= " AND CAST(REPLACE(REPLACE(gia, '.', ''), 'đ', '') AS UNSIGNED) BETWEEN 2000001 AND 5000000";
            break;
        case '4': 
            $sql .= " AND CAST(REPLACE(REPLACE(gia, '.', ''), 'đ', '') AS UNSIGNED) > 5000000";
            break;
    }
}

$sql .= " ORDER BY id DESC";

// Chuẩn bị và thực thi truy vấn
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Lưu kết quả vào mảng
$danh_sach_hien_thi = [];
while ($row = $result->fetch_assoc()) {
    $danh_sach_hien_thi[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản Phẩm Có Sẵn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>

    <div style="max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px;">

        <div style="width: 25%;">
            <?php include 'instockfilter.php'; ?>
        </div>

        <div style="width: 75%;">
            <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; color: #333;">
                    <i class="fa-solid fa-store"></i> SẢN PHẨM CÓ SẴN
                </h2>
                <div style="color: #666; font-size: 14px;">
                    <i class="fa-solid fa-box"></i> 
                    Tìm thấy <strong style="color: #39c5bb;"><?php echo count($danh_sach_hien_thi); ?></strong> sản phẩm
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php foreach ($danh_sach_hien_thi as $sp): ?>
                    <div style="width: 31%; background: white; border: 1px solid #eee; border-radius: 10px; overflow: hidden; position: relative; transition: 0.3s;"
                         onmouseover="this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)'; this.style.borderColor='#39c5bb'"
                         onmouseout="this.style.boxShadow='none'; this.style.borderColor='#eee'">
                        
                        <div style="position: absolute; top: 10px; left: -5px; background: <?php echo $sp['trang_thai'] == 'In Stock' ? '#39c5bb' : ($sp['trang_thai'] == 'Pre-order' ? '#2ecc71' : '#e74c3c'); ?>; color: white; padding: 3px 10px; font-size: 12px; font-weight: bold; box-shadow: 2px 2px 5px rgba(0,0,0,0.2); z-index: 10;">
                            <?php echo $sp['trang_thai']; ?>
                        </div>
                        
                        <?php if (!empty($sp['giam_gia'])): ?>
                            <div style="position: absolute; top: 10px; right: 10px; background: #d63031; color: white; padding: 2px 6px; font-size: 12px; border-radius: 3px; font-weight: bold; z-index: 10;">
                                <?php echo $sp['giam_gia']; ?>
                            </div>
                        <?php endif; ?>

                        <div style="width: 100%; height: 250px; overflow: hidden; background: #f9f9f9;">
                            <img src="imginstock/<?php echo $sp['hinh']; ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;" 
                                 onmouseover="this.style.transform='scale(1.1)'" 
                                 onmouseout="this.style.transform='scale(1)'"
                                 onerror="this.src='img/no-image.jpg'">
                        </div>

                        <div style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="background: #f0f0f0; color: #666; padding: 2px 8px; border-radius: 3px; font-size: 11px;">
                                    #<?php echo $sp['id']; ?>
                                </span>
                                <span style="color: #888; font-size: 12px;">
                                    <i class="fa-solid fa-user"></i> <?php echo ucfirst($sp['nhan_vat']); ?>
                                </span>
                            </div>

                            <h3 style="font-size: 14px; margin: 0 0 10px 0; height: 36px; color: #333; line-height: 1.3; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo $sp['ten']; ?>
                            </h3>

                            <div style="margin-bottom: 10px;">
                                <span style="color: #888; font-size: 12px;">
                                    <i class="fa-solid fa-cubes"></i> Còn: <strong><?php echo $sp['so_luong_ton']; ?></strong>
                                </span>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <span style="color: #d63031; font-weight: bold; font-size: 16px;"><?php echo $sp['gia']; ?></span>
                                <?php if (!empty($sp['gia_cu'])): ?>
                                    <span style="color: #999; text-decoration: line-through; font-size: 12px; margin-left: 5px;"><?php echo $sp['gia_cu']; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                <button onclick="giamSoLuong(this)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold;">-</button>
                                <input type="text" value="1" style="width: 40px; height: 30px; text-align: center; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: none; border-right: none;">
                                <button onclick="tangSoLuong(this)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold;">+</button>
                            </div>

                            <button style="width: 100%; background: #39c5bb; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s;" 
                                    onmouseover="this.style.backgroundColor='#2ca99f'" 
                                    onmouseout="this.style.backgroundColor='#39c5bb'"
                                    onclick="themVaoGio(<?php echo $sp['id']; ?>, this)">
                                <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($danh_sach_hien_thi)): ?>
                    <div style="width: 100%; text-align: center; padding: 50px; color: #888;">
                        <h3><i class="fa-solid fa-box-open" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i></h3>
                        <h3 style="margin: 10px 0;">Không tìm thấy sản phẩm nào!</h3>
                        <p>Vui lòng thử thay đổi bộ lọc hoặc <a href="instock.php" style="color: #39c5bb; text-decoration: none;">xem tất cả sản phẩm</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function tangSoLuong(btn) {
            var input = btn.parentElement.querySelector('input');
            var value = parseInt(input.value);
            input.value = value + 1;
        }

        function giamSoLuong(btn) {
            var input = btn.parentElement.querySelector('input');
            var value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
            }
        }

        function themVaoGio(id, btn) {
            var soLuong = btn.parentElement.querySelector('input').value;
            
            // Hiệu ứng thêm vào giỏ
            btn.innerHTML = '<i class="fa-solid fa-check"></i> ĐÃ THÊM';
            btn.style.backgroundColor = '#2ecc71';
            
            setTimeout(function() {
                btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ';
                btn.style.backgroundColor = '#39c5bb';
            }, 1500);
            
            // TODO: Gửi AJAX request để thêm vào giỏ hàng trong database
            console.log('Thêm sản phẩm ID:', id, 'Số lượng:', soLuong);
            
            // Có thể thêm code AJAX ở đây để cập nhật giỏ hàng
        }
    </script>
</body>
</html>