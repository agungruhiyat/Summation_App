<?php

namespace App\Models;

use App\Casts\TimestampEpochCast;
use App\Traits\HasBoot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,HasBoot;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dateFormat = 'U';
    protected $fillable = [
            "customer",
            "name_product",
            "price",
            "total_product",
            "total_price",
            "total_pay",
            "change_money"
    ];

    protected $casts = [
        'created_at' => TimestampEpochCast::class,
        'updated_at' => TimestampEpochCast::class,
    ];
}


