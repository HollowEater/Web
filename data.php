<?php

// Header
$cot_1_anime = [
    "One Piece",
    "Naruto",
    "Dragon Ball",
    "Kimetsu no Yaiba",
    "Gundam",
    "Attack on Titan"
];

$cot_2_game = [
    "Genshin Impact",
    "Honkai Star Rail",
    "Fate/Grand Order"
];

$cot_3_nhanvat = [
    "Hatsune Miku",
    "Raiden Shogun",
    "Son Goku",
    "Rem & Ram"
];

// Banner
$banners = [
    [
        "id" => 1,
        "mau_nen" => "#ffbd59",
        "tieu_de_nho" => "HÀNG SẴN",
        "tieu_de_lon" => "SHIP NGAY",
        "hinh_anh" => ["miku0.jpg", "elaina0.jpg", "albedo0.jpg", "hoshino0.jpg", "frieren0.jpg", "chloe0.jpg"]
    ],
    [
        "id" => 2,
        "mau_nen" => "#ff6b6b",
        "tieu_de_nho" => "HÀNG ĐẶT TRƯỚC",
        "tieu_de_lon" => "ĐẢM BẢO GIÁ TỐT",
        "hinh_anh" => ["miku1.jpg", "kurumi1.jpg", "shiro1.jpg", "albedo1.jpg", "firefly1.jpg", "maomao1.jpg"]
    ],
    [
        "id" => 3,
        "mau_nen" => "#54a0ff",
        "tieu_de_nho" => "HÀNG CHÍNH HÃNG",
        "tieu_de_lon" => "DỄ DÀNG THANH TOÁN",
        "hinh_anh" => ["miku2.jpg", "kurumi2.jpg", "albedo2.jpg", "shiro2.jpg", "alisa2.jpg", "azurlane2.jpg"]
    ]
];

//featured
    $ds_nhanvat = [
    ["ten" => "Hatsune Miku", "hinh" => "miku.jpg"],
    ["ten" => "Elaina", "hinh" => "elaina.jpg"],
    ["ten" => "Tokisaki Kurumi", "hinh" => "kurumi.jpg"],
    ["ten" => "Shiro", "hinh" => "shiro.jpg"],
    ["ten" => "Frieren", "hinh" => "frieren.jpg"],

    ["ten" => "Albedo", "hinh" => "albedo.jpg"],
    ["ten" => "Hololive", "hinh" => "hololive.jpg"],
    ["ten" => "Rem", "hinh" => "rem.jpg"],
    ["ten" => "Marin", "hinh" => "marin.jpg"],
    ["ten" => "Hoshino Ai", "hinh" => "hoshino.jpg"]
    ];

    $tin_tuc = [
    "tieu_de" => "Chúc mừng Ngày Nhà Giáo Việt Nam 20/11",
    "hinh" => "2011.jpg",
    "ngay_dang" => "20/11/2025"
    ];

//flashsale
    $flash_sale = [
    [
        "ten" => "Kitagawa Marin Black Lobelia Ver. Espresto",
        "hinh" => "marin.jpg", 
        "gia_moi" => "490.000đ",
        "gia_cu" => "530.000đ",
        "giam" => "-8%"
    ],
    [
        "ten" => "Hatsune Miku HATSUNE MIKU EXPO 10th Anniversary Ver 1/7",
        "hinh" => "miku.jpg",
        "gia_moi" => "5.900.000đ",
        "gia_cu" => "6.950.000đ",
        "giam" => "-15%"
    ],
    [
        "ten" => "Ai Hoshino 1/8 - Oshi no Ko | Medicom Toy Figure",
        "hinh" => "hoshino.jpg",
        "gia_moi" => "3.120.000đ",
        "gia_cu" => "3.900.000đ",
        "giam" => "-20%"
    ],
    [
        "ten" => "Anya Forger Block Calendar - Spy x Family",
        "hinh" => "anya.jpg",
        "gia_moi" => "1.250.000đ",
        "gia_cu" => "1.600.000đ",
        "giam" => "-22%"
    ],
    [
        "ten" => "Kurumi Tokisaki Rasiel Ver. 1/7 - Date A Live",
        "hinh" => "kurumi.jpg",
        "gia_moi" => "5.520.000đ",
        "gia_cu" => "6.500.000đ",
        "giam" => "-15%"
    ]
];

