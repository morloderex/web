@extends('layouts.app')
    @section('content')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default well panel--styled">
                        <div class="panel-body card">
                            <div class="col-md-12 panelTop">
                                <div class="card-image">
                                    @if(!$photos->isEmpty())
                                        @if($photos->count() > 1)
                                            @include('partials.photo.render.multiple.thumbnails', ['photos' => $photos])
                                        @else
                                            @include('partials.photo.render.thumbnail', ['photo' => $photos->first()])
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <h2 class="card-title">
                                        {{$gallery->name}}
                                    </h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="card-function">
                                                {{$gallery->description}}
                                            </p>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12 panelBottom">
                                @foreach($photos as $photo)
                                    @if( ! $photo->Information->isEmpty() )

                                            <div class="col-md-4 text-right">
                                                @include('partials.information', ['information' => $photo->information])
                                            </div>
                                     @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                  {!! $photos->render() !!}
                </div>
            </div>
        </div>
        <hr role="separator" class="divider"/>
    @endsection