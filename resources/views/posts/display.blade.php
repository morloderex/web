@include('partials.layout.head')
	<div class="container">
        <h4> Written by:
            <a href="{{ route('user.show', $post->user->id)}}">
                {{ $post->user->name }}
            </a>
        </h4>
        <h3>Categories:
            <em>
                @foreach($post->category as $category)
                    <a href="{{ route('category.show', $category->id) }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </em>
        </h3>
        <h3>Tags:</h3>
        <ul>
            @foreach($post->tags as $tag)
                @foreach($tag->tags as $content)
                    <li>
                        {{ $content->tag }}
                    </li>
                @endforeach
            @endforeach
        </ul>
        <div class="well well-sm">
            @include('partials.photo.render.multiple.thumbnails', ['photos'	=> $post->photos])
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $post->title }}</h3>
                </div>
                <div class="panel-body">
                    {{ $post->body }}
                </div>
            </div>
        </div>
    </div>