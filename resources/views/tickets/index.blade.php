@extends('layouts.app')
	@section('content')

		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
			@foreach($accounts as $account)
				<li role="presentation">
					<a href="#account__{{ $account->id }}" aria-controls="tab" role="tab" data-toggle="tab">{{ $account->name }}</a>
				</li>
			@endforeach	
			</ul>
		
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane" id="account__{{ $account->id }}">
					@include('partials.tickets.list', $account->tickets)
				</div>
			</div>
		</div>

	@endsection