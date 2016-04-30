<?php

namespace App\Traits\Model;

trait hasStyles {
	public function styles() {
		return $this->morphMany(Style::class, 'styleable');
	}
}