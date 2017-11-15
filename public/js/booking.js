var services = [];
var current_date = new Date();
var current_month = current_date.getMonth() + 1;
var current_year = current_date.getFullYear();
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
    $("#sp_services, #Language").on("change",function() {
        var booking_id = $( "#sp_services" ).val().split('-')[0];
        var booking_interpreter_id = $( "#sp_services" ).val().split('-')[1];
        
        //Clean times options
        $("#time-options").html('');
        $("#booking-date").val('')

        if( $("#Language" ).val() != '' ) // Requires interpreter
        {
            getBookingsByService( booking_interpreter_id );
        } 
        else // Do not requires interpreter
        {
            getBookingsByService(booking_id); 
        }

        $("#ServiceName").val( $("#sp_services option:selected").text() );
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
                    
                    if(e.hasOwnProperty("date"))
                    {
                        var day =  ('0'+ e.date.getDate()).slice(-2);
                        var month = ('0'+ (e.date.getMonth() + 1) ).slice(-2);
                        var year = e.date.getFullYear();
                        showTimes(day, month, year);     
                    }
                })
                .on('changeMonth', function(e){
                    var booking_id = $( "#sp_services" ).val().split('-')[0];
                    var booking_interpreter_id = $( "#sp_services" ).val().split('-')[1];
                    var current_year = String(e.date).split(" ")[3];
                    var current_month = new Date(e.date).getMonth() + 1;
                    
                    $("#booking-date").val('')
                    if( $("#Language" ).val() != '' ) // Requires interpreter
                    {
                        getServiceDatesByDate( current_year, current_month, current_service); //Init dates  
                    } 
                    else // Do not requires interpreter
                    {
                        getServiceDatesByDate( current_year, current_month, current_service); //Init dates            
                    }   
                });  
                /**** Buggy method can't be used *****
                .on('changeMonth', function(e){});  
                *******/
    getServiceDatesByDate( current_year, current_month, current_service); //Init dates     
}

function getServiceDatesByDate( year, month, sv_id )
{
    showLoading();
    if( sv_id.length > 0)
    {
        var dateInput = $('#booking-date');

        $.ajax({
          method: "GET",
          url: "/booking/listDatesByDate/" + year + "/" + month + "/" + sv_id,
          async: false
        })
          .done(function( msg ) {
            if( Object.keys(msg).length > 1)
            {
                services = msg;
                dateInput.datepicker( 'setDate', year + "-" + month + "-01" );
                dateInput.datepicker('setDatesDisabled', msg.unavailables);
                showAvailability();
                hideLoading();
            } else{
                hiddeAvailability();
                hideLoading();                
            }
          });

    } else {
        hiddeAvailability();
        hideLoading();
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

function showLoading()
{    
    $("#loading").hide().removeClass("hidden").fadeIn(); 
    $(".availability").addClass("hidden");
    $("#no-dates-availables").addClass("hidden");
}

function hideLoading()
{    
    $("#loading").addClass("hidden");
}

function showTimes(day, month, year)
{
    var current_date = year + '-' + month + '-' + day;
    for (index = 0; index < services._embedded.events.length; ++index) {
        if( services._embedded.events[index].date == current_date )
        {
            var times = services._embedded.events[index].times;
            var date = services._embedded.events[index].date;
            var event_id = services._embedded.events[index].event_id;            
        }
    }

    $("#time-options").html('');

    var duration_slot = 30;
    if( $("#Language" ).val() != '' ) // Requires interpreter
    {
        var duration_slot = 60;
    }

    for (index = 0; index < times.length; ++index) {
        if(times[index].avail == 1)
        {            
            var slot_time = new Date(times[index].datetime);
            var hour = ('0'+ slot_time.getHours() ).slice(-2);
            var minute = ('0'+ slot_time.getMinutes() ).slice(-2);
            var time = times[index].time;

            var end_time = new Date(slot_time.getTime() + ( duration_slot * 60 * 1000));
            var end_hour = ('0'+ end_time.getHours() ).slice(-2);
            var end_minute = ('0'+ end_time.getMinutes() ).slice(-2);

            var option = '<label class="mt-radio mt-radio-outline"><input type="radio" name="serviceTime" value="' + date + 'T' + hour + ':' + minute + '"> ' + hour + ':' + minute + ' - '+ end_hour +':' + end_minute + '<span></span></label>';
            $("#time-options").append(option);               
        }
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
            booking_interpreter_id = services_by_sp[index].BookingInterpritterServiceId;
            service_name = services_by_sp[index].ServiceName;
            if( booking_id != '' || booking_interpreter_id != '')
            {
                var option = '<option value="' + booking_id + '-' + booking_interpreter_id + '"> ' + service_name + ' </option>';
                //var option = '<option value="' + booking_id + '"> ' + service_name + ' </option>';
                $("#sp_services").append(option);
            }
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
