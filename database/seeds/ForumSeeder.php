<?php

use Illuminate\Database\Seeder;

use App\Traits\Seeder\Relatable,
    App\Models\Forum\Category,
    App\Models\Forum\Thread,
    App\Models\Information,
    App\Models\Photo,
    App\Models\User,
    App\Models\Comment;


class ForumSeeder extends Seeder
{
    use Relatable;

    protected $relationships = [
        Thread::class       =>  ['method' => 'threads', 'times' => 10],
        Category::class     =>  2,
        Information::class  =>  4,
        Photo::class        =>  5,
        Category::class     =>  2
    ];

    protected $model = Category::class;
    protected $times = 10;
}
