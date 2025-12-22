<?php include 'data.php'; //Lấy bỏ qua đây 
?>

<div style="background-color: #000000ff; height: 80px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; font-family: Arial, sans-serif;">

    <a href="index.php" style="display: flex; align-items: center; text-decoration: none;">
        <img src="img/logo.jpg" alt="Logo Shop" style="height: 70px;">

        <span style="font-size: 24px; font-weight: bold;">
            <span style="color: #39c5bb;">NULL</span>
            <span style="color: #39c5bb;"> EATER </span>
            <span style="color: white;">STORE</span>
        </span>
    </a>

    <div style="display: flex; align-items: center; gap: 15px; height: 100%;">

        <div id="timkiem"
            style="position: relative; height: 100%; display: flex; align-items: center;"
            onmouseenter="hienMenu()"
            onmouseleave="anMenu()">

            <div style="background: transparent; color: #39c5bb; border: 2px solid #39c5bb; padding: 8px 20px; font-weight: bold; border-radius: 5px;">
                DANH MỤC SẢN PHẨM ▼
            </div>

            <div id="bangmenu" style="display: none; position: absolute; top: 80px; left: 0; width: 600px; background: white; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 999;">
                <div style="width: 33%; float: left;">
                    <h3 style="color: #39c5bb; border-bottom: 2px solid #39c5bb;">ANIME</h3>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($cot_1_anime as $item): ?>
                            <li style="padding: 5px;"><a href="#" style="text-decoration: none; color: #333;"><?php echo $item; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div style="width: 33%; float: left;">
                    <h3 style="color: #39c5bb; border-bottom: 2px solid #39c5bb;">GAME</h3>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($cot_2_game as $item): ?>
                            <li style="padding: 5px;"><a href="#" style="text-decoration: none; color: #333;"><?php echo $item; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div style="width: 33%; float: left;">
                    <h3 style="color: #39c5bb; border-bottom: 2px solid #39c5bb;">CHARACTER</h3>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($cot_3_nhanvat as $item): ?>
                            <li style="padding: 5px;"><a href="#" style="text-decoration: none; color: #333;"><?php echo $item; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

        <a href="instock.php"
            style="background: transparent; color: #39c5bb; border: 2px solid #39c5bb; padding: 8px 20px; font-weight: bold; text-decoration: none; border-radius: 5px;">
            HÀNG MỚI PHÁT HÀNH
        </a>
        

    </div>

    <div style="display: flex; gap: 15px; align-items: center; color: white; font-size: 14px;">

        <div style="display: flex; align-items: center; border: 2px solid #39c5bb; border-radius: 30px; padding: 5px 15px;">
            <img src="img/hotline.jpg" alt="Phone" style="width: 30px; height: 30px; object-fit: contain; margin-right: 8px;">
            <span style="font-weight: bold; color: #39c5bb;">Hot Line: 0934.449.636</span>
        </div>

        <a href="cart.php" style="display: flex; align-items: center; text-decoration: none; color: white;
                  border: 2px solid #39c5bb; border-radius: 30px; padding: 5px 15px; transition: 0.3s;"
            onmouseover="this.style.backgroundColor='#39c5bb'; this.style.boxShadow='0 0 10px #39c5bb'"
            onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none'">
            <img src="img/buy0.jpg" alt="Cart" style="width: 60px; height: 30px; object-fit: contain; margin-right: 8px; mix-blend-mode: screen;">
            <?php //mix-blend-mode: screen; => làm mờ nút
            ?>
            <span>Giỏ hàng (0)</span>
        </a>

        <a href="login.php" style="display: flex; align-items: center; text-decoration: none; color: white;
                  border: 2px solid #39c5bb; border-radius: 30px; padding: 5px 15px; transition: 0.3s;"
            onmouseover="this.style.backgroundColor='#39c5bb'; this.style.boxShadow='0 0 10px #39c5bb'"
            onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none'">
            <img src="img/user.jpg" alt="User" style="width: 30px; height: 30px; object-fit: contain; margin-right: 8px; mix-blend-mode: screen;">
            <span>Đăng nhập</span>
        </a>
    </div>
</div>

<script>
    function hienMenu() {
        document.getElementById("bangmenu").style.display = "block";
    }

    function anMenu() {
        document.getElementById("bangmenu").style.display = "none";
    }
</script>