@extends ('orbit.master')

@section ('content')
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
		<div class="note note-success">
			<h4 class="block">No matches found.</h4>
			<p>Sorry, no matching legal services found. For alternative referral options please check the non-legal referral list. <a href="https://docs.google.com/document/d/1meV27i-3VT2o4zqN7bCWDoAa8gia96adz6u0V9qVtgs/edit#" target="_blank">Link</a>.</p>
			<br>				
			<a href="/referral/create/location" class="btn green-jungle btn-lg push-right" > Create New Referral </a>
		</div>

  </div> <!-- Col Close -->
</div> <!-- Row Close -->
@endsection
