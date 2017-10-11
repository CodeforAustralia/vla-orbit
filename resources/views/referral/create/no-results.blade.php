@extends ('orbit.master')

@section ('content')
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">

	<br>
	<br>

	<div class="text-center">
		<div class="snap-block">
			<img class="snap-image" src="/assets/global/img/sad_search_man.png" width="40%">
		</div>
		<div class="snap-text">
			<p class="bold">No matching legal service found. For alternative referral options check out the non-legal referral list <a href="https://docs.google.com/document/d/1meV27i-3VT2o4zqN7bCWDoAa8gia96adz6u0V9qVtgs/edit#" target="_blank" class="font-green-jungle">here</a>. </p>
			
			<a href="/referral/create/location" class="btn green-jungle btn-lg push-right"> Create New Referral </a>
		</div>
	</div>
	
  </div> <!-- Col Close -->
</div> <!-- Row Close -->
@endsection
