<?php

use Illuminate\Database\Seeder;

use App\Traits\Seeder\Relatable,
    App\Models\Forum\Post,
    App\Models\Forum\Thread,
    App\Models\Information,
    App\Models\Photo,
    App\Models\User,
    App\Models\Comment;

class PostTableSeeder extends Seeder
{
  use Relatable;

  protected $relationships = [
    Thread::class       =>  ['method' => 'thread', 'times' => 1],
    Photo::class        =>  5,
    User::class         =>  1,
    Comment::class,
  ];

  protected $model = Post::class;
  protected $times = 10;
}
