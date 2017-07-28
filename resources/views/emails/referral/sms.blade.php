Hi, please find your referral details here: \n\n\n

- Service Provider: {{ $args['ServiceProviderName'] }} \n\n
- Service Name: {{ $args['ServiceName'] }} \n\n
- Service Type: {{ $args['ServiceTypeName'] }} \n\n
- Phone number: {{ $args['Phone'] }} [if relevant] \n\n
@if( $args['Location'] != "#" )
- Address: {{ $args['Location'] }} [if relevant] \n\n
@endif
- URL: {{ $args['URL'] }}\n\n

Once you make contact they will assess whether they can help you or not. 