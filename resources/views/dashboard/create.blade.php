@extends ('orbit.master')


@section ('content')
	<div class="portlet box green">
	    <div class="portlet-title">
	        <div class="caption">
	        	Edit Message in Dashboard
	        </div>
	    </div>
        @include('dashboard.form')
    </div>
@endsection