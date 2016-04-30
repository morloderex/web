<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    public $timestamps = False;

    protected $fillable = [
      'tag_id',
      'tag'
    ];

    public function Tag() {
      return $this->belongsTo(Tag::class);
    }


}
