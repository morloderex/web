@extends('layouts.app')

@section('content')
   <div class="container-fluid">
      <div class="row-fluid">
         <h3>Accounts</h3>
         <div class="list-group">
            @foreach($accounts as $account)
               <a href="{{ route('armory.account.show', $account) }}"
                  class="list-group-item">{{ $account->username }}</a>
            @endforeach
         </div>
      </div>
</div>
@endsection
