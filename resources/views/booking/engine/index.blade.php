@extends ('orbit.master')

@section ('content')

<div id="booking_engine">
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet">
        <h1 class="page-title col-xs-10"> Office Services </h1>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Result 1 -->
    <div class="row">
        <div class="col-xs-12">

            @foreach( $services as $match )

            <?php
            $current_sp_pos  = array_search( $match['ServiceProviderId'],  array_column( $service_providers, 'ServiceProviderId' ) );
            $current_sp_logo = $service_providers[ $current_sp_pos ]['ServiceProviderLogo'];
            $filters_class   = str_replace( ' ', '-', strtolower( $match['ServiceLevelName'] ) ) . ' ' . str_replace( ' ', '-', strtolower( $match['ServiceTypeName'] ) ) . ' ' . str_replace( ' ', '-', strtolower( $match['ServiceProviderTypeName'] ) ) ;
            ?>

            <div class="card-container col-xs-12 col-sm-6 col-xl-4 {{ $filters_class }}" id="{{ $match['ServiceId'] }}">
                <div class="col-xs-12 form-group result-card padding-0">
                    <div class="row card-top-info">

                        <div class="col-xs-12 col-sm-5">

                            <div class="col-sm-12">
                                <img src="{{ $current_sp_logo }}">
                            </div>
                            @if( $match['URL'] != '#')
                            <div class="col-sm-12 website">
                                <a href="http://{{ $match['URL'] }}" target="_blank">Visit website</a>
                            </div>
                            @endif

                        </div>

                        <div class="col-xs-12 col-sm-7 padding-0">
                            <a href="/service/show/{{ $match['ServiceId'] }}" target="_blank" class="btn green btn-xs pull-right">Edit</a>
                            <h3 class="margin-0 service-name"><strong>{{ $match['ServiceName'] }}</strong></h3>
                            <h4 class="service-provider-name">{{ $match['ServiceProviderName'] }}</h4>
                            @if( $match['Location'] != '#')
                            <p>
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <a href="http://maps.google.com/?q={{ $match['Location'] }}" target="_blank">{{ mb_strimwidth($match['Location'], 0, 40, "...") }}</a>
                            </p>
                            @endif
                            @if( $match['Phone'] != '#')
                            <p><i class="fa fa-phone" aria-hidden="true"></i> {{ $match['Phone'] }}</p>
                            @endif
                            @if( $match['OpenningHrs'] != '#' && $match['OpenningHrs'] != 'TBA' )
                            <p> <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $match['OpenningHrs'] }} </p>
                            @endif
                            @if( $match['Wait'] != '#' && $match['Wait'] != 'TBA' )
                            <p> <i class="fa fa-hourglass-end" aria-hidden="true"></i> {{ $match['Wait'] }} </p>
                            @endif
                        </div>

                    </div>
                    <hr>
                    <div class="row card-body-info">

                        <div class="col-xs-6">
                            <span class="col-xs-12">
                                <strong>
                                    Service Level
                                </strong>
                            </span>
                            <span class="col-xs-12" id="service-level">{{ $match['ServiceLevelName'] }} </span>
                        </div>

                        <div class="col-xs-6 border-left">
                            <span class="col-xs-12">
                                <strong>
                                    Service Type
                                </strong>
                            </span>
                            <span class="col-xs-12" id="service-type">{{ $match['ServiceTypeName'] }} </span>
                        </div>

                        <div class="col-xs-12 description">
                            {!! $match['Description'] !!}
                            <p>&nbsp;</p>
                        </div>

                    </div>

                    <hr>

                    <div class="row bottom-buttons margin-0 margin-bottom-10">
                        <div class="col-xs-12">
                            <h5 class="bold"><small> Booking Settings</small></h5>
                        </div>
                        <div class="col-xs-3">
                            <a v-on:click="openCalendar({{ $match['ServiceId'] }})" href="#"><small><i class="icon-calendar"></i> Days</small></a>
                        </div>
                        <div class="col-xs-3 border-left">
                            <a v-on:click="openSchedule({{ $match['ServiceId'] }})" href="#"><small><i class="icon-clock"></i> Hours</small></a>
                        </div>
                        <div class="col-xs-3 border-left">
                            <a v-on:click="openAdhoc({{ $match['ServiceId'] }})" href="#"><small><i class="fa fa-cog"></i> Ad hoc</small></a>
                        </div>
                        <div class="col-xs-3 border-left">
                            <a href="javascript:;"><small><i class="fa fa-users"></i> Add Resources</small></a>
                        </div>

                    </div>
                </div>
            </div>

            @endforeach

        </div> <!-- Col Close -->
    </div> <!-- Row Close -->

    @include("booking.engine.days_modal")
    @include("booking.engine.hours_modal")
    @include("booking.engine.adhoc_modal")
</div>

</div>
@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/readmore/readmore.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="/js/booking_engine.js?id={{ str_random(6) }}"></script>
@endsection

@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <link href="/css/booking_engine.css" rel="stylesheet" type="text/css" />
@endsection

@section('inline-scripts')
@endsection