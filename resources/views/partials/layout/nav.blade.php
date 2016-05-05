<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                Welcome
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ url('/home') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('post.index') }}">Posts</a>
                </li>
                <li>
                    <a href="{{ url('/faq') }}">Faq</a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}">Contact</a>
                </li>
                <li>
                    <a href="{{ route('gallery.index') }}">Gallery</a>
                </li>
                <li>
                    <a href="{{ route('forum.index') }}">Forum</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
               @include("partials.layout.nav.auth")
            </ul>
        </div>
    </div>
</nav>