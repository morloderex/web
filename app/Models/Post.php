<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Scopes\Traits\scopeInactive;
use App\Scopes\Traits\scopePopular;
use App\Scopes\Traits\scopeRandom;
use App\Traits\Model\Forgetable;
use App\Traits\Model\hasInformation;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\hasTestimonials;
use App\Traits\Model\isCommentable;
use App\Traits\Model\isTaggable;
use App\Traits\Model\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Watson\Rememberable\Rememberable;

/**
 * Class Post
 * @package App
 */
class Post extends Model
{
    use isTaggable,
        Rememberable,
        Forgetable,
        hasPhotos,
        hasInformation,
        hasTestimonials,
        isCommentable,
        RecordsActivity,
        scopeRandom,
        scopePopular,
        scopeInactive;

    #HasModerateTrait;

    /**
     * Remember all queries forever by default.
     *
     * @var string
     */
    #protected $rememberFor = '-1';

    /**
     * @inheritdoc
     */
    protected $with = [
        'user',
        'photos'
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'active',
        'title',
        'description',
        'body'
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'id'            =>  'integer',
        'user_id'       =>  'integer',
        'active'        =>  'boolean',
        'title'         =>  'string',
        'description'   =>  'string',
        'body'          =>  'text',
        'user'          =>  'object',
        'comments'      =>  'collection'
    ];

    /**
     * The attributes on the model which are moderated.
     *
     * @var array
     */
    private $moderate = [
        'title'         =>  'blacklist|links:2',
        'description'   =>  'blacklist'
    ];

    /**
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        // Only return active Post(s) by default.
        static::addGlobalScope(new ActiveScope);

        // Only return moderated Post(s) by default.
        #static::addGlobalScope(new ModeratedScope);
    }

    /**
     * @return Category
     */
    public function category() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @return User
     */
    public function author() : BelongsTo
    {
        return $this->user();
    }

    /**
     * @return User
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $queryBuilder
     * @param int $take
     * @return Collection
     */
    public function scopeLatestSortedByCategory(
                    $queryBuilder,
                    int $take = 20,
                    array $columns = ['categories.id','categories.name', 'posts.id', 'posts.title']
    ) : Collection
    {
        $query = $queryBuilder
                    ->join('category_post as pivot', 'pivot.post_id', '=', 'posts.id')
                    ->join('categories', 'pivot.category_id', '=', 'categories.id')
                    ->orderBy('pivot.category_id', 'desc')
                    ->take($take);

        return $query->get($columns);
    }
}
