<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'testimoniable_id',
        'testimoniable_type',

        'user_id',

        'title',
        'data'
    ];

    protected $casts = [
        'testimoniable_id'     =>  'integer',
        'testimoniable_type'   =>  'string',

        'user_id'              =>  'integer',

        'title'                =>  'string',
        'content'              =>  'text'
    ];
    
    public function testimoniable()
    {
        return $this->morphTo();
    }

    public function getAuthorAttribute() : User
    {
        return $this->user();
    }

    public function user() : User
    {
        return $this->belongsTo(User::class)->first();
    }
}
