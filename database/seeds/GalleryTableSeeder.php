<?php

use Illuminate\Database\Seeder;

use App\Models\Gallery,
    App\Models\Photo;

use App\Traits\Seeder\Relatable;

class GalleryTableSeeder extends Seeder
{
    use Relatable;

    protected $relationships = [
        Photo::class    =>  20
    ];

    protected $model = Gallery::class;
    protected $times = 3;

}
