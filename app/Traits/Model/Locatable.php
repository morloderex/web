<?php

namespace App\Traits\Model;
use App\Models\Location;

trait Locatable {
  public function location() {
    return $this->morphTo(Location::class);
  }
}
