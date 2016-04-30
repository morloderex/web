<?php

namespace App\Models\Forum;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Comment extends Model
{
	use Rememberable, RecordsActivity;

    protected $table = 'forum_post_comments';

    protected $primaryKey = 'post_id';
}
