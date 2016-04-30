<?php
/**
 *  This trait is to be used with Watson/Rememberable
 */

namespace App\Traits\Model;


use Illuminate\Support\Facades\Cache;

trait Forgetable
{
    public static function bootForgetable()
    {
        static::deleting(function($model){
           Cache::forget($model->getCacheKey()); 
        });
    }
}