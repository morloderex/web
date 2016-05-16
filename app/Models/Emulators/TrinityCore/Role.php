<?php

namespace App\Models\Emulators\TrinityCore;


use App\Traits\Model\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    use RecordsActivity;

    /**
     * @inheritdoc
    */
    protected $connection = 'TrinityCore_auth';

    /**
     * @inheritdoc
     */
    protected $table = 'account_access';

    /**
     * array of roles (GM Levels)
     * @var array
     */
    protected $roles = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = [])
    {
        $this->roles = config('roles');
        parent::__construct($attributes);
    }

    /**
     * One-To-One Relationship with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Resolves the numeric (integral) value corresponding to given GM Level,
     * if any.
     *
     * @param  string $role
     * @return integer
     */
    public function resolveGMLevel(string $role)
    {
        if(array_key_exists($role, $this->roles))
            return $this->roles[$role]['gmlevel'];

        return array_map(function($key, $array) use($role){
            $abbreviation = $array['abbreviation'];
            if($role == $abbreviation)
                return $array['gmlevel'];
        }, $this->roles);
    }

    /**
     * Increments GM Level of given User associated with this Role.
     * @return self
     */
    public function promote() : Role
    {
        $gmlevel = $this->getAttribute('gmlevel');
        $gmlevel++;
        $this->setAttribute('gmlevel', $gmlevel);
        $this->save();
        return $this;
    }

    /**
     * Increments GM Level of given User associated with this Role.
     * @return self
     */
    public function demote() : Role
    {
        $gmlevel = $this->getAttribute('gmlevel');
        $gmlevel--;
        $this->setAttribute('gmlevel', $gmlevel);
        $this->save();
        return $this;
    }

    /**
     * Assign or Reassigns the User associated with this Role's, realm.
     * @return self
     */
    public function assignTo(int $realmID = -1)
    {
        $this->setAttribute('realmID', $realmID);
        return $this;
    }
}
