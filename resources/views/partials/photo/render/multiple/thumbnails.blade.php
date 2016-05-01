<div class="row">
    @foreach($photos as $photo)
        <div class="col-md-3 col-sm-4 col-xs-6">
            @include('partials.photo.render.thumbnail', compact('photo'))
        </div>
    @endforeach
</div>