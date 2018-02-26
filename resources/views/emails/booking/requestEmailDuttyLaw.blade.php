@component('mail::message')

<p>The following client has been assessed as eligible to receive advice and/or in-court advocacy from the Duty Lawyer service.</p>

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

<p>Please ensure that the Client Intake Record and Client information are printed and provided to the lawyer for this appointment.</p>

<p>Thank you.</p>

*If you have any issues with this referral its content, appropriateness or even something positive (!) please contact the Legal Help Senior Team directly by emailing LH Senior Team to provide feedback or seek clarification.*

@endcomponent