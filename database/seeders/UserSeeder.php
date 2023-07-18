<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'mutasim',
            'email' => 'mutasim@gmail.com',
            'password' => Hash::make('12345678'),
            'status' => User::ADMIN,
            'phone' => '01092782741',
            'dob' => '2001-08-20',
            'gender' => '0'
        ]);
    }
}
