@extends('_layout.base')

@section('content')

    <div class="container"  id="items">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                @foreach($item->Stats as $stat)

                    <ul class="event-list item">
                        <li>
                            <div class="inventory">
                                <h2 class="title">{{ trans('site/items.item.dmg.min') }}:  {{ $item->category }}</h2>
                                <img class="img img-responsive" src="{{ asset('/images/items/'.$item->category.'.png') }}">
                            </div>
                            <div class="info">
                                <h2 class="title">{{ $item->name }}</h2>
                                <p class="desc">{{ $item->type }}</p>
                                <div class="col-sm-4 col-sm-offset-1 panel panel-primary no-border">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-stack-exchange fa-2x"></i> {{ trans('site/items.item.stats') }}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul>
                                            <li class="stat">{{ trans('site/items.item.dmg.min') }}:  {{ $stat->min_damage }}</li>
                                            <li class="stat">{{ trans('site/items.item.dmg.max') }}:  {{ $stat->max_damage }}</li>
                                            <li class="stat range">{{ trans('site/items.item.dmg.range') }}:  {{ $stat->range }}M</li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="col-sm-6 col-sm-offset-1 panel panel-primary no-border">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-stack-exchange fa-2x"></i> {{ trans('site/items.item.abilities') }}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul>
                                            @foreach($item->Abilities as $ability)
                                                <li class="ability">{{ trans('site/items.item.ability.name') }}: {{ $ability->name }}</li>
                                                <li class="ability">{{ trans('site/items.item.ability.desc') }}: {{ $ability->description }}</li>
                                                <li class="ability">{{ trans('site/items.item.ability.type') }}: {{ $ability->type }}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
@endsection