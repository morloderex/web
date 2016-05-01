@extends('layouts.app')

@section('content')

<div id="welcome" class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            Welcome to MWoW!
          </h1>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><i class="fa fa-fw fa-check"></i> Bootstrap v3.2.0</h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default">Learn More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><i class="fa fa-fw fa-gift"></i> Free &amp; Open Source</h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default">Learn More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><i class="fa fa-fw fa-compass"></i> Easy to Use</h4>
            </div>
            <div class="panel-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque, optio corporis quae nulla aspernatur in alias at numquam rerum ea excepturi expedita tenetur assumenda voluptatibus eveniet incidunt dicta nostrum quod?</p>
              <a href="#" class="btn btn-default">Learn More</a>
            </div>
          </div>
        </div>
      </div>

      <div class="well">

        <div class="row">
          <div class="col-lg-12">
            <h3>Changelog</h3>
            <div class="panel-group" id="accordion">
              @foreach($changelogs as $changelog)
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title centered">
                      <a class="accordion-toggle text-center" data-toggle="collapse" data-parent="#accordion" href="#collapse__changelog-{{$changelog->id}}">
                        written: {{ $changelog->updated_at->diffForHumans() }}
                        <br />
                        {{ $changelog->description }}
                      </a>
                    </h4>
                  </div>
                  <div id="collapse__changelog-{{$changelog->id}}" class="panel-collapse collapse">
                    <div class="panel-body">
                      <ul class="nav nav-pills nav-stacked">
                        @foreach($changelog->posts as $post)
                          <li>
                            <a data-lity data-lity-target="{{ route('post.display', $post) }}" href="{{ route('post.show', $post->id) }}" title="{{ $post->description }}" data-toggle="tooltip">
                              {{ $post->title }}
                            </a>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
