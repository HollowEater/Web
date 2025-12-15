<div style="max-width: 1200px; margin: 50px auto; display: flex; gap: 30px; padding: 0 20px; font-family: Arial, sans-serif;">

    <div style="flex: 3;">
        
        <h2 style="border-left: 5px solid #39c5bb; padding-left: 15px; color: #333;">
            NHÂN VẬT YÊU THÍCH
        </h2>

        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
            
            <?php foreach ($ds_nhanvat as $nv): ?>
                <div style="width: 18%; border: 2px solid #39c5bb; border-radius: 10px; text-align: center; cursor: pointer; transition: 0.3s;"
                     onmouseover="this.style.boxShadow='0 5px 15px rgba(57, 197, 187, 0.3)'; this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                    <?php //this.style.transform='translateY(-5px) => nâng thẻ lên ?>
                    <div style="width: 100%; height: 120px; padding: 10px; box-sizing: border-box;">
                        <img src="imgfeatured/<?php echo $nv['hinh']; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>

                    <div style="padding: 10px 5px; font-weight: bold; font-size: 13px; color: #333;">
                        <?php echo $nv['ten']; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <div style="flex: 1;">
        
        <h2 style="border-left: 5px solid #333; padding-left: 15px; color: #333;">
            TIN TỨC MỚI
        </h2>

        <div style="background: white; border: 2px solid #39c5bb; padding: 15px; border-radius: 10px;">
            
            <div style="width: 100%; height: 300px; border-radius: 5px; margin-bottom: 15px;">
                <img src="imgfeatured/<?php echo $tin_tuc['hinh']; ?>" style="width: 100%; height: 100%; object-fit: contain;">
            </div>

            <h3 style="margin: 0 0 10px 0; font-size: 16px;">
                <a href="#" style="text-decoration: none; color: #333;" 
                   onmouseover="this.style.color='#39c5bb'" 
                   onmouseout="this.style.color='#333'">
                    <?php echo $tin_tuc['tieu_de']; ?>
                </a>
            </h3>

            <div style="font-size: 12px; color: #888;">
                <?php echo $tin_tuc['ngay_dang']; ?>
            </div>
        </div>
    </div>
</div>