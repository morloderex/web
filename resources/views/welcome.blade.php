@extends('layouts.app')

@section('content')

<div id="welcome" class="container">
  <!-- Jumbotron Header -->
  <header class="jumbotron hero-spacer">
    <h1>A Warm Welcome!</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, ipsam, eligendi, in quo sunt possimus non
      incidunt odit vero aliquid similique quaerat nam nobis illo aspernatur vitae fugiat numquam repellat.</p>
    <p><a class="btn btn-primary btn-large">Call to action!</a>
    </p>
  </header>

  <hr>

  <!-- Title -->
  <div class="row">
    <div class="col-lg-12">
      <h3>Latest Features</h3>
    </div>
  </div>
  <!-- /.row -->

  <!-- Page Features -->
  <div class="row text-center">

    <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
        <img src="http://placehold.it/800x500" alt="">
        <div class="caption">
          <h3>Feature Label</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>
            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
        <img src="http://placehold.it/800x500" alt="">
        <div class="caption">
          <h3>Feature Label</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>
            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
        <img src="http://placehold.it/800x500" alt="">
        <div class="caption">
          <h3>Feature Label</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>
            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
        <img src="http://placehold.it/800x500" alt="">
        <div class="caption">
          <h3>Feature Label</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>
            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
          </p>
        </div>
      </div>
    </div>

  </div>
  <!-- /.row -->

  <hr>

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
