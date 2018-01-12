@component('mail::message')

<p>Please contact this person to provide phone advice.</p>

___

<p>First name: {{ $args['client']['FirstName'] }}</p>
<p>Last  name: {{ $args['client']['LastName'] }}</p>
<p>If interpreter is needed, what language?: {{ ( isset($args['Language']) && $args['Language'] != '' ? $args['Language'] : 'N/A') }}</p>
<p>Client Intake Record - Legal Advice ID:  {{ ( isset($args['CIRNumber']) && $args['CIRNumber'] != '' ? $args['CIRNumber'] : 'N/A') }}</p>

@if( isset($args['client']['ClientEmail']) && $args['client']['ClientEmail'] != '')
<p>Email: {{ $args['client']['ClientEmail'] }}</p>
<p>Email contact OK? Yes</p>
@endif

@if( isset($args['client']['Mobile']) && $args['client']['Mobile'] != '')
<p>Contact number: {{ $args['client']['Mobile'] }}</p>
@endif

{!! $args['Desc'] !!}

<p>Thank you.</p>

*If you have any issues with this referral its content, appropriateness or even something positive (!) please contact the Legal Help Senior Team directly by emailing LH Senior Team to provide feedback or seek clarification.*

@endcomponent