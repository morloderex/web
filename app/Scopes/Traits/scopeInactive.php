<?php

namespace App\Scopes\Traits;
use App\Scopes\ActiveScope;

/**
 * @Note: This trait is an complimentary addition to App\Scopes\ActiveScope
 * 
 * Class scopeInactive
 * @package App\Scopes\Traits
 */
trait scopeInactive
{
    /**
     *
     */
    public static function bootScopeInactive()
    {
        if( ! static::hasGlobalScope(ActiveScope::class) )
            static::addGlobalScope(new ActiveScope);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInactive()
    {
        $query = $this->newQueryWithoutScope(ActiveScope::class);
        return $query->where(ActiveScope::getScopeField(), '=', 0);
    }
}