<div style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
    
    <form action="" method="GET">
        
        <h3 style="margin-top: 0; border-left: 4px solid #39c5bb; padding-left: 10px; color: #333;">
            LỌC GIÁ
        </h3>
        <ul style="list-style: none; padding: 0; color: #555; margin-bottom: 20px;">
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="muc_gia" value="1" id="p1" style="margin-right: 10px; cursor: pointer;" <?php if (isset($_GET['muc_gia']) && $_GET['muc_gia'] == 1) echo "checked"; ?>>
                <label for="p1" style="cursor: pointer;">Dưới 500,000đ</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="muc_gia" value="2" id="p2" style="margin-right: 10px; cursor: pointer;" <?php if (isset($_GET['muc_gia']) && $_GET['muc_gia'] == 2) echo "checked"; ?>>
                <label for="p2" style="cursor: pointer;">500k - 2 Triệu</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="muc_gia" value="3" id="p3" style="margin-right: 10px; cursor: pointer;" <?php if (isset($_GET['muc_gia']) && $_GET['muc_gia'] == 3) echo "checked"; ?>>
                <label for="p3" style="cursor: pointer;">2 Triệu - 5 Triệu</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="muc_gia" value="4" id="p4" style="margin-right: 10px; cursor: pointer;" <?php if (isset($_GET['muc_gia']) && $_GET['muc_gia'] == 4) echo "checked"; ?>>
                <label for="p4" style="cursor: pointer;">Trên 5 Triệu</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="muc_gia" value="all" id="p_all" style="margin-right: 10px; cursor: pointer;" <?php if (!isset($_GET['muc_gia']) || $_GET['muc_gia'] == 'all') echo "checked"; ?>>
                <label for="p_all" style="cursor: pointer;">Xem tất cả giá</label>
            </li>
        </ul>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

        <h3 style="margin-top: 0; border-left: 4px solid #39c5bb; padding-left: 10px; color: #333;">
            NHÂN VẬT
        </h3>
        <ul style="list-style: none; padding: 0; color: #555; margin-bottom: 20px;">
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="chon_char" value="miku" id="c1" style="margin-right: 10px; cursor: pointer;" <?php if(isset($_GET['chon_char']) && $_GET['chon_char'] == 'miku') echo "checked"; ?>>
                <label for="c1" style="cursor: pointer;">Hatsune Miku</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="chon_char" value="kurumi" id="c2" style="margin-right: 10px; cursor: pointer;" <?php if(isset($_GET['chon_char']) && $_GET['chon_char'] == 'kurumi') echo "checked"; ?>>
                <label for="c2" style="cursor: pointer;">Tokisaki Kurumi</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="chon_char" value="shiro" id="c3" style="margin-right: 10px; cursor: pointer;" <?php if(isset($_GET['chon_char']) && $_GET['chon_char'] == 'shiro') echo "checked"; ?>>
                <label for="c3" style="cursor: pointer;">Shiro</label>
            </li>
            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                <input type="radio" name="chon_char" value="all" id="c_all" style="margin-right: 10px; cursor: pointer;" <?php if(!isset($_GET['chon_char']) || $_GET['chon_char'] == 'all') echo "checked"; ?>>
                <label for="c_all" style="cursor: pointer;">Tất cả nhân vật</label>
            </li>
        </ul>

        <button type="submit" 
                style="width: 100%; background: #39c5bb; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s;"
                onmouseover="this.style.backgroundColor='#2ca99f'"
                onmouseout="this.style.backgroundColor='#39c5bb'">
            XÁC NHẬN
        </button>

    </form>
</div>