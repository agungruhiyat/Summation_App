<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasBoot
{

    public static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->keyType                = 'string';
            $model->incrementing           = false;
            $model->dateFormat             = 'U';
            $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) Str::uuid();
            $model->created_at = now()->timestamp;
            $model->updated_at = now()->timestamp;
        });

        static::updating(function($model){
           $model->updated_at = now()->timestamp;
        });
    }
}
