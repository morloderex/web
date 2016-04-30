<?php

namespace App\Models\TrinityCore;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Model\RecordsActivity;

class Ticket extends Model
{
    use RecordsActivity;
  
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
