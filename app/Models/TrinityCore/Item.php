<?php

namespace App\Models\TrinityCore;

use App\Libraries\Game\TrinityCore\Item\ItemClass;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

use App\Traits\Model\hasPhotos,
	App\Traits\Model\Forgetable;

class Item extends Model
{
    use Rememberable, 
    	Forgetable,
    	hasPhotos;

    protected $connection = 'TrinityCore_world';

    protected $primaryKey = 'entry';

    protected $table = 'item_template';

    protected $guarded = [
    	'VerifiedBuild'
    ];

    protected $casts = [
    	'class'		=>	'string',
    	//'subClass'	=>	'string'
    	'displayId'	=>	'object'
    ];

    /**
     * Get the Item class represented as a string
     * @return string
     */
    public function getClassAttribute() : string
    {
    	return $this->getClass();	
    }

    /**
     * @return string
     */
    public function getClass() : string 
    {
    	return new ItemClass($this);
    }
}
