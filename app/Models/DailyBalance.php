<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'balance',
        'date',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
