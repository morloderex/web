<?php

namespace App\Models\Emulators\TrinityCore;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class Inventory extends Model
{
    /**
     * @inheritdoc
     */
    protected $connection = 'TrinityCore_characters';

    /**
     * @inheritdoc
     */
    protected $table = 'character_inventory';

    /**
     * @inheritdoc
     */
    protected $primaryKey = 'guid';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'guid',
        'bag',
        'slot',
        'item'
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'bag'   =>  'integer',
        'slot'  =>  'integer',
        'item'  =>  'object'
    ];

    /**
     * @return BelongsTo
     */
    public function Item() {
        return $this->belongsTo(Item::class, 'item');
    }

}
