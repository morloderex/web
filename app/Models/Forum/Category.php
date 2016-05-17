<?php

namespace App\Models\Forum;

use App\Models\User;
use App\Traits\Model\hasInformation;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\isTaggable;
use App\Scopes\Traits\scopePopular;

use Gbrock\Table\Traits\Sortable;
use Riari\Forum\Models\Category as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        isTaggable,
        Sortable,
        hasPhotos;

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
     * The attributes which may be used for sorting dynamically.
     *
     * @var array
     */
    protected $sortable = [
        'title',
        'description',
        'visits'
    ];

    public function __construct(array $attributes = [])
    {
        $this->setPopularAttribute('visits');
        parent::__construct($attributes);
    }
}
