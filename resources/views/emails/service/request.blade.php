Please add the next {{ $args['name'] }}:<br> <br> <br>

<strong>Name:</strong> {{ isset( $args['matter_name'] ) ? $args['matter_name'] :  $args['criteria_name']  }} <br> <br>

@if( isset( $args['parent_matter'] ) )
<strong>Area of Law:</strong> {{ $args['parent_matter'] }}<br><br>
@else
<strong>Criteria Group:</strong> {{ $args['criteria_group'] }}<br><br>
@endif

<strong>Reason:</strong> <br>
{{ $args['reason'] }}<br><br>

Requested By: {{ $args['user']->name }}<br>
Contact Email: {{ $args['user']->email }}