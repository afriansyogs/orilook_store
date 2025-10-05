<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'brand_name' => 'Patrobas',
            'brand_Img' => NULL,
        ],
        [
            'brand_name' => 'Ventela',
            'brand_Img' => NULL,
        ],
        [
            'brand_name' => 'Compas',
            'brand_Img' => NULL,
        ],
        [
            'brand_name' => 'Brodos',
            'brand_Img' => NULL,
        ],
    );
    }
}