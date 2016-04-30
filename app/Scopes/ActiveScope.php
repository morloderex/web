<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ActiveScope
 * @package App\Scopes
 */
class ActiveScope implements Scope
{
    /**
     * @var string
     */
    protected static $scopeActive_field = 'active';

    /**
     * ActiveScope constructor.
     * @param string $scopeActive_field
     */
    public function __construct(string $scopeActive_field = 'active')
    {
        static::$scopeActive_field = $scopeActive_field;
    }
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where(static::$scopeActive_field, '=', 1);
    }

    /**
     * @return string
     */
    public static function getScopeField()
    {
        return static::$scopeActive_field;
    }
}