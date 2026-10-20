<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator Toko',
            'email'    => 'admin@tsim.com',
            'password' => Hash::make('admin123'), 
        ]);
    }
}

