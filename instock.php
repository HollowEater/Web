<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản Phẩm Có Sẵn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin: 0; background-color: #f9f9f9; font-family: Arial, sans-serif;">

    <?php include 'header.php'; ?>
    <?php include 'xuly/locgia.php'; ?>

    <div style="max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px;">

        <div style="width: 25%;">
            <?php include 'instockfilter.php'; ?>
        </div>

        <div style="width: 75%;">
            <div style="margin-bottom: 20px;">
                <h2 style="margin: 0; color: #333;">SẢN PHẨM CÓ SẴN</h2>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php foreach ($danh_sach_hien_thi as $sp): ?>
                    <div style="width: 31%; background: white; border: 1px solid #eee; border-radius: 10px; overflow: hidden; position: relative; transition: 0.3s;"
                         onmouseover="this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)'; this.style.borderColor='#39c5bb'"
                         onmouseout="this.style.boxShadow='none'; this.style.borderColor='#eee'">
                        
                        <div style="position: absolute; top: 10px; left: -5px; background: #39c5bb; color: white; padding: 3px 10px; font-size: 12px; font-weight: bold; box-shadow: 2px 2px 5px rgba(0,0,0,0.2); z-index: 10;">
                            In Stock
                        </div>
                        
                        <?php if ($sp['giam_gia'] != ""): ?>
                            <div style="position: absolute; top: 10px; right: 10px; background: #d63031; color: white; padding: 2px 6px; font-size: 12px; border-radius: 3px; font-weight: bold; z-index: 10;"><?php echo $sp['giam_gia']; ?></div>
                        <?php endif; ?>

                        <div style="width: 100%; height: 250px; overflow: hidden;">
                            <img src="imginstock/<?php echo $sp['hinh']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;" 
                            onmouseover="this.style.transform='scale(1.1)'" 
                            onmouseout="this.style.transform='scale(1)'">
                        </div>

                        <div style="padding: 15px;">
                            <h3 style="font-size: 14px; margin: 0 0 10px 0; height: 36px; color: #333;"><?php echo $sp['ten']; ?></h3>
                            <div style="margin-bottom: 15px;">
                                <span style="color: #d63031; font-weight: bold; font-size: 16px;"><?php echo $sp['gia']; ?></span>
                                <?php if (isset($sp['gia_cu'])): ?>
                                    <span style="color: #999; text-decoration: line-through; font-size: 12px; margin-left: 5px;"><?php echo $sp['gia_cu']; ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                <button onclick="giamSoLuong(this)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold;">-</button>
                                <input type="text" value="1" style="width: 40px; height: 30px; text-align: center; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: none; border-right: none;">
                                <button onclick="tangSoLuong(this)" style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; font-weight: bold;">+</button>
                            </div>
                            <button style="width: 100%; background: #39c5bb; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s;" onmouseover="this.style.backgroundColor='#2ca99f'" onmouseout="this.style.backgroundColor='#39c5bb'">
                                <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($danh_sach_hien_thi)): ?>
                    <div style="width: 100%; text-align: center; padding: 50px; color: #888;">
                        <h3><i class="fa-solid fa-box-open"></i> Không tìm thấy sản phẩm nào!</h3>
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
    </script>
</body>
</html>