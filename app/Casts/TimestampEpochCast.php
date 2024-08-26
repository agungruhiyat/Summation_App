<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TimestampEpochCast implements CastsAttributes
{

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return float|int|string|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if(!$value){
            return $value;
        }

        return Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $value)->timestamp;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return (int)$value;
    }
}
