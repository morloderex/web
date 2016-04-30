<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ModeratedScope
 * @package App\Scopes
 */
class ModeratedScope implements Scope
{
    /**
     * @var string
     */
    protected static $scopeModerated_field = 'moderated';

    /**
     * ModeratedScope constructor.
     * @param string $scopeModerated_field
     */
    public function __construct(string $scopeModerated_field = 'moderated')
    {
        static::$scopeModerated_field = $scopeModerated_field;
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
        return $builder->where(static::$scopeModerated_field, '=', 1);
    }

    /**
     * @return string
     */
    public static function getScopeField()
    {
        return static::$scopeModerated_field;
    }
}