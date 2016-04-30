<?php

namespace App\Models\Forum;

use Riari\Forum\Models\Post as Model;

use App\RecordsActivity;
use Watson\Rememberable\Rememberable;

class Post extends Model
{   
    use RecordsActivity, Rememberable;

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function image()
    {
        return $this->belongsToMany(Image::class);
    }
}
