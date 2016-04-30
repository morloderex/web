@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'Hello!',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h2>{{ $data['subject'] }}</h2>
        <h3>
            <em>
                Please note: this event is triggered when an IP has changed thrice within a short time frame.
            </em>
        </h3>
        
        <p>
            We have notices an abnormal change of your IP address, <br />
            the last IP register was:
            <blockquote>
                {{ $data['last_ip'] }}
            </blockquote>
            <br />
            however recently a login from {{ $data['current_ip'] }} has been noticed.
        </p>

    @include('beautymail::templates.sunny.contentEnd')

@stop