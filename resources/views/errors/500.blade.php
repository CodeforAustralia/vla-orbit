@extends ('orbit.master')

@section ('content')

	<div class="note note-danger">

	    <h4 class="block"> <strong>Error  <small>{{ $exception->getStatusCode() }}</small></strong> - Something went wrong please contact an admin to receive help with this issue <a href="mailto:{{ config('app.team_email') }}">{{ config('app.team_email') }}</a> </h4>
	</div>

    @if ( \App\Http\helpers::getRole() == 'Administrator' )
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title font-purple-soft bold uppercase">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_error"> Error JSON </a>
            </h4>
        </div>
        <div id="collapse_error" class="panel-collapse collapse">
            <div class="panel-body">

				<p> <strong>{{ $exception->getFile() }} in line {{ $exception->getLine() }}</strong> </p>
				<br>
				<p>  {{ json_encode($exception->getTrace()) }} </p>

            </div>
        </div>
    </div>
    @endif

@endsection
