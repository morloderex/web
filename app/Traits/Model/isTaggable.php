<?php

namespace App\Traits\Model;


use App\Tag;

trait isTaggable
{
    public function tags()
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
}