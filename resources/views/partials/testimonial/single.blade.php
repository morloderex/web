<div class="testiminial-block">
    <div class="row">
        <div class="profile-circle" style="background-color: rgba(145,169,216,.2);">
            <?php
                $photos = $testimonial->author->photos;
             ?>
            @if(!$photos->isEmpty())
                <div class="col-md-2 col-sm-2">
                    <img src="data:image/jpeg;base64,{!! base64_encode($photos->first()->getThumbnail()) !!}" class="img-responsive img-circle"/>
                </div>
            @endif
        </div>
        <div class="col-md-8 col-sm-8 testimonial-content">
            <h3>{{ $testimonial->title }}</h3>
            <p>
                {{ $testimonial->content }}
            </p>
            <div class="testimonial-author">
                Author <span>{{ $testimonial->author->name }}</span>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 comp-logo">
            <img src="http://placehold.it/300x180&text=Author Company Logo!" class="img-responsive"/>
        </div>
    </div>
</div>
<hr role="separator" class="divider"/>
