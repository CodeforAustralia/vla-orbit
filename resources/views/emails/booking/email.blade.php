@component('mail::message')

@if( $args['send_booking'] == 1)

<p> <strong>Client's name: </strong>{{ $args['client']['FirstName'] . ' ' . $args['client']['LastName'] }} </p>

<p> <strong>Client's phone number: </strong>{{ $args['client']['Mobile'] }} </p>

<p> <strong>Client's email: </strong>{{ $args['client']['ClientEmail'] }} </p>

<p> <strong>Client's CIR number: </strong>{{ $args['booking']['CIRNumber'] }} </p>

<p> <strong>Service Name: </strong> {{ $args['service_name'] }} </p>

<p> <strong>Booking Time: </strong>{{ $args['booking']['Date'] . ' ' . $args['booking']['Time'] }} </p>

<p> <strong>Interpreter: </strong>{{ $args['booking']['Language'] }} </p>

<p> <strong>Description: </strong>{{ $args['booking']['Desc'] }} </p>


@endif

<h1> This email was generated automatically by ORBIT </h1>

@endcomponent