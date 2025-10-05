<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'payment_name' => 'Pengiriman Cepat',
            'payment_img' => NULL,
            'no_rekening' => NULL,
        ],
        [
            'payment_name' => 'Ambil Ditempat',
            'payment_img' => NULL,
            'no_rekening' => NULL,
        ]
    );
    }
}