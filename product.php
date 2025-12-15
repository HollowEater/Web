<div style="max-width: 1200px; margin: 50px auto; display: flex; gap: 30px; padding: 0 20px; font-family: Arial, sans-serif;">

    <div style="flex: 1;">
        <h2 style="border-left: 5px solid #333; padding-left: 15px; color: #333; margin-bottom: 20px;">
            HOT PRODUCTS
        </h2>

        <div style="display: flex; gap: 15px;">
            <?php foreach ($hot_products as $sp): ?>
                <div style="flex: 1; border: 1px solid #39c5bb; border-radius: 5px; position: relative;">

                    <?php if ($sp['tag'] == 'Pre-order'): ?>
                        <div style="position: absolute; background: #2ecc71; color: white; padding: 3px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 0 0 5px 0;">Pre-order</div>
                    <?php elseif ($sp['tag'] == 'In Stock'): ?>
                        <div style="position: absolute; background: #3498db; color: white; padding: 3px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 0 0 5px 0;">In Stock</div>
                    <?php endif; ?>

                    <a href="#" style="display: block; position: relative; overflow: hidden"
                        onmouseover="this.children[1].style.opacity='1'; this.children[0].style.transform='scale(1.1)'"
                        onmouseout="this.children[1].style.opacity='0'; this.children[0].style.transform='scale(1)'">
                        <?php //this.children[1] => bật tắt bảng mờ 
                        ?>
                        <img src="imgproduct/<?php echo $sp['hinh']; ?>" style="width: 100%; height: 200px; object-fit: contain; transition: 0.5s;">

                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0;">
                            <?php //opacity: 0 => ẩn bảng mờ 
                            ?>
                            <div style="width: 50px; height: 50px; background: white; border-radius: 50%; box-shadow: 10px rgba(0, 0, 0, 1); display: flex; align-items: center; justify-content: center;">
                                <img src="imgproduct/miku2.jpg" style="width: 80%; height: 80%; object-fit: contain; border-radius: 50%;">
                            </div>
                        </div>
                    </a>

                    <div style="padding: 10px;">
                        <h3 style="font-size: 13px; color: #333; margin: 0 0 5px 0;">
                            <a href="#" style="text-decoration: none; color: #333;"><?php echo $sp['ten']; ?></a>
                        </h3>
                        <div style="color: #d32f2f; font-weight: bold; font-size: 14px;">
                            <?php echo $sp['gia']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div style="flex: 1;">
        <h2 style="border-left: 5px solid #333; padding-left: 15px; color: #333; margin-bottom: 20px;">
            CHARACTER GOODS
        </h2>

        <div style="display: flex; gap: 15px;">
            <?php foreach ($char_goods as $sp): ?>
                <div style="flex: 1; border: 1px solid #39c5bb; border-radius: 5px; position: relative;">

                    <?php if ($sp['tag'] == 'Pre-order'): ?>
                        <div style="position: absolute; background: #2ecc71; color: white; padding: 3px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 0 0 5px 0;">Pre-order</div>
                    <?php elseif ($sp['tag'] == 'In Stock'): ?>
                        <div style="position: absolute; background: #3498db; color: white; padding: 3px 10px; font-size: 11px; font-weight: bold; z-index: 2; border-radius: 0 0 5px 0;">In Stock</div>
                    <?php endif; ?>

                    <a href="#" style="display: block; position: relative; overflow: hidden;"
                        onmouseover="this.children[1].style.opacity='1'; this.children[0].style.transform='scale(1.1)'"
                        onmouseout="this.children[1].style.opacity='0'; this.children[0].style.transform='scale(1)'">

                        <img src="imgproduct/<?php echo $sp['hinh']; ?>" style="width: 100%; height: 200px; object-fit: cover; transition: 0.5s;">

                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0;">
                            <?php //opacity: 0 => ẩn bảng mờ 
                            ?>
                            <div style="width: 50px; height: 50px; background: white; border-radius: 50%; box-shadow: 10px rgba(0, 0, 0, 1); display: flex; align-items: center; justify-content: center;">
                                <img src="imgproduct/miku2.jpg" style="width: 80%; height: 80%; object-fit: contain; border-radius: 50%;">
                            </div>
                        </div>
                    </a>

                    <div style="padding: 10px;">
                        <h3 style="font-size: 13px; color: #333; margin: 0 0 5px 0;">
                            <a href="#" style="text-decoration: none; color: #333;"><?php echo $sp['ten']; ?></a>
                        </h3>
                        <div style="color: #d32f2f; font-weight: bold; font-size: 14px;">
                            <?php echo $sp['gia']; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>