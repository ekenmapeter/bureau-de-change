<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bureau.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Create Manager User
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@bureau.com',
            'role' => 'manager',
            'password' => Hash::make('password'),
        ]);

        // Create Cashier User
        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@bureau.com',
            'role' => 'cashier',
            'password' => Hash::make('password'),
        ]);
    }
}
