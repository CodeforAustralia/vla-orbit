@extends ('orbit.master')

@section ('content')

	<div class="note note-danger">

		<h4 class="block"> <strong>Error  <small>{{ $exception->getStatusCode() }}</small></strong> </h4>
		<p><h4>Page not found</h4></p>
		<p>If you need help, please contact an admin <a href="mailto:{{ config('app.team_email') }}">{{ config('app.team_email') }}</a></p>
		@if( isset($_GET['debug']) )
		<p> <strong>{{ $exception->getFile() }} in line {{ $exception->getLine() }}</strong> </p>
		<br>
		<p>  {{ json_encode($exception->getTrace()) }} </p>
		@endif
	</div>

@endsection
