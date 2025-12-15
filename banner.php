<div id="banner-container" style="position: relative; width: 100%; overflow: hidden;">

    <?php foreach ($banners as $index => $slide): ?>
        
        <div class="mySlides fade" style="display: 
        <?php echo ($index == 0) ? 'block' : 'none'; // Hiện banner đầu trước ?>; 
        background-color: <?php echo $slide['mau_nen']; // Chạy đi lấy màu ?>; 
        padding: 40px 0; text-align: center; width: 100%;">
            
            <h3 style="color: white; font-size: 24px; font-style: italic; margin-bottom: 5px;">
                <?php echo $slide['tieu_de_nho']; ?>
            </h3>
            <h1 style="color: white; font-size: 48px; font-weight: bold; margin-top: 0; margin-bottom: 30px;">
                <?php echo $slide['tieu_de_lon']; ?>
            </h1>

            <div style="display: flex; justify-content: center; gap: 15px; padding: 0 50px;">
                <?php foreach ($slide['hinh_anh'] as $img): // Chạy đi lấy hình ?>
                    <div style="width: 150px; height: 220px; background: white; padding: 5px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                        <img src="imgbanner/<?php echo $img; // Bỏ hình vô ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        <?php // object-fit: cover => cắt phần thừa của hình ?>
                        <?php // box-shadow: 0 4px 8px => ngang-dọc-độ nhòe ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top: 30px;">
                <a href="instock.php" style="background: transparent; border: 2px solid white; color: white; padding: 10px 30px; font-size: 18px; 
                          font-weight: bold; border-radius: 25px; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.3s;"
                   onmouseover="this.style.backgroundColor='white'; this.style.color='<?php echo $slide['mau_nen']; /*đổi theo màu*/ ?>'" 
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='white'">
                   <?php // onmouseover => di chuột vào ?>
                    SHOP NOW
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <div style="text-align: center; position: absolute; bottom: 15px; width: 100%; z-index: 100;">
        <?php //z-index: 100 => kéo trục z lên làm nút không bị trùng banner như bên Unity ?>
        <?php foreach ($banners as $index => $slide): // Chạy lấy index cho chấm ?>
            <span class="dot" onclick="currentSlide(<?php echo $index + 1; ?>)" 
                  style="cursor: pointer; height: 12px; width: 12px; margin: 0 5px; background-color: rgba(255,255,255,0.5); border-radius: 50%; display: inline-block;">
            </span>
        <?php endforeach; ?>
    </div>

</div>

<script>
    let slideIndex = 0;
    let timer;
    showSlides(); // Gọi hàm
    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) { // Ẩn slide
            slides[i].style.display = "none";  
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}    
        for (i = 0; i < dots.length; i++) { // Trả màu chấm
            dots[i].style.backgroundColor = "rgba(255,255,255,0.5)";
        }
        slides[slideIndex-1].style.display = "block"; // Hiện slide
        dots[slideIndex-1].style.backgroundColor = "white"; // Tô chấm
        timer = setTimeout(showSlides, 4000); // Gán timer, 3s chuyển
    }
    function currentSlide(n) {
        clearTimeout(timer); // Hủy hẹn giờ
        slideIndex = n - 1; // Gán thứ tự
        showSlides();
    }
</script>