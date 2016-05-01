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
      static::creating(function(Tag $tag){
            $taggable = Taggable::whereTag($tag->tag)->get() ?: new Taggable($tag->tag);
            unset($tag->attributes['tag']);
            $tag->Tags()->saveMany($taggable);
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
