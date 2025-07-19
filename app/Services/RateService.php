<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RateService
{
    public static function latest(string $base = 'USD'): array
    {
        $resp = Http::get("https://api.exchangerate.host/latest", [
            'base' => $base,
            'symbols' => 'NGN,GBP,EUR,CFA,ZAR,CNY',
        ]);

        return $resp->json('rates') ?? [];
    }
}
