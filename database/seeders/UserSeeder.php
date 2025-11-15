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
            'name' => 'Admin User',
            'email' => 'Josephus@graphicstar.com.ph',
            'password' => Hash::make('Phussy21'), 
            'role' => 'admin', 
            'branch' => 'Cebu Graphicstar Imaging Corp - Head Office', 
        ]);

        // Create a staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password1'),
            'role' => 'staff',
            'branch' => 'Cebu Graphicstar Imaging Corp - Head Office', 
        ]);
    }

    
}
