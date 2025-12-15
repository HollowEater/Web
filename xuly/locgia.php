<?php
$danh_sach_hien_thi = isset($in_stock) ? $in_stock : [];

// Chỉ chạy lọc khi người dùng có bấm chọn giá hoặc nhân vật
if (isset($_GET['muc_gia']) || isset($_GET['chon_char'])) {
    
    // Lấy giá trị từ URL (Nếu không chọn thì mặc định là 'all')
    $chon_gia = isset($_GET['muc_gia']) ? $_GET['muc_gia'] : 'all';
    $chon_char = isset($_GET['chon_char']) ? $_GET['chon_char'] : 'all';
    
    $ket_qua = []; 

    if (!empty($in_stock)) {
        foreach ($in_stock as $sp) {
            // Xóa dấu chấm và chữ đ, chuyển thành số nguyên
            $gia_so = intval(str_replace(['.', 'đ'], '', $sp['gia'])); 
            
            // KIỂM TRA GIÁ
            $pass_gia = false;
            if ($chon_gia == 1 && $gia_so < 500000) $pass_gia = true;
            elseif ($chon_gia == 2 && $gia_so >= 500000 && $gia_so <= 2000000) $pass_gia = true;
            elseif ($chon_gia == 3 && $gia_so > 2000000 && $gia_so <= 5000000) $pass_gia = true;
            elseif ($chon_gia == 4 && $gia_so > 5000000) $pass_gia = true;
            elseif ($chon_gia == 'all') $pass_gia = true;

            // KIỂM TRA NHÂN VẬT
            $pass_char = false;
            if ($chon_char == 'all') {
                $pass_char = true;
            } 
            elseif (isset($sp['nhan_vat']) && $sp['nhan_vat'] == $chon_char) {
                $pass_char = true;
            }

            // QUYẾT ĐỊNH CUỐI CÙNG
            if ($pass_gia && $pass_char) {
                $ket_qua[] = $sp;
            }
        }
    }
    // Cập nhật danh sách hiển thị
    $danh_sach_hien_thi = $ket_qua;
}// Ý nghĩa file này lọc điều kiện lấy từ data bỏ vào $danh_sach_hien_thi và dùng nó xuất cái sp đạt điều kiện bên instock
?>