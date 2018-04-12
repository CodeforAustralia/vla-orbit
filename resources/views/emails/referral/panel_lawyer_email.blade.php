@component('mail::message')

<div style="text-align: right">Ref. ID: {{ $args['RefNo'] }}</div>

Hello,
  
You contacted {{ $args['sendingServiceProvider']['ServiceProviderName'] }} on {{ date("d-m-Y") }} with a question about a legal matter. They have referred you to another service for more help.

If you are eligible for a grant of aid, these lawyers may be able to run your case for you:

@php
	$nearest = json_decode($args['Nearest'], true);	
	$nearest = array_slice( $nearest, 0,  5 );
@endphp

<ul>
@foreach( $nearest as $near )
	<li>
		{{ strtoupper(strtolower($near['office']['firm_name'])) }}<br>
		{{ $near['office']['address'] }}<br>
		{{ $near['office']['phone'] or '' }}<br>
	</li>
	<br>
@endforeach
</ul>
Sometimes the lawyers may be busy, so be prepared to try a few different ones.

If none of the above listed lawyers can help click this <a href="{{ $args['URL'] }}">link</a> to see a map of all lawyers and law firms able to apply for a grant of legal aid to run your legal matter.

*This email was sent by ORBIT on behalf of {!! $args['sendingServiceProvider']['ServiceProviderName'] !!}. Please do not reply to this email.*

@endcomponent