<?php

namespace App\Traits\Model;


trait hasBootedEvent
{
    /**
     * Register a created model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @param  int  $priority
     * @return void
     */
    public static function booted($callback, $priority = 0)
    {
        static::registerModelEvent('booted', $callback, $priority);
    }
}