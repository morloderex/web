<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $with = ['Tags'];

    protected $fillable = [
      'taggable_id',
      'taggable_type',
      'tag'
    ];

    protected $casts = [
      'tags'  =>  'object'
    ];

    public static function boot() {
      static::created(function(Tag $tag){
        $taggable = Taggable::whereTag($tag->tag) ?: new Taggable($tag->tag);
        $tag->tags()->save($taggable);
      });
    }

    public function Taggable()
    {
        return $this->morphTo();
    }

    public function Tags() {
      return $this->hasMany(Taggable::class);
    }
}
