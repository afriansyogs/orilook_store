<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class StaticDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $menuData = [
            ['name' => 'Home', 'url' => url('/')], 
            ['name' => 'Product', 'url' => url('/product')], 
            ['name' => 'About', 'url' => url('/about')], 
            ['name' => 'Contact', 'url' => url('/contact')], 
            ['name' => 'Box', 'url' => url('/order'), 'icon' => 'fa-solid fa-box'], 
            ['name' => 'Cart', 'url' => url('/cart'), 'icon' => 'fa-solid fa-cart-shopping'], 
            ['name' => 'User', 'url' => url('/profile'), 'icon' => 'fa-solid fa-user'], 
        ];

        $sectionData = [
            ['name' => 'Story', 'url' => url('/about/#aboutStory')], 
            ['name' => 'brand', 'url' => url('/about/#brandSection')], 
            ['name' => 'category', 'url' => url('/#categorySection')], 
            ['name' => 'platform', 'url' => url('/about/#platformSection')], 
            ['name' => 'review', 'url' => url('/#reviewSection')], 
        ];

        $statisticData = [
            [ 'descriptionStatistic' => "Toko dengan banyak penjual aktif yang menyediakan sepatu berkualitas.", 'iconStatistic' => "fa-shop" ],
            [ 'descriptionStatistic' => "Penjualan produk bulanan yang terus meningkat.", 'iconStatistic' => "fa-money-bill-trend-up" ],
            [ 'descriptionStatistic' => "Banyak pelanggan aktif yang mempercayai Orilook Store untuk kebutuhan sepatu mereka.", 'iconStatistic' => "fa-users" ],
            [ 'descriptionStatistic' => "Penjualan tahunan yang signifikan dengan kualitas yang terjamin.", 'iconStatistic' => "fa-sack-dollar" ],
        ];        

        $serviceData = [
            [ 'title' => "ONLINE STORE TERPERCAYA", 'description' => "Toko yang aman dan tepercaya", 'icon' => "fa-solid fa-building-shield fa-xl" ],
            [ 'title' => "CUSTOMER SERVICE YANG BAIK", 'description' => "Dukungan pelanggan yang ramah", 'icon' => "fa-solid fa-headset fa-xl" ],
            [ 'title' => "GARANSI UANG KEMBALI", 'description' => "Kami mengembalikan uang jika barang tidak sesuai", 'icon' => "fa-solid fa-hand-holding-dollar fa-xl" ],
        ];

        $brandPartnerData = [
            ['name' => "Patrobas", 'img' => "assets/img/logo_patrobas_bg_black.png", 'url' => "https://www.patrobas.id/"],
            ['name' => "Ventela", 'img' => "assets/img/logo_ventela.jpg", 'url' => "https://ventelashoes.com"],
            ['name' => "AeroStreet", 'img' => "assets/img/logo_aerostreet.png", 'url' => "https://aerostreet.id/"],
            ['name' => "Johnson", 'img' => "assets/img/johnson.png", 'url' => "https://johnson.id/"],
            ['name' => "Compass", 'img' => "assets/img/compas_logo.jpg", 'url' => "https://sepatucompass.com/"],
            ['name' => "Brodo", 'img' => "assets/img/logo_brodo.png", 'url' => "https://bro.do/"],
            ['name' => "Warior", 'img' => "assets/img/warior_logo.png", 'url' => "https://www.sepatuwarrior.com/"],
        ];

        $contactData = [
            ['name' => "Telp", 'icon' => "fa-solid fa-phone", 'contact' => "0896-7038-8783", 'url' => ""],
            ['name' => "Instagram", 'icon' => "fa-brands fa-instagram", 'contact' => "@orilookstore", 'url' => "https://www.instagram.com/orilookstore?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="],
            ['name' => "X-Twiter", 'icon' => "fa-brands fa-x-twitter", 'contact' => "@orilookstore", 'url' => "https://x.com/orilookstore"],
            ['name' => "WhatsApp", 'icon' => "fa-brands fa-whatsapp", 'contact' => "0896-7038-8783", 'url' => "https://api.whatsapp.com/message/5NGXU3KSKFWEH1?autoload=1&app_absent=0"],
        ];
        
        $platformsData = [
            ['platform' => "Tokopedia", 'name' => "orilook.store", 'img' => "assets/img/tokped_logo.png", 'url' => "https://www.tokopedia.com/orilookstoree?aff_unique_id=VjkFBru-b3LP8-F10KK0IdpoQnqZGHWGL05G4ckjORjGRsQuV2SC8d-EqnXgC8X1QcQtEw%3D%3D&channel=salinlink&_branch_match_id=1094498998353970860&utm_source=salinlink&utm_campaign=affiliateshare-shop-VjkFBru-b3LP8-F10KK0IdpoQnqZGHWGL05G4ckjORjGRsQuV2SC8d-EqnXgC8X1QcQtEw%253D%253D-0-0-081222&utm_medium=affiliate-share&_branch_referrer=H4sIAAAAAAAAA8soKSkottLXL8nPzi9ITclM1MvJzMvWTzar8gsLMK10LEuyrytKTUstKsrMS49PKsovL04tsnXOKMrPTQUAkKHqhjwAAAA%3D"],
            ['platform' => "Shopee", 'name' => "ORILOOK_STORE", 'img' => "assets/img/shopee_logo.png", 'url' => "https://shopee.co.id/shop/212360210?utm_campaign=-&utm_content=----&utm_medium=affiliates&utm_source=an_11038500000&utm_term=cjyq2g2qygrj"],
            ['platform' => "TikTok Shop", 'name' => "orilook.store", 'img' => "assets/img/tiktokShop_logo.png", 'url' => "https://www.tiktok.com/@orilook.store"],
            ['platform' => "Lazada", 'name' => "ORILOOKSTORE", 'img' => "assets/img/lazada_logo.png", 'url' => "https://www.lazada.co.id/shop/orilookstore?spm=a211g0.store_hp.top.share&dsource=share&laz_share_info=295072180_100_200_400361121135_262147484_null&laz_token=d1d02b3e3b6861dc4b5a4627ec5315b8&exlaz=e_CcUyBUcZoUTGip8qo24MCZxWHrQEPErDOJB%2FxtnrDRrCXvTKdNAtBVrqffc5%2BhYHK4nzvf3NM5dRbYfyD9SP3xTFJKgxdWvbD3WnASIas1Y%3D&sub_aff_id=social_share&sub_id2=295072180&sub_id3=400361121135&sub_id6=CPI_EXLAZ"],
        ];

        View::share([
            'menuData' => $menuData,
            'sectionData' => $sectionData,
            'statisticData' => $statisticData,
            'serviceData' => $serviceData,
            'brandPartnerData' => $brandPartnerData,
            'contactData' => $contactData,
            'platformsData' => $platformsData
        ]);
    }
}
