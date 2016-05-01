@foreach($photos as $photo)
    @include('partials.photo.render.image', compact('photo'))
@endforeach    