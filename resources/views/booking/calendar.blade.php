@extends ('orbit.master')

@section ('content')
    
    @include ('orbit.alert')

    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
            <div class="portlet light portlet-fit  calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green sbold uppercase">Bookings</span>
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
        <div class="col-md-1">
    	</div>
    </div>

  <!-- Modal Start -->     
  <div class="modal fade" id="bookingInfo" tabindex="-1" role="dialog" aria-labelledby="bookingInfo">
    <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">                
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
	                		<label class="col-sm-5"><strong>Is there risk of violence? </strong></label>
	                    	<span id="bookingIsSafe"></span>
	                	</div>
	                	<div class="col-sm-12">
	                		<label class="col-sm-5"><strong>Booking Description: </strong></label>
	                    	<span id="bookingDescription"></span>
	                	</div>
                	</div>
	                <div class="row pull-right">
	                	<a href="#" class="btn btn-danger delete-content" id="delete-booking">Delete</a>
	                </div>
                </div>
            </div>
        </div>

    </div><!-- Modal Dialogue Close-->
  </div><!-- Modal Fade Close-->
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