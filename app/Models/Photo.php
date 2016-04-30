<?php

namespace App\Models;

use App\Traits\Model\handlesImages;
use Illuminate\Database\Eloquent\Model;

// Traits
use Watson\Rememberable\Rememberable,
    App\Traits\Model\RecordsActivity,
    App\Traits\Model\hasInformation;

/**
 * Class Photo
 * @package App
 */
class Photo extends Model
{
    use Rememberable,
        RecordsActivity,
        hasInformation,
        handlesImages;

    /**
     * @var bool
     */
    public $timestamps = False;

    /**
     * @var array
     */
    protected $fillable = [
    	'name',
      'extension',
      'description',
    	'imageable_id',
    	'imageable_type'
    ];

    protected $casts = [
        'name'              =>  'string',
        'extension'         =>  'string',
        'description'       =>  'string',
        'imageable_id'      =>  'integer',
        'imageable_type'    =>  'string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable() {
    	return $this->morphTo();
    }
}
