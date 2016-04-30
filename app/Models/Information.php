<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = False;
    
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'informable_id',
        'informable_type',
        'type',
        
        'title',
        'synopsis',
        'data'
    ];
    
    /**
     * @inheritdoc
     */
    protected $casts = [
        'informable_id'     =>  'integer',
        'informable_type'   =>  'string',
        'type'              =>  'string',
        
        
        'title'     =>  'string',
        'synopsis'  =>  'string',
        'data'      =>  'text'
    ];
    
    public function informable()
    {
        return $this->morphTo();
    }
}
