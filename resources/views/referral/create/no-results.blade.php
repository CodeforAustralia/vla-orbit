@extends ('orbit.master')

@section ('content')
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
		<div class="note note-success">
			<h4 class="block">No matches found.</h4>
			<p> This client is not elegible for any service, please check the non legal issues page. <a href="#">Link</a> </p>
			<br>
				
			<a href="/referral/create/location" class="btn green-jungle btn-lg push-right" > Start again </a>
		</div>

  </div> <!-- Col Close -->
</div> <!-- Row Close -->
@endsection