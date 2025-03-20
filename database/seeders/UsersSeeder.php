<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'no_hp' => '081234567842',
            'addres' => 'Jl. Dekat rumah 12',
            'role' => 'admin',
            'password' => bcrypt('12345678'), 
        ]);
    }
}
