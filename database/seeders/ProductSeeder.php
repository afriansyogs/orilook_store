<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1, 
            'brand_id' => 1,    
            'product_name' => 'Sepatu Patrobas Glare Denim Low Dark Blue',
            'product_img' => 'storage/products/kaos_polos.jpg', 
            'slug' => 'patrobas-glare-denim-low', 
            'price' => 360000,
            'discount' => 51000, 
            'discounted_price' => 309000, 
            'description' => '(PASTIKAN DAHULU SIZE NYA SEBELUM MEMBELI, SIZE CHART + CARA UKUR DI BAWAH)

            Terinspirasi dari motif jahitan khas Jepang, kini Patrobas GLARE hadir dengan kombinasi bahan Denim dengan pola Sashiko di bagian Heel Counter. Penggunaan ornamen berupa Pull Tab memberi kemudahan ketika akan menggunakan sepatu. Patrobas GLARE dirancang khusus untuk Geng Patrobas yang mengutamakan kenyamanan ditengah aktivitas yang tinggi, dan berani tampil berbeda
            
            SPESIFIKASI
            13 oz Denim
            13 oz Denim Heel Counter with Embroidery Sashiko Pattern
            Reflective on Upper Logo
            Embossed Eyelet with Patrobas Logotype
            Upgraded Patrofoam Tech Insole
            Pull Tab Canvas
            Breathable Mesh Lining
            Rubber Sole & Foxing
            
            Size Chart
            SIZE 35 = 24 cm
            SIZE 36 = 24,5 cm
            SIZE 37 = 25,2 cm
            SIZE 38 = 25,9 cm
            SIZE 39 = 26,3 cm
            SIZE 40 = 27,0 cm
            SIZE 41 = 27,7 cm
            SIZE 42 = 28,1 cm
            SIZE 43 = 28,5 cm
            SIZE 44 = 29 cm
            SIZE 45 = 29,5 cm
            
            Cara Pengukuran :
            Panjang kaki (Tumit ke Ujung), ditambahkan 1 cm (tight fit) s/d 1,5cm (loose fit)
            
            Thanks,
            
            ORILOOKSTORE',
        ]);
        Product::create([
            'category_id' => 1, 
            'brand_id' => 2,    
            'product_name' => 'Ventela Reborn Low Black Natural',
            'product_img' => 'storage/products/kemeja_flanel.jpg', 
            'slug' => 'ventela-reborn-low-black-natural', 
            'price' => 380000,
            'discount' => 51000, 
            'discounted_price' => 329000, 
            'description' => 'Ventela Shoes
            Kualitas Bintang ️️️️️
            100% ORIGINAL
            
            Size Chart (Patokan Size)
            33 : 21.2 cm
            34 : 21.8 cm
            35 : 22.5 cm
            36 : 23.1 cm
            37 ; 23.8 cm
            38 ; 24.7 cm
            39 ; 25.2 cm
            40 ; 26.1 cm
            41 ; 26.5 cm
            42 ; 27.4 cm
            43 ; 28.3 cm
            44 ; 28.8 cm
            [ NOTE : Size Chart Merupakan Panjang Insole / Panjang Kaki ]
            
            Packing Dijamin Aman Sampai Tujuan Menggunakan Bubble Wrap Tebal [GRATIS]
            
            Pengirim Setiap Senin sd Sabtu
            Pembayaran Diatas Jam 14.00 Akan Dikirim H+1
            Sebelum Jam 14.00 Dikirim Hari Itu Juga
            
            Happy Shopping :)
            Terima Kasih',
        ]);
    }
}
