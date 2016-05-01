 @extends('layouts.app')
    @section('content')

        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group" id="accordion">
                    @foreach($faqs as $key => $faq)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse__faq-{{$key}}">
                                        {{ $faq['question'] }}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse__faq-{{$key}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    {!! Markdown::convertToHtml($faq['answer']) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @endsection