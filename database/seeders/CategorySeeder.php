<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'category_name' => 'Sepatu',
            'category_img' => NULL,
        ],
        [
            'category_name' => 'Sandal',
            'category_img' => NULL,
        ],
        [
            'category_name' => 'Topi',
            'category_img' => NULL,
        ],
        [
            'category_name' => 'Kaos Kaki',
            'category_img' => NULL,
        ]
    );
    }
}
