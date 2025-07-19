<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'balance',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
