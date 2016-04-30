@extends('layouts.app')

@section('content')
<div id="welcome" class="container">
    <div class="jumbotron">
      <h1>Welcome!</h1>

      <div class="well">
        <div class="row">
          <h3>Popular posts</h3>
          <ul class="nav nav-pills nav-stacked">
            @foreach($posts as $post)
              <li>
                <a href="{{ route('post.show', $post->id) }}" title="{{ $post->description }}" data-toggle="tooltip">
                  {{ $post->title }}
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
</div>
@endsection
