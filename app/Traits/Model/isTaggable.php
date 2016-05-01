<?php

namespace App\Traits\Model;


use App\Models\Tag;

trait isTaggable
{
    public function tags()
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
}