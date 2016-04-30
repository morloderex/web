@extends('beautymail::templates.ark')

@section('content')

    @include('beautymail::templates.ark.heading', [
        'heading' => $text.', '.$user->name.' !',
        'level' => 'h1'
    ])

    @include('beautymail::templates.ark.contentStart')

        <h4 class="secondary"><strong>We</strong></h4>
        <p> are glad you're here! </p>
        <h3 class="secondary"><strong>And</strong></h3>
        <p> hope to see you enjoy, your stay! </p>

        <button type="button">
          <a href="{{ route('auth.login') }}">
            Login
          </a>
        </button>
@stop
