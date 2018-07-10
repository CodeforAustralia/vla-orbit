@component('mail::message')

@if( $args['send_booking'] == 1)

<p> <strong>Client's name: </strong>{{ $args['client']['FirstName'] . ' ' . $args['client']['LastName'] }} </p>

<p> <strong>Client's phone number: </strong>{{ $args['client']['Mobile'] }} </p>

<p> <strong>Client's email: </strong>{{ $args['client']['ClientEmail'] }} </p>

@if( isset($args['booking']['ClientBooking']['CIRNumber']) )
<p> <strong>Client's CIR number: </strong>{{ $args['booking']['ClientBooking']['CIRNumber'] }} </p>

@endif
<p> <strong>Service Name: </strong> {{ $args['service_name'] }} </p>

<p> <strong>Booking Time: </strong>{{ $args['booking']['Date'] . ' ' . $args['booking']['Time'] }} </p>

<p> <strong>Interpreter: </strong>{{ $args['booking']['ClientBooking']['Language'] }} </p>

<p> <strong>Description: </strong>{{ $args['booking']['ClientBooking']['Description'] }} </p>

@endif

<h1> This email was generated automatically by {{ strtoupper(config('app.name')) }} </h1>

@endcomponent