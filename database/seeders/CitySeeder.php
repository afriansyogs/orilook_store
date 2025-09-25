<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // 'province_id',
    //     'city_name',
    //     'shipping_price'
    public function run(): void
    {
        City::create([
            'province_id' => 1,
            'city_name' => 'Semarang',
            'shipping_price' => 15000
        ],
        [
            'province_id' => 1,
            'city_name' => 'Solo',
            'shipping_price' => 12000
        ],
        [
            'province_id' => 1,
            'city_name' => 'Magelang',
            'shipping_price' => 10000
        ],
        [
            'province_id' => 2,
            'city_name' => 'Surabaya',
            'shipping_price' => 18000
        ],
        [
            'province_id' => 2,
            'city_name' => 'Malang',
            'shipping_price' => 14000
        ],
        [
            'province_id' => 2,
            'city_name' => 'Kediri',
            'shipping_price' => 11000
        ],
        [
            'province_id' => 3,
            'city_name' => 'Bandung',
            'shipping_price' => 16000
        ],
        [
            'province_id' => 3,
            'city_name' => 'Bogor',
            'shipping_price' => 13000
        ],
        [
            'province_id' => 3,
            'city_name' => 'Bekasi',
            'shipping_price' => 17000
        ],
    );
    }
}
