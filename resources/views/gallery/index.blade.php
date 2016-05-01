@extends('layouts.app')
    @section('content')
        <div id="galleries" class="container">
            <div class="jumbotron">
                <h1>Galleries</h1>

                <div class="well">
                    <div class="row">
                        <ul class="nav nav-pills nav-stacked">
                            @foreach($galleries as $gallery)
                                <li>
                                    <a href="{{ route('gallery.show', $gallery->id) }}" title="{{ $gallery->description }}" data-toggle="tooltip">
                                        {{ $gallery->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    @endsection