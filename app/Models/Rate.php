<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'buy_rate',
        'sell_rate',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
