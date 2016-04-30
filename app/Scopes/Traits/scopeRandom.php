<?php

namespace App\Scopes\Traits;


/**
 * Class scopeRandom
 * @package App\Traits\Model
 */
trait scopeRandom
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeRandom($query)
    {
        return $query->orderByRaw("RAND()")->first();
    }
}
