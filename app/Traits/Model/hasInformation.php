<?php

namespace App\Traits\Model;


use App\Models\Information;

trait hasInformation
{
    /**
     * Returns Polymorphic relationship with Information
     * 
     * @return mixed
     */
    public function Information()
    {
        return $this->morphMany(Information::class, 'informable');
    }

    /**
     * Returns a pagination enabled collection of Information
     * @return mixed
     */
    public function paginatableInformation()
    {
        $information = $this->information()->get();
        $paginatedInformation = $information->map(function($information){
            return $information->simplePaginate(2);
        });
        return $paginatedInformation;
    }
}