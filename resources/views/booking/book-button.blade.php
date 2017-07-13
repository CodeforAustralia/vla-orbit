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

@section('styles')    
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')    
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
	<script src="/js/booking.js" type="text/javascript"></script>
@endsection

