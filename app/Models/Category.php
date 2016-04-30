<?php

namespace App\Models;

use App\Traits\Model\hasInformation;
use App\Traits\Model\isTaggable;
use App\Scopes\Traits\scopePopular;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Traits\Model\Forgetable,
    Watson\Rememberable\Rememberable;

/**
 * Class Category
 * @package App
 */
class Category extends Model
{
    use hasInformation,
        Rememberable,
        Forgetable,
        scopePopular,
        isTaggable;

    /**
     * Remember all queries forever by default.
     *
     * @var string
     */
    #protected $rememberFor = '-1';

    /**
     * @inheritdoc
     */
    #protected $with = ['user', 'tags'];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'user_id'       =>  'object',
        'name'          =>  'string',
        'description'   =>  'string'
    ];

    public function __construct(array $attributes = [])
    {
        $this->setPopularAttribute('visits');
        parent::__construct($attributes);
    }

    /**
     * @return User
     */
    public function user() : User
    {
        return $this->belongsTo(User::class)->firstOrFail();
    }

    /**
     * @return Collection
     */
    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