//product
$hot_products = [
    [
        "ten" => "POP UP PARADE Rem L size - Re:ZERO",
        "hinh" => "rem.jpg",
        "gia" => "1.800.000đ",
        "tag" => "In Stock",    
    ],
    [
        "ten" => "TENITOL Hatsune Miku GALAXY LIVE Ver",
        "hinh" => "miku0.jpg",
        "gia" => "1.700.000đ",
        "tag" => "Pre-order",  
    ],
    [
        "ten" => "Keqing Driving Thunder 1/7 Scale",
        "hinh" => "keqing.jpg",
        "gia" => "3.800.000đ",
        "tag" => "In Stock",
    ]
];

$char_goods = [
    [
        "ten" => "Wondrous Travels Series Nahida - Genshin Impact",
        "hinh" => "nahida.jpg",
        "gia" => "1.300.000đ",
        "tag" => "In Stock",
    ],
    [
        "ten" => "Nendoroid 850 Snow Miku Crane Priestess Ver",
        "hinh" => "miku.jpg",
        "gia" => "2.900.000đ",
        "tag" => "In Stock",
    ],
    [
        "ten" => "Nhồi Bông Marin Kitagawa Gentle",
        "hinh" => "marin.jpg",
        "gia" => "1.290.000đ",
        "tag" => "Pre-order",
    ]
];

