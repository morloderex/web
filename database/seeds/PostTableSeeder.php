<?php

use Illuminate\Database\Seeder;

use App\Traits\Seeder\Relatable,
    App\Models\Post,
    App\Models\Category,
    App\Models\Information,
    App\Models\Photo,
    App\Models\User,
    App\Models\Tag,
    App\Models\Comment,
    App\Models\Testimonial;

class PostTableSeeder extends Seeder
{
  use Relatable;

  protected $relationships = [
    Category::class     =>  ['method' => 'category', 'times' => 1],
    Information::class  =>  4,
    Photo::class        =>  5,
    User::class         =>  1,
    Tag::class,
    Comment::class,
    Testimonial::class
  ];

  protected $model = Post::class;
  protected $times = 10;
}
