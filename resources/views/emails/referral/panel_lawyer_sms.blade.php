@php
	$nearest = json_decode($args['Nearest'], true);
	$nearest = array_slice( $nearest, 0,  5 );
@endphp
Hi, here are the details to contact\n\n
@foreach( $nearest as $near )
{{ strtoupper(strtolower($near['office']['firm_name']) ) }}\n
{{ $near['office']['address'] }}\n
{{ $near['office']['phone'] or '' }}\n\n
@endforeach
Once you make contact they will assess whether they can help you or not.