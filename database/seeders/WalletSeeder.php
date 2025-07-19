<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            Wallet::create([
                'currency_id' => $currency->id,
                'balance' => $currency->code === 'NGN' ? 1000000 : 0,
            ]);
        }
    }
}
