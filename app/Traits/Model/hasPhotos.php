<?php

namespace App\Traits\Model;

use App\Photo;

trait hasPhotos {
	public function photos() {
  		return $this->morphMany(Photo::class, 'imageable');
  	}	
}