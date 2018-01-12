@extends ('orbit.master')

@section ('content')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet">
        <h1 class="page-title col-xs-10"> All Bookings </h1>
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
                            <div class="col-sm-6">
                                <h4><strong> Client Information </strong></h4>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>First name: </strong></label>                                
                                <a href="javascript:;" id="bookingFirstName" data-type="text" data-original-title="Enter First name"></a>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Last name: </strong></label>                                
                                <a href="javascript:;" id="bookingLastName" data-type="text" data-original-title="Enter Last name"></a>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Phone number: </strong></label>                                 
                                <a href="javascript:;" id="bookingPhone" data-type="text" data-original-title="Enter Phone number"></a>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Email: </strong></label>                                
                                <a href="javascript:;" id="bookingEmail" data-type="email" data-original-title="Enter Email"></a>
                            </div>

                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>CIR Number: </strong></label>                                
                                <a href="javascript:;" id="bookingCIRNumber" data-type="text" data-original-title="Enter CIR number"></a>
                            </div>                      
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <h4><strong> Booking Information </strong></h4>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Service Name: </strong></label>
                                <span id="bookingTitle"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Booking Time: </strong></label>
                                <a id="bookingTime" class="editable editable-click edit-booking"></a>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Interpreter Language: </strong></label>
                                <span id="bookingIntLanguage"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Booking Description: </strong></label>
                                <div id="bookingDescription" class="col-sm-7 padding-0" data-type="wysihtml5"></div>
                            </div>
                        
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Created By: </strong></label>
                                <span id="createdBy"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Sent status: </strong></label>
                                <span id="sentStatus"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Arrival status: </strong></label>
                                
                                <select class="form-control input-small booking-status input-sm">
                                    <option value="1" class="bg-white bg-font-white">Pending</option>
                                    <option value="2" class="bg-white font-green-jungle">Arrived</option>
                                    <option value="3" class="bg-white font-red">No Show</option>
                                </select>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label class="col-sm-5">&nbsp;</label>
                                
                                        <div class="tab-pane" id="booking_document_tab">
                                            <div class="scroller" style="height: auto;" data-always-visible="1" data-rail-visible1="1">
                                                <ul class="feeds">
                                                    
                                                </ul>
                                            </div>
                                        </div>
                            </div>

                            <input type="text" name="csrf" id="csrf" value="{{ csrf_token() }}" class="hidden">
                        </div>
                        <br>
                        <div class="row pull-right">
                            <a href="#" class="btn btn-xs green remind-booking">Send Reminder</a>                            
                            <a href="#" class="btn btn-xs btn-danger" id="delete-booking">Delete</a>
                            <a href="#" class="btn btn-xs btn-outline dark close-booking-edit" data-dismiss="modal" >Close</a>
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
                            <th width="10%" > Date </th>
                            <th width="10%" > Time </th>
                            <th width="10%"> Service </th>
                            <th width="10%"> First Name </th>
                            <th width="10%"> Last Name </th>
                            <th width="10%"> Email </th>
                            <th width="10%"> Phone </th>
                            <th width="10%"> Sent SMS </th>
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