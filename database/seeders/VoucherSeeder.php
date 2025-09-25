<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::create([
            'voucher_name' => 'KALCER',
            'discount_voucher' => '20000'
        ],
        [
            'voucher_name' => 'SHOES',
            'discount_voucher' => '45000'
        ]
    );
    }
}
