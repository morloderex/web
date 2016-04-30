@foreach($photos as $photo)
    @include('partials.photo.render.thumbnail', compact('photo'))
@endforeach