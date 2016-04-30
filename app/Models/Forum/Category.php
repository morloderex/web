<?php

namespace App\Models\Forum;

use Riari\Forum\Models\Thread as Model;

use App\RecordsActivity;
use Sofa\Eloquence\Eloquence;
use Watson\Rememberable\Rememberable;

class Category extends Model
{
    use RecordsActivity, Eloquence, Rememberable;

    protected $searchableColumns = ['title'];
}
