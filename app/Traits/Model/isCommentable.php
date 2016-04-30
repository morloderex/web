<?php

namespace App\Traits\Model;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Comment;

trait isCommentable {
	public function comments() : MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}
}
