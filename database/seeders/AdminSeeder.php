<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('123456'),
            'status' => 1,
            'is_super' => 1,
            'email_verified_at' => now(),
            'remember_token' => null,
        ]);

        Admin::create([
            'name' => 'Editor',
            'email' => 'admin2@demo.com',
            'password' => bcrypt('123456'),
            'status' => 1,
            'is_super' => 0,
            'email_verified_at' => now(),
            'remember_token' => null,
        ]);
    }
}
