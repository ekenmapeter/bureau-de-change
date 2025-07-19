<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'NGN', 'name' => 'Nigerian Naira'],
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'GBP', 'name' => 'British Pound'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'CFA', 'name' => 'CFA Franc'],
            ['code' => 'ZAR', 'name' => 'South African Rand'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan'],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
