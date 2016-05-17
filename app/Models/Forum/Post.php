<?php

namespace App\Models\Forum;

use App\Models\User;
use App\Scopes\Traits\scopeRandom;
use App\Traits\Model\Forgetable;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\hasTestimonials;
use App\Traits\Model\isCommentable;
use App\Traits\Model\isTaggable;
use App\Traits\Model\RecordsActivity;
use Riari\Forum\Models\Post as Model; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        hasTestimonials,
        isCommentable,
        RecordsActivity,
        scopeRandom;

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
        'post_id'       =>  'integer',
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

        // Only return moderated Post(s) by default.
        #static::addGlobalScope(new ModeratedScope);
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
}
