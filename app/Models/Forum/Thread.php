<?php

namespace App\Models\Forum;

use App\Traits\Model\hasInformation;
use App\Traits\Model\RecordsActivity;

use Riari\Forum\Models\Thread as Model;

use Watson\Rememberable\Rememberable;

class Thread extends Model
{
    use RecordsActivity, Rememberable, hasInformation;

    protected $fillable = [
        'title',
        'pinned',
        'locked'
    ];
    
    protected $casts = [
        'title'     => 'string',
        'pinned'    => 'boolean',
        'locked'    => 'boolean'
    ];

    public function scopeLatestChangelog($query, int $take = 10)
    {
        return $query->with(['posts', 'information'])
            ->where('title', '=', 'changelog')
            ->orderBy('updated_at', 'DESC')
            ->take($take)
            ->get();
    }
}
