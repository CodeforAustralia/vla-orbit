@component('mail::message')

<p>Please contact this person for further assessment and assistance.</p>

___

<p>First name: {{ $args['client']['FirstName'] }}</p>
<p>Last  name: {{ $args['client']['LastName'] }}</p>
<p>If interpreter is needed, what language?: {{ ( isset($args['Language']) && $args['Language'] != '' ? $args['Language'] : 'N/A') }}</p>
<p>Client Intake Record - Legal Advice ID:  {{ ( isset($args['CIRNumber']) && $args['CIRNumber'] != '' ? $args['CIRNumber'] : 'N/A') }}</p>

@if( isset($args['client']['ClientEmail']) && $args['client']['ClientEmail'] != '')
<p>Email: {{ $args['client']['ClientEmail'] }}</p>
<p>Email contact OK? Yes</p>
@endif


@if( isset($args['postal_address']) && $args['postal_address'] != '')
<p>Postal Address: {{ $args['postal_address'] }}</p>
@endif
@if( isset($args['client']['Mobile']) && $args['client']['Mobile'] != '')
<p>Contact number: {{ $args['client']['Mobile'] }}</p>
@endif
 
@if( isset($args['phonepermission']) && $args['phonepermission'] != '')
<p>Is it safe to contact this client by SMS?: {{ $args['phonepermission'] }}</p>
@endif
@if( isset($args['phoneCallPermission']) && $args['phoneCallPermission'] != '')
<p>Is it safe to contact this client by phone call?: {{ $args['phoneCallPermission'] }}</p>
@endif
@if( isset($args['phoneMessagePermission']) && $args['phoneMessagePermission'] != '')
<p>Is it safe to leave a message? : {{ $args['phoneMessagePermission'] }}</p>
@endif
@if( isset($args['reContact']) && $args['reContact'] != '')
<p>Information to Recontact: {{ $args['reContact'] }}</p>
@endif

{!! $args['Desc'] !!}

<p>Thank you.</p>

*If you have any issues with this referral its content, appropriateness or even something positive (!) please contact the Legal Help Senior Team directly by emailing LH Senior Team to provide feedback or seek clarification.*

@endcomponent
