@component('mail::message')

@if( $args['send_booking'] == 1)

<p> <strong>Client's name: </strong>{{ $args['booking']['first_name'] . ' ' . $args['booking']['last_name'] }} </p>

<p> <strong>Client's phone number: </strong>{{ $args['booking']['contact'] }} </p>

@if( isset($args['booking']['CIRNumber']) )
<p> <strong>Client's CIR number: </strong>{{ $args['booking']['CIRNumber'] }} </p>

@endif
<p> <strong>Service Name: </strong> {{ $args['service_name'] }} </p>

<p> <strong>Booking Time: </strong>{{ $args['booking']['date'] . ' ' . $args['booking']['hour'] }} </p>

<p> <strong>Interpreter: </strong>{{ $args['booking']['int_language'] }} </p>

<p> <strong>Description: </strong>{{ $args['booking']['comment'] }} </p>

@endif

<h1> This email was generated automatically by {{ strtoupper(config('app.name')) }} </h1>

@endcomponent