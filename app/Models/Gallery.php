<?php

namespace App\Models;

use App\Traits\Model\Forgetable;
use App\Traits\Model\hasPhotos;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Gallery extends Model
{
    use hasPhotos, Forgetable, Rememberable;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $casts = [
        'name'          =>  'string',
        'description'   =>  'string'
    ];
}
