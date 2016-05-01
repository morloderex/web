<?php

namespace App\Models\TrinityCore;


use App\Traits\Model\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use RecordsActivity;

    protected $connection = 'TrinityCore_characters';

    protected $primaryKey = 'guid';

    protected $table = 'gm_ticket';

    protected $fillable = [
      
    ];

  /**
   * One-To-One Relationship with Character
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
    public function player()
    {
        return $this->belongsTo(Character::class, 'playerGuid', 'guid');
    }

    /**
     * One-To-One Relationship with Character
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameMaster()
    {
        return $this->belongsTo(Character::class, 'playerGuid', 'assignedTo');
    }
}
