@component('mail::message')

Hello, 

  
You contacted {{ $args['sendingServiceProvider']['ServiceProviderName'] }} on {{ date("Y-m-d") }} with a question about a legal matter. 
They have referred you to another service for more help:  


- <strong>Service Provider: </strong>{{ $args['ServiceProviderName'] }}
- <strong>Service Name: </strong>{{ $args['ServiceName'] }}
- <strong>Service Type: </strong>{{ $args['ServiceTypeName'] }}
@if( $args['Location'] != "#" )
- <strong>Address: </strong> {{ $args['Location'] }}
@endif
- <strong>Phone number:</strong> {{ $args['Phone'] }}
- <strong>Url:</strong> {{ $args['URL'] }}

- <strong>Service details: </strong>{!! $args['Description'] !!}


<p style="font-style:italic;">This email was sent by ORBIT on behalf of {!! $args['sendingServiceProvider']['ServiceProviderName'] !!}. Please do not reply to this email.</p>


@endcomponent