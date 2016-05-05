<div class="row">
    @foreach($characters as $character)
        <div class="col-md-4">
            @include('partials.home.characters.card', compact('character'))
        </div>
    @endforeach
</div>