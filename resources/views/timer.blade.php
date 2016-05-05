<!DOCTYPE html>
<html lang="en">
@include('partials.layout.head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.css">
<body>
<div class="wrapper">
    <header id="header" class="parallax1">
        <div class="container">
            <div class="row ">
                <div class="wrapper-inner text-center">
                    <div class="heading-content">
                        <h2 class="title wow bounceInLeft" >website is almost ready</h2>
                        <p class="wow bounceInRight" data-wow-delay=".3s">Our website  is under construction. We'll be here soon with our new awesome site. </p>
                    </div>
                    <!-- start timer, reference to js/countdown.js -->
                    <div id="timer" class=" wow flipInY"></div>
                    <!-- end timer -->
                    <div class="btn-container m60">
                        <a href="{{ url('/welcome') }}" class="active wow fadeInLeft">early preview</a>
                        @include("partials.layout.nav.auth")
                    </div>
                    <ul class="list-inline socail-link">
                        <li><a href="#"><i class="fa fa-facebook wow fadeInRight" data-wow-delay=".2s"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter wow fadeInRight" data-wow-delay=".4s"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus wow fadeInRight" data-wow-delay=".8s"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin wow fadeInRight" data-wow-delay=".1s"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram wow fadeInRight" data-wow-delay="1.1s"></i></a></li>
                    </ul>
                    <div class="copyright text-center white">
                        <p>&copy; Original authors
                            Designed by
                            <a href="https://www.behance.net/towkirbappy" target="_blank">Bappy</a>
                            and developed by
                            <a href="https://www.behance.net/esrat91" target="_blank">Themeturn </a>
                        </p>
                        <p>Copyright reserved to both.2015 </p>
                    </div>
                </div> <!-- wrapper-inner end -->
            </div> <!-- row end -->
        </div> <!-- container-fluid end -->
    </header>
</div>
</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<script src="{{ elixir('js/app.js') }}"></script>
<script src="{{ elixir('js/main.js') }}"></script>