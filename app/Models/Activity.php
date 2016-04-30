<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
			'name', 
            'user_id',
			'subject_id',
			'subject_type',
	];
    
    protected $casts = [
        'name'          =>  'string',
        'user_id'       =>  'integer',
        'subject_id'    =>  'integer',
        'subject_type'  =>  'string'
    ];

    public function getNameAttribute($name) : string
    {
        return strtolower($name);
    }

    public function subject()
    {
    	return $this->morphTo();
    }
}
