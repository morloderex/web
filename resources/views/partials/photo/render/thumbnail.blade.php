<!-- TODO: Lity -->
<img data-lity data-lity-target="{{ route('photo.show', $photo) }}" style="background-color: rgba(0,0,0,.2); cursor: pointer;" src="data:image/jpeg;base64,{!! base64_encode($photo->getThumbnail()) !!}"/>
