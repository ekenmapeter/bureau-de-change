<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rate;
use App\Models\Transaction;
use App\Models\Wallet;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
