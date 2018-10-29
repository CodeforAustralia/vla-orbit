@component('mail::message')

<p>Please contact this person to provide phone advice.</p>

___

<p>First name: {{ $args['first_name'] }}</p>
<p>Last  name: {{ $args['last_name'] }}</p>
<p>If interpreter is needed, what language?: {{ ( isset($args['int_language']) && $args['int_language'] != '' ? $args['int_language'] : 'N/A') }}</p>
<p>Complex needs? {{ ( isset($args['IsComplex']) && $args['IsComplex'] != '' ? ($args['IsComplex'] == 1 ? 'Yes' : 'No' ) : 'N/A') }}</p>
<p>Client Intake Record - Legal Advice ID:  {{ ( isset($args['CIRNumber']) && $args['CIRNumber'] != '' ? $args['CIRNumber'] : 'N/A') }}</p>

@if( isset($args['client']['ClientEmail']) && $args['client']['ClientEmail'] != '')
<p>Email: {{ $args['client']['ClientEmail'] }}</p>
<p>Email contact OK? Yes</p>
@endif

@if( isset($args['phone']) && $args['phone'] != '')
<p>Contact number: {{ $args['phone'] }}</p>
@endif

@if( isset($args['reContact']) && $args['reContact'] != '')
<p>Instructions re contact: {{ $args['reContact'] }}</p>
@endif

{!! $args['Desc'] !!}

<p>Thank you.</p>

*If you have any issues with this referral its content, appropriateness or even something positive (!) please contact the Legal Help Senior Team directly by emailing LH Senior Team to provide feedback or seek clarification.*

@endcomponent