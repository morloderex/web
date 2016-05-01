<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    #use HasModerateTrait;
    /**
     * @inheritdoc
     */
    protected $with = ['author'];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'title',
        'body'
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'title'     =>  'string',
        'body'      =>  'body'
    ];

    /**
     * The attributes on the model which are moderated.
     *
     * @var array
     */
    private $moderate = [
        'title' => 'blacklist|links:2'
    ];

     /**
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        // Only return moderated Comment(s) by default.
        #static::addGlobalScope(new ModeratedScope);
    }

    public function author() : User
    {
        return $this->user();
    }

    public function user() : User
    {
        return $this->belongsTo(User::class);
    }

    public function commentable() : Model
    {
        return $this->morphTo();
    }
}
