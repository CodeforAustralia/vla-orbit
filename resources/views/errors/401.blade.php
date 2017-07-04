@extends ('orbit.master')

@section ('content')

	<div class="note note-danger">

	    <h4 class="block"> <strong>Error  <small>{{ $exception->getStatusCode() }}</small></strong> </h4>
	    
	    <p>Access Denied</p>

	    @if( isset($_GET['debug']) )
		<p> <strong>{{ $exception->getFile() }} in line {{ $exception->getLine() }}</strong> </p>
		<br>
		<p>  {{ json_encode($exception->getTrace()) }} </p>
		@endif
	</div>

@endsection
