@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>{{ $account->username }}</h3>
        <h4>A member since: {{ $account->joindate->diffForHumans() }}</h4>
        <div class="well">
            <div class="deckgrid clearfix">
                @include('partials.home.characters.list', ['characters' => $account->Characters])
            </div>
        </div>
    </div>
@endsection
