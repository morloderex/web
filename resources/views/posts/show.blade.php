@extends('layouts.app')
	@section('content')
		<div class="jumbotron">
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
		<div class="well well-sm">
			  @include('partials.photo.render.multiple.images', ['photos'	=> $post->photos])
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">{{ $post->title }}</h3>
				  </div>
				  <div class="panel-body">
				    {{ $post->body }}
				  </div>
				  <div class="panel-footer">
				    Tags:
						<ul>
							@foreach($post->tags as $tag)
								<li>{{$tag->taggable->tag}}</li>
							@endforeach
						</ul>
				  </div>
				</div>
		</div>



		</div>
	@endsection
