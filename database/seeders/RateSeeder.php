<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Rate;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = [
            'USD' => ['buy' => 750.00, 'sell' => 762.00],
            'GBP' => ['buy' => 950.00, 'sell' => 970.00],
            'EUR' => ['buy' => 820.00, 'sell' => 835.00],
            'CFA' => ['buy' => 1.20, 'sell' => 1.25],
            'ZAR' => ['buy' => 45.00, 'sell' => 48.00],
            'CNY' => ['buy' => 105.00, 'sell' => 108.00],
        ];

        foreach ($rates as $code => $rate) {
            $currency = Currency::where('code', $code)->first();
            if ($currency) {
                Rate::create([
                    'currency_id' => $currency->id,
                    'buy_rate' => $rate['buy'],
                    'sell_rate' => $rate['sell'],
                ]);
            }
        }
    }
}
