<div class="photo">
    <div onclick="$('.photo-index').show()">
        <div class="photo-index hidden">

            <p>Gender</p>
            <p>
                <span>
                    {{ $character->gender }}
                </span>
            </p>

            <p>Class</p>
            <p>
                <span>
                    {{ $character->class }}
                </span>
            </p>

            <p>Race</p>
            <p>
                <span>
                    {{ $character->race }}
                </span>
            </p>

        </div>
        <div class="photo-wrapper">
            @include('partials.photo.render.multiple.thumbnails', ['photos' => $character->photos])
        </div>
        <div class="photo-description">
            <h3>Name</h3>
            <p>{{ $character->name }}</p>
            <a href="{{ route('armory.character.show', $character) }}">
                Armory link
            </a>
        </div>
    </div>
</div>