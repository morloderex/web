<?php

namespace App\Models\Emulators\TrinityCore;

use App\Libraries\Currency\IngameCurrencyConverter as CurrencyConverter;
use App\Traits\Model\hasPhotos;
use App\TrinityCore\Map;
use App\TrinityCore\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Characters Model
 * Reflects TrinityCore in-game Characters.
 */
class Character extends Model
{
    use hasPhotos;
    /**
     * @inheritdoc
     */
    public $timestamps = False;
    /**
     * @inheritdoc
     */
    protected $connection = 'TrinityCore_characters';
    /**
     * @inheritdoc
     */
    protected $table = 'characters';
    /**
     * @inheritdoc
     */
    protected $primaryKey = 'guid';
    /**
    * @inheritdoc
    */
    protected $fillable = [
        'guid',
        'account',
        'name',
        'race',
        'class',
        'gender',
        'level',
        'money',
        'position_x',
        'position_y',
        'position_z',
        'orientation',
        'map',
        'zone',
        'cinematic'
    ];
    /**
     * @inheritdoc
     */
    protected $casts = [
        'guid'          =>  'integer',
        'account'       =>  'object',
        'name'          =>  'sting',
        'race'          =>  'string',
        'class'         =>  'string',
        'gender'        =>  'string',
        'level'         =>  'integer',   
        'money'         =>  'string',
        'position_x'    =>  'integer', 
        'position_y'    =>  'integer', 
        'position_z'    =>  'integer',
        'orientation'   =>  'float',
        'map'           =>  'string',
        'zone'          =>  'string',
        'cinematic'     =>  'boolean'
    ];
    /**
     * Array of in-game races enumerated with appropriate numbers.
     *
     * @var array
     */
    protected $races = [
        '1' => 'Human',
        '2' => 'Orc',
        '3' => 'Dwarf',
        '4' => 'Night Elf',
        '5' => 'Undead',
        '6' => 'Tauren',
        '7' => 'Gnome',
        '8' => 'Troll',
        '9' => 'Goblin',
        '10' => 'Blood Elf',
        '11' => 'Draenei',
        '12' => 'Fel Orc',
        '13' => 'Naga',
        '14' => 'Broken',
        '15' => 'Skeleton',
        '16' => 'Vrykul',
        '17' => 'Tuskarr',
        '18' => 'Forest Troll',
        '19' => 'Taunka',
        '20' => 'Northrend Skeleton',
        '21' => 'Ice Troll',
        '22' => 'Worgen'
    ];

    /**
     * Array of in-game classes enumerated with appropriate numbers.
     *
     * @var array
     */
    protected $classes = [
        '1' => 'Warrior',
        '2' => 'Paladin',
        '3' => 'Hunter',
        '4' => 'Rogue',
        '5' => 'Priest',
        '6' => 'Death Knight',
        '7' => 'Shaman',
        '8' => 'Mage',
        '9' => 'Warlock',
        '11' => 'Druid'
    ];

    /**
     * Array of in-game genders.
     *
     * @var array
     */
    protected $genders = [
        '0'   =>  'male',
        '1'   =>  'female',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function($character){
           if( ! $character->guid )
           {
               $count = $character->all()->count();
               if($count === 0)
               {
                   $newMax = 1;
               } else {
                   $newMax = $count+1;
               }

               $character->guid = $newMax;
           }
        });
    }

    // -- Eloquent Relations -- //

    /**
     * One-To-One Relationship with TrinityCore/Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account', 'id');
    }

    /**
     * One-To-Many Relationship with TrinityCore/Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Tickets() : HasMany
    {
        return $this->hasMany(Ticket::class, 'guid', 'playerGuid');
    }

    /**
     * One-To-Many Relationship with TrinityCore/Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function Items() : HasMany
    {
        return $this->hasManyThrough(Item::class, Inventory::class, 'entry', 'item');
    }

    /**
     * One-To-One Relationship with TrinityCore/Map
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Map() {
        return $this->belongsTo(Map::class);
    }

    /**
     * One-To-One Relationship with TrinityCore/Zone
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Zone() {
        return $this->belongsTo(Zone::class);
    }

    // -- Eloquent Attribute Accessors -- //
    
    /**
     * Returns name of a Race based upon a given integer.
     * @param  int    $race
     * @return string
     */
    public function getRaceAttribute(int $race) : string
    {
        return $this->getRace($race);
    }

    /**
     * Returns Class name based upon a given integer.
     * @param  int    $race
     * @return string
     */
    public function getRace(int $race) : string
    {
        return !array_key_exists($race, $this->races) ? 'unknown' : $this->races[$race];
    }

    /**
     * Sets the Race attribute to a integer corresponding to the given input.
     *
     * @param $race
     * @return Character
     */
    public function setRaceAttribute($race) : self
    {
        $this->attributes['race'] = $this->findArrayKey($race, $this->races);

return $this;
}

    /**
     * Returns the key corresponding to the given value
     *
     * @param $value
     * @param array $array
     * @return mixed
     */
    protected function findArrayKey($value, array $array)
    {
        switch (gettype($value))
        {
            case 'string':
                if( ! is_numeric($value) )
                {
                    while( ($race = current($array)) !== False)
                    {
                        $value = key($array);
                        break;
                    }
                }
                break;
        }

        return $value;
    }

    /**
     * Returns Class name based upon a given integer.
     * @param  int    $class
     * @return string
     */
    public function getClassAttribute(int $class) : string
    {
        return $this->getClass($class);
    }

    /**
     * Returns Class name based upon a given integer.
     * @param  int    $class
     * @return string
     */
    public function getClass(int $class) : string
    {
        return !array_key_exists($class, $this->classes) ? 'unknown' : $this->classes[$class];
    }

    /**
     * Sets the Class attribute to a integer corresponding to the given input.
     *
     * @param $class
     * @return Character
     */
    public function setClassAttribute($class) : self
    {
        $this->attributes['class'] = $this->findArrayKey($class, $this->classes);

        return $this;
    }

    // -- Attribute "Coverters" -- //

    /**
     * Returns Gender based upon a given integer.
     * @param  int    $gender
     * @return string
     */
    public function getGenderAttribute(int $gender) : string
    {
        return $this->getGender($gender);
    }

    /**
     * Returns Gender based upon a given integer.
     * @param  int    $gender
     * @return string
     */
    public function getGender(int $number) : string
    {
        return !array_key_exists($number, $this->genders) ? 'unknown' : $this->genders[$number];
    }

    /**
     * Sets the Class attribute to a integer corresponding to the given input.
     *
     * @param $class
     * @return Character
     */
    public function setGenderAttribute($gender) : self
    {
        $this->attributes['gender'] = $this->findArrayKey($gender, $this->genders);

        return $this;
    }

    public function getMoneyAttribute(int $money) : string
    {
        return $this->getMoney($money);
    }

    /**
     * Returns a nicely formatted string of 
     * %g Gold, %s Silver, %c Copper
     * @param  int    $money Copper
     * @return string
     */
    public function getMoney(int $money) : string
    {
        return (new CurrencyConverter($money))->getMoney();
    }
}