//instock
$in_stock = [
    [
        "id" => 101,
        "ten" => "Hatsune Miku Prisma Wing PWPCL-07DX 1/4 - DX Version",
        "hinh" => "miku1.jpg", 
        "gia" => "37.000.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 102,
        "ten" => "Vocaloid - Hatsune Miku - 1/7 - Digital Stars 2022 Ver",
        "hinh" => "miku2.jpg",
        "gia" => "11.000.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 103,
        "ten" => "Hatsune Miku JAPAN LIVE TOUR 2025 BLOOMING 1/7",
        "hinh" => "miku3.jpg",
        "gia" => "9.900.000đ",
        "gia_cu" => "10.900.000đ", 
        "giam_gia" => "-9%",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 104,
        "ten" => "Hatsune Miku - B-style 1/4 Bunny Ver",
        "hinh" => "miku4.jpg",
        "gia" => "9.750.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 105,
        "ten" => "Hatsune Miku Japan Tour 2023 ~ Thunderbolt 1/7 - Vocaloid",
        "hinh" => "miku5.jpg",
        "gia" => "9.500.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 106,
        "ten" => "Hatsune Miku 15th Anniversary Ver. 1/7 - Character Vocal Series 01",
        "hinh" => "miku6.jpg",
        "gia" => "8.100.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 107,
        "ten" => "Hatsune Miku Symphony 5th Anniversary Ver. 1/1",
        "hinh" => "miku7.jpg",
        "gia" => "8.900.000đ",
        "gia_cu" => "9.000.000đ",
        "giam_gia" => "-1%",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 108,
        "ten" => "Hatsune Miku Digital Stars 2023 ver 1/7 - Vocaloid",
        "hinh" => "miku8.jpg",
        "gia" => "8.500.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 109,
        "ten" => "Hatsune Miku Shooting Star a la Mode Ver 1/7 Scale",
        "hinh" => "miku9.jpg",
        "gia" => "6.500.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 110,
        "ten" => "Hatsune Miku feat. Yoneyama Mai 1/7 - Character Vocal Series 01",
        "hinh" => "miku10.jpg",
        "gia" => "7.300.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 111,
        "ten" => "Hatsune Miku Rosuuri Ver. 1/7 - Vocaloid",
        "hinh" => "miku11.jpg",
        "gia" => "7.300.000đ",
        "gia_cu" => "7.900.000đ",
        "giam_gia" => "-8%",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 112,
        "ten" => "Hatsune Miku HATSUNE MIKU EXPO 10th Anniversary Ver 1/7",
        "hinh" => "miku12.jpg",
        "gia" => "6.950.000đ",
        "giam_gia" => "",
        "nhan_vat" => "miku"
    ],
    [
        "id" => 201,
        "ten" => "Kurumi Tokisaki Swimsuit Ver. 1/2.5 - Date A Live Series",
        "hinh" => "kurumi1.jpg",
        "gia" => "30.000.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 202,
        "ten" => "KDcolle Tokisaki Kurumi Oiran Ver. 1/7 - Date A Live Fragment Date A Bullet",
        "hinh" => "kurumi2.jpg",
        "gia" => "6.900.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 203,
        "ten" => "Kurumi Tokisaki Rasiel Ver. 1/7 - Date A Live",
        "hinh" => "kurumi3.jpg",
        "gia" => "6.500.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 204,
        "ten" => "Kurumi Tokisaki Fantasia 30th Anniversary ver Renewal package",
        "hinh" => "kurumi4.jpg",
        "gia" => "4.950.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 205,
        "ten" => "Tokisaki Kurumi 1/8 - Gekijouban Date A Live Mayuri Judgement",
        "hinh" => "kurumi5.jpg",
        "gia" => "4.900.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 206,
        "ten" => "Kurumi Tokisaki Wa-Bunny 1/7 - Date A Live V",
        "hinh" => "kurumi6.jpg",
        "gia" => "4.800.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 207,
        "ten" => "KDcolle Tokisaki Kurumi Cat Ears ver. - Date A Live",
        "hinh" => "kurumi7.jpg",
        "gia" => "4.500.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 208,
        "ten" => "Light Novel Edition Kurumi Tokisaki: Wedding Dress Ver.",
        "hinh" => "kurumi8.jpg",
        "gia" => "4.350.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 209,
        "ten" => "Tokisaki Kurumi Nightwear Ver. Renewal Taito Online Crane Limited",
        "hinh" => "kurumi9.jpg",
        "gia" => "790.000đ",
        "giam_gia" => "",
        "nhan_vat" => "kurumi"
    ],          
    [
        "id" => 210,
        "ten" => "Date A Live Fragment: Date A Bullet - Tokisaki Kurumi",
        "hinh" => "kurumi10.jpg",
        "gia" => "450.000đ",
        "gia_cu" => "530.000đ",
        "giam_gia" => "15%",
        "nhan_vat" => "kurumi"
    ],
    [
        "id" => 301,
        "ten" => "No Game No Life - Shiro - Aqua Float Girls",
        "hinh" => "shiro1.jpg",
        "gia" => "480.000đ",
        "giam_gia" => "",
        "nhan_vat" => "shiro"
    ],
    [
        "id" => 302,
        "ten" => "Shiro Hot Spring Ver. 1/7 - No Game No Life",
        "hinh" => "shiro2.jpg",
        "gia" => "5.900.000đ",
        "giam_gia" => "",
        "nhan_vat" => "shiro"
    ],
    [
        "id" => 303,
        "ten" => "Schwi Dola & Shiro No Game No Life: Zero 1/7",
        "hinh" => "shiro3.jpg",
        "gia" => "4.400.000đ",
        "giam_gia" => "",
        "nhan_vat" => "shiro"
    ],
    [
        "id" => 304,
        "ten" => "Shiro Yuu Kamiya Art Works 1/7 - No Game No Life",
        "hinh" => "shiro4.jpg",
        "gia" => "3.900.000đ",
        "giam_gia" => "",
        "nhan_vat" => "shiro"
    ],    
    [
        "id" => 305,
        "ten" => "TENITOL Shiro - No Game No Life",
        "hinh" => "shiro5.jpg",
        "gia" => "1.350.000đ",
        "giam_gia" => "",
        "nhan_vat" => "shiro"
    ],        
];
?>

