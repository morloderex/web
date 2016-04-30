<?php

namespace App\Scopes\Traits;
use DB;

trait {
	public function scopeDescribe($query) {
		return DB::query('DESCRIBE '.(new static)->getTable());
	}
}