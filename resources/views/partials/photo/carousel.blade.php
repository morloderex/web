<section id="carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="carousel slide" id="myCarousel" data-ride="carousel" data-interval="3000">
                    <!-- Carousel indicators -->
                    <ol class="carousel-indicators">
                        @for($i = 0; $i < $photos->count(); $i++)
                            <li data-target="#fade-quote-carousel" data-slide-to="{{$i}}"></li>
                        @endfor
                    </ol>
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        @foreach($photos as $photo)
                            <div class="item">
                                @include('partials.photo.render.thumbnail')
                                @if($photo->description)
                                    <blockquote>
                                        <p>{{$photo->description}}</p>
                                    </blockquote>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
