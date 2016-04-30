<?php

use Illuminate\Database\Seeder;

use App\Traits\Seeder\Relatable,
    App\Post,
    App\Category,
    App\Information,
    App\Photo,
    App\User,
    App\Tag,
    App\Comment,
    App\Testimonial;

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
