@extends ('orbit.master')

@section ('content')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet">
        <h1 class="page-title col-xs-10"> Bookings by Service Provider</h1>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green sbold uppercase">Select Service Provider</span>
                    </div>
                </div>
                <div class="portlet-body">

                    <div class="row">
                        <div class="form-group">                    
                            <select class="form-control" id="service_provider_id" name="service_provider_id"> 
                                    <option value="0"> Select Service Provider </option>                               
                                @foreach($service_providers as $service_provider)
                                    @if( $service_provider['ServiceProviderTypeName'] == 'VLA'  )
                                        <option value="{{ $service_provider['ServiceProviderId'] }}"> {{ $service_provider['ServiceProviderName'] }} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Start -->  

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit  calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green sbold uppercase">Office Bookings</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div id="calendar" class="has-toolbar"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Calendar End -->  
    <!-- Modal Start -->
    @include("booking.booking_info_modal")
    @include("booking.booking_custom_sms_modal")
    <!-- Modal Fade Close-->                   
@endsection

@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />

    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />

    <link href="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>

    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>    

    <script src="/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.mockjax.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/js/calendar.js?id={{ str_random(6) }}" type="text/javascript"></script>

    <script src="/js/edit-booking.js?id={{ str_random(6) }}" type="text/javascript"></script>

    <script src="/js/remind-booking.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

@endsection

@section('inline-scripts')
    $(document).ready(function()       
    {     
        $("#contentLoading").modal("hide")        
    })
@endsection