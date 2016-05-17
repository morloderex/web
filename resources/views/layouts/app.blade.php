<!DOCTYPE html>
<html lang="en">
    @extends('partials.layout.head')
    <body id="app-layout">
        @include('partials.layout.nav')
        <div class="container">

            @yield('content')

        </div>
        <!-- JavaScripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <script src="{{ elixir('js/app.js') }}"></script>
        <script src="{{ elixir('js/main.js') }}"></script>
    </body>
</html>
