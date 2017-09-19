@extends ('orbit.master')

@section ('content')
    
    @include ('orbit.alert')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet">
        <h1 class="page-title col-xs-10"> All Bookings </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/booking/new">New Booking</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Calendar Start -->  
    @if(Auth::user()->sp_id != 112)
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
    @endif
    <!-- Calendar End -->  

    <!-- Modal Start -->     
    <div class="modal fade" id="bookingInfo" tabindex="-1" role="dialog" aria-labelledby="bookingInfo" data-backdrop="static" data-keyboard="false" aria-hidden="false" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">                
                    <button type="button" class="close close-booking-edit" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h5 class="modal-title" id="SelectMatchLabel"><strong>Booking #<span id="bookingRef"></span></strong></h5>
                </div>

                <div class="modal-body">
                    <div class="container-fluid booking-information">
                        <div class="row">
                            <h4><strong> Client Information </strong></h4>
                            <hr>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Name: </strong></label>
                                <span id="bookingName"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Phone number: </strong></label> 
                                <span id="bookingPhone"></span>
                                
                                <a href="#" class="btn btn-xs green remind-booking">Send Reminder</a>                                

                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Email: </strong></label>
                                <span id="bookingEmail"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>CIR Number: </strong></label>
                                <span id="bookingCIRNumber"></span>
                            </div>                      
                        </div>
                        <br>
                        <div class="row">
                            <h4><strong> Booking Information </strong></h4>
                            <hr>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Name: </strong></label>
                                <span id="bookingTitle"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Booking Time: </strong></label>
                                <span id="bookingTime"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Interpreter Language: </strong></label>
                                <span id="bookingIntLanguage"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Booking Description: </strong></label>
                                <span id="bookingDescription"></span>
                            </div>
                        </div>
                        <div class="row pull-right">                            
                            <a href="#" class="btn green edit-booking hidden">Edit</a>
                            <a href="#" class="btn btn-danger" id="delete-booking">Delete</a>
                            <a href="#" class="btn btn-outline dark close-booking-edit" data-dismiss="modal" >Close</a>
                        </div>
                    </div>
                    <div class="hidden booking-edit">
                        <div class="row availability hidden">
                            <div class="col-xs-12 padding-bottom-20">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Date:</label>                
                                        <input type="text" class="form-control input-medium" id="booking-date" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row availability hidden">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Available Times:</label>
                                        <div class="mt-radio-list" id="time-options">
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="row hidden" id="no-dates-availables">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <h3>Sorry there are no dates available for this service</h3>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="row hidden" id="loading">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">                
                                        <i class="fa fa-spinner fa-spin font-green-jungle" style="font-size:24px"></i>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <button id="update-booking" class="btn green">Update</button>
                    </div>
                </div>
            </div>
        </div><!-- Modal Dialogue Close-->
    </div><!-- Modal Fade Close-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold uppercase"><i class="icon-list font-green"></i>&nbsp;  Your Bookings</span>
            </div>
          </div>
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_bookings">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%" > Id </th>
                            <th width="10%"> Service Name </th>
                            <th width="10%" > Date </th>
                            <th width="10%"> First Name </th>
                            <th width="10%"> Last Name </th>
                            <th width="10%"> Email </th>
                            <th width="10%"> Phone </th>
                            <th width="10%"> </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
                
@endsection

@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />

    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>    
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