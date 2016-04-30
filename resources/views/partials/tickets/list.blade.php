	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Tickets</div>
		<div class="panel-body">
			<p>Text goes here...</p>
		</div>
	
		<!-- Table -->
		<table class="table">
			<thead>
			@foreach($tickets as $ticket)
				@foreach($ticket as $key => $value)
					<tr>
						<th>{{ $key }}</th>
					</tr>
				@endforeach
			@endforeach
			</thead>
			<tbody>
				@foreach($tickets as $ticket)
					<tr>
						<a href="{{ route('ticket.show', $ticket->id) }}">
							@foreach($ticket as $key => $value)
								<td>
									{{ $value }}
								</td>	
							@endforeach
						</a>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
