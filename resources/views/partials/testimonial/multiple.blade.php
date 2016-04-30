<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="quote"><i class="fa fa-quote-left fa-4x"></i>
                <h1>Testimonials</h1>
            </div>
            @foreach($testimonials as $testimonial)
                @include('partials.testimonial.single', compact('testimonial'))
            @endforeach
        </div>
    </div>
</div>