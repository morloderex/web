<?php

namespace App\Models;

use Mpociot\Teamwork\TeamworkTeam;

use App\Traits\Model\RecordsActivity,
	App\Traits\Model\hasTestimonials,
	App\Traits\Model\isTaggable;

class Team extends TeamworkTeam
{
	use RecordsActivity,
		isTaggable,
		hasTestimonials;

    protected $fillable = [
        'name',
        'owner_id'
    ];

    protected $casts = [
        'name'      => 'string',
        'owner_id'  => 'object'
    ];
}
