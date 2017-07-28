@component('mail::message')

Hello, 

  
You contacted {{ $args['sendingServiceProvider']['ServiceProviderName'] }} on {{ date("Y-m-d") }} with a question about a legal matter. 
They have referred you to another service for more help:  


- <strong>Service Provider: </strong>{{ $args['ServiceProviderName'] }}
- <strong>Service Name: </strong>{{ $args['ServiceName'] }}
- <strong>Service Type: </strong>{{ $args['ServiceTypeName'] }}
@if( $args['Location'] != "#" )
- <strong>Address: </strong> {{ $args['Location'] }} [if relevant]
@endif
- <strong>Phone number:</strong> {{ $args['Phone'] }} [if relevant]
- <strong>Url:</strong> {{ $args['URL'] }}

- <strong>Service details: </strong>{{ $args['Description'] }}


Please, contact the service as per details above. Once you make contact they will assess whether they can help you or not. 

You can also pay a private lawyer to advise you. The Law Institute of Victoria's Legal Referral Service can put you in touch with a lawyer. With a referral letter from the Law Institute, participating law firms will see clients free of charge for up to the first 30 minutes.
If you book an interview, write your questions down beforehand, so you get as much out of the free 30 minutes as possible. 
If you discuss getting further help from the lawyer, make sure you know how much it will cost. 

This email was sent by ORBIT on behalf of {{ $args['sendingServiceProvider']['ServiceProviderName'] }}. Please do not reply to this email.


@endcomponent