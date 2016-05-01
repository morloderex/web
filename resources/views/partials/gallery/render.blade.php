<div class="row">
    @foreach($gallery->photos as $photo)
        <div class="col-md-4 img-portfolio">
            @include('partials.photo.render.image', $photo)
        </div>
    @endforeach
</div>