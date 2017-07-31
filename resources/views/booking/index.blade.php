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

    <!-- Modal Start -->     
    <div class="modal fade" id="bookingInfo" tabindex="-1" role="dialog" aria-labelledby="bookingInfo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">                
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h5 class="modal-title" id="SelectMatchLabel"><strong>Booking #<span id="bookingRef"></span></strong></h5>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <h4><strong> Client Information </strong></h4>
                            <hr>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Client's Name: </strong></label>
                                <span id="bookingName"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Client's phone number: </strong></label>
                                <span id="bookingPhone"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Client's email: </strong></label>
                                <span id="bookingEmail"></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-5"><strong>Client's CIR Number: </strong></label>
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
                            <a href="#" class="btn btn-danger delete-content" id="delete-booking">Delete</a>
                            <a href="#" class="btn btn-outline dark" data-dismiss="modal" >Cancel</a>
                        </div>
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
              <span class="caption-subject font-green sbold uppercase">Office Bookings</span>
            </div>
          </div>
        <div class="portlet-body">
            <div class="table-container">
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

    <!-- Content -->
    <div class="row"> 
      <div class="col-xs-12">
        <div class="portlet light">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold"><i class="icon-list font-green"></i>&nbsp;  Recieved Bookings</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-xs-12">
                <p>In this data table we need the following columns:</p>
                <ul>
                    <li>ID</li>
                    <li>Service Name</li>
                    <li>Date</li>
                    <li>Time</li>
                    <li>First Name</li>
                    <li>Last Name</li>
                    <li>Email</li>
                    <li>Phone</li>
                    <li>Safe to Contact? (In this column we will have two icons, one for phone and one for email. If 'safe to contact' is selected for either turn the icon to green-jungle and if not set to greyed out)</li>
                    <li>Requirements (In this column we will have a disability icon that turns blue if the user has disability requirements and a translator icon that turns blue if a translator is required)</li>
                    <li>Actions (Buttons: Edit, Delete)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Content -->
    <div class="row"> 
      <div class="col-xs-12">
        <div class="portlet light">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold"><i class="icon-list font-green"></i>&nbsp;  Sent Bookings</span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="row">
              <div class="col-xs-12">
                <p>In this data table we need the following columns:</p>
                <ul>
                    <li>ID</li>
                    <li>Service Name</li>
                    <li>Date</li>
                    <li>Time</li>
                    <li>First Name</li>
                    <li>Last Name</li>
                    <li>Email</li>
                    <li>Phone</li>
                    <li>Safe to Contact? (In this column we will have two icons, one for phone and one for email. If 'safe to contact' is selected for either turn the icon to green-jungle and if not set to greyed out)</li>
                    <li>Requirements (In this column we will have a disability icon that turns blue if the user has disability requirements and a translator icon that turns blue if a translator is required)</li>
                    <li>Actions (Buttons: Edit, Delete)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
                
@endsection

@section('styles')
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/js/calendar.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
@endsection
