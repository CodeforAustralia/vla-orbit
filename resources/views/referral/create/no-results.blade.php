@extends ('orbit.master')

@section ('content')
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
		<div class="note note-success">
			<h4 class="block">No matches found.</h4>
			<p>We were unable to find any free legal services. Please see our alternative referral options <a href="http://vla.vic.gov.au">here</a>.</p>
			<br>				
			<a href="/referral/create/location" class="btn green-jungle btn-lg push-right" > Create New Referral </a>
		</div>

  </div> <!-- Col Close -->
</div> <!-- Row Close -->
@endsection
