<?php

namespace App\Models;

use App\Casts\TimestampEpochCast;
use App\Traits\HasBoot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Summations extends Model
{
    use HasFactory,HasBoot;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dateFormat = 'U';

    protected $fillable = [
        "destination_user_id",
        "sender_user_id",
        "total",
        "rate_per_unit",
        "bank",
        "account_number",
        "transfer_fees",
        "processed_by"
    ];

    protected $casts = [
        'created_at' => TimestampEpochCast::class,
        'updated_at' => TimestampEpochCast::class,
    ];
}