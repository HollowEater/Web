<div style="background-color: #ff7675; padding: 20px 0; margin-bottom: 50px; font-family: Arial, sans-serif;">
    
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; color: white;">
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="background: #ffeaa7; color: #d63031; padding: 2px 5px; font-weight: bold; font-size: 12px; border-radius: 3px;">HOT!</span>
                <h2 style="margin: 0; font-style: italic;">FLASH SALE</h2>
            </div>

            <div style="flex: 1; margin: 0 50px;">
                <marquee scrollamount="10" style="color: white; font-weight: bold; font-size: 16px;">
                    Tokisaki Kurumi AMP+ giảm giá cực sốc --- Hatsune Miku Birthday 2023 giảm 25% --- Nhanh tay số lượng có hạn!
                </marquee>
            </div>

            <div style="display: flex; gap: 5px; text-align: center;">
                <div style="background: white; color: #333; padding: 5px; border-radius: 5px; width: 40px;">
                    <div id="gio" style="font-weight: bold; font-size: 18px;">12</div>
                    <div style="font-size: 10px;">Giờ</div>
                </div>
                <div style="background: white; color: #333; padding: 5px; border-radius: 5px; width: 40px;">
                    <div id="phut" style="font-weight: bold; font-size: 18px;">00</div>
                    <div style="font-size: 10px;">Phút</div>
                </div>
                <div style="background: white; color: #333; padding: 5px; border-radius: 5px; width: 40px;">
                    <div id="giay" style="font-weight: bold; font-size: 18px;">00</div>
                    <div style="font-size: 10px;">Giây</div>
                </div>
            </div>

        </div>

        <div style="display: flex; gap: 15px; padding-bottom: 10px;">
            <?php //overflow-x: auto; ?>
            <?php foreach ($flash_sale as $sp): ?>
                <div style="background: white; border-radius: 10px; width: 20%; padding: 10px; position: relative; box-sizing: border-box;">
                    
                    <div style="position: absolute; background: #d63031; color: white; padding: 2px 8px; font-size: 12px; border-radius: 3px; font-weight: bold;">
                        <?php //position: absolute; => cho giảm giá nằm trên nền?>
                        <?php echo $sp['giam']; ?>
                    </div>

                    <div style="width: 100%; height: 200px; margin-bottom: 10px;">
                        <img src="imgflashsale/<?php echo $sp['hinh']; ?>" style="width: 100%; height: 100%; object-fit: contain; border-radius: 5px;">
                    </div>

                    <h3 style="font-size: 14px; color: #333; margin: 0 0 20px 0;">
                        <?php echo $sp['ten']; ?>   
                    </h3>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="color: #d63031; font-weight: bold; font-size: 16px;"><?php echo $sp['gia_moi']; ?></span>
                        <span style="color: #999; text-decoration: line-through; font-size: 12px;"><?php echo $sp['gia_cu']; ?></span>
                    </div>

                    <div style="background: #fab1a0; height: 5px; border-radius: 5px; margin-bottom: 15px; position: relative;">
                        <div style="background: #d63031; width: 80%; height: 100%; border-radius: 5px;"></div>
                        <span style="position: absolute; top: -15px; left: 0; font-size: 10px; color: #d63031;">🔥 Sắp cháy hàng</span>
                    </div>

                    <div style="text-align: center;">
                        <a href="instock.php" 
                           style="display: block; width: 100%; background: #d63031; color: white; text-decoration: none; padding: 10px 0; font-weight: bold; border-radius: 5px; transition: 0.3s;"
                           onmouseover="this.style.background='#b71540'"
                           onmouseout="this.style.background='#d63031'">
                            MUA NGAY
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</div>

<script>
    var countDownDate = new Date().getTime() + (12 * 60 * 60 * 1000); // Thời gian
    
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000); // Tính giờ

        document.getElementById("gio").innerHTML = hours; // Hiển thị giờ trừ
        document.getElementById("phut").innerHTML = minutes;
        document.getElementById("giay").innerHTML = seconds;

        // Nếu hết giờ thì dừng
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("gio").innerHTML = "00";
        }
    }, 1000);
</script>