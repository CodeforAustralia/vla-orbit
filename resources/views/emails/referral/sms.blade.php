Hi, this is the referral details to contact {{ $args['ServiceProviderName'] }} for their {{ $args['ServiceName'] }} service.\n\n

Contact details:\n
{{ $args['Phone'] }}  \n
@if( $args['Location'] != "#" )
{{ $args['Location'] }} \n
@endif
{{ $args['URL'] }}\n\n

Once you make contact they will assess whether they can help you or not.