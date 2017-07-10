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
	<link href="../assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')    
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="../assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
	<script src="../assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
@endsection

@section('inline-scripts')

var services = [];
var current_date = new Date();
var current_month = current_date.getMonth() + 1;
var current_service = '';

$(document).ready(function() { 
    service_provicer_change();
    service_change();    
    checkSecureContact();
});         

function service_provicer_change()
{
    $("#service_provider_id").on("change",function() {
        var sp_id = $( this ).val();
        getServicesBySP(sp_id);
    });

}

function service_change()
{
    $("#sp_services").on("change",function() {
        var booking_id = $( this ).val();
        getBookingsByService(booking_id);        
    });

}

function getBookingsByService(booking_id)
{
    var dateInput = $('#booking-date');
    current_service = booking_id;
    dateInput.datepicker({
                    format: "yyyy-mm-dd",
                    startDate: current_date.toISOString().split('T')[0],
                    daysOfWeekDisabled: [0,6],
                    todayHighlight: true
                }).on("changeDate", function(e) {
                    // `e` here contains the extra attributes

                    var month = e.date.getMonth() + 1;
                    var day =  e.date.getDate();                    

                    if(current_month != month)
                    {
                        current_month = month;
                        getServiceDatesByMonth(month, current_service); //Init dates

                    }
                    showTimes(day);

                });
                /**** Buggy method can't be used *****
                .on('changeMonth', function(e){});  
                *******/
    getServiceDatesByMonth(current_month, current_service); //Init dates     
}

function getServiceDatesByMonth(month, sv_id)
{
    if( sv_id.length > 0)
    {
        var dateInput = $('#booking-date');

        $.ajax({
          method: "GET",
          url: "/booking/listDatesByMonth/" + month + "/" + sv_id
        })
          .done(function( msg ) {
            dateInput.datepicker('setDatesDisabled', msg.unavailables);
            services = msg;
            showAvailability();
          });

    } else {
        hiddeAvailability();
    }
}

function hiddeAvailability()
{
    $(".availability").addClass("hidden");
    $("#no-dates-availables").hide().removeClass("hidden").fadeIn();    
}

function showAvailability()
{
    $(".availability").hide().removeClass("hidden").fadeIn();
    $("#no-dates-availables").addClass("hidden");
}

function showTimes(day)
{
    var times = services._embedded.events[day-1].times;
    var date = services._embedded.events[day-1].date;
    var event_id = services._embedded.events[day-1].event_id;
    $("#time-options").html('');
    for (index = 0; index < times.length; ++index) {
        
        var hour = ('0'+ new Date(times[index].datetime).getHours() ).slice(-2);
        var minute = ('0'+ new Date(times[index].datetime).getMinutes() ).slice(-2);
        var time = times[index].time;

        var option = '<label class="mt-radio mt-radio-outline"><input type="radio" name="serviceTime" value="' + date + 'T' + hour + ':' + minute + '"> ' + hour + ':' +              minute + '<span></span></label>';
        $("#time-options").append(option);        
    }
}

function getServicesBySP(sp_id){
    $.ajax({
      method: "GET",
      url: "/service/list_services_sp/" + sp_id
    })
      .done(function( services_by_sp ) {
        $("#sp_services").html('<option> </option>');
        for (index = 0; index < services_by_sp.length; ++index) {
            booking_id = services_by_sp[index].BookingServiceId;
            service_name = services_by_sp[index].ServiceName;
            var option = '<option value="' + booking_id + '"> ' + service_name + ' </option>';
            $("#sp_services").append(option);
        }        
        if( $("#sp_services option")[0] )
        {
            var booking_id = $( $("#sp_services option")[0] ).val();
            getBookingsByService(booking_id);  
        } else {
            hiddeAvailability();
        }
      });
}

function checkSecureContact()
{
    $("input[name='emailpermission']").on("change",function() {
        if($( this ).val() == 'No')
        {
            $( "#email" ).val("");
            $( "#email" ).prop('disabled', true);
        } else{        
            $( "#email" ).prop('disabled', false);
        }
    });

    $("input[name='phonepermission']").on("change",function() {
        if($( this ).val() == 'No')
        {
            $( "#mobile" ).val("")
            $( "#mobile" ).prop('disabled', true);
        } else{        
            $( "#mobile" ).prop('disabled', false);
        }
    });
}
@endsection