<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Spatie\Glide\GlideImageFacade;
use Watson\Rememberable\Rememberable;

class Image extends Model
{
	use Eloquence, Rememberable;

    protected $fillable = ['name'];

    protected $searchableColumns = ['name'];

    public function save(array $options = [])
    {
	    GlideImageFacade::load($this->name)->save(Config('laravel-glide.source.path') . '/' . $this->name);
	    parent::save($options);
    }
}
