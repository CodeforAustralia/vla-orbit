var services = [];
var current_date = new Date();
var current_month = current_date.getMonth() + 1;
var current_service = '';

$(document).ready(function() { 
    openEditBooking();
    closeEditBooking();
    updateBooking();
});  

function updateBooking()
{
    $("#update-booking").on("click", function(){
        if( typeof $("#time-options input:checked").val() != 'undefined' )
        {
            showLoading();
            var booking_ref = $("#bookingRef").text();
            var date_time = $("#time-options input:checked").val();
            $.ajax({
              method: "GET",
              url: "/booking/updateBooking/" + booking_ref + "/" + date_time
            })
              .done(function( msg ) {
                swal( "Booking Updated", "Booking #" + booking_ref + " has been updated", "success" );
                $(".close-booking-edit").click();                
              });

        } else {
            alert('Please choose a time');
        }
    }); 
}
function openEditBooking()
{
    $(".edit-booking").on("click", function(){

        var booking_id = $(".edit-booking").attr("id");
        $(".booking-information").addClass("hidden");
        $(".booking-edit").hide().removeClass("hidden").fadeIn(); 

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
                    if(e.hasOwnProperty("date"))
                    {
                        var day =  ('0'+ e.date.getDate()).slice(-2);
                        var month = ('0'+ (e.date.getMonth() + 1) ).slice(-2);
                        var year = e.date.getFullYear();
                        showTimes(day, month, year);     
                    }
                })
                .on('changeMonth', function(e){
                    var current_month = new Date(e.date).getMonth() + 1;
                    $("#booking-date").val('');                    
                    getServiceDatesByMonth(current_month, booking_id); 
                });  
                /**** Buggy method can't be used *****
                .on('changeMonth', function(e){});  
                *******/
    getServiceDatesByMonth(current_month, current_service); //Init dates     
}

function getServiceDatesByMonth(month, sv_id)
{
    showLoading();
    var dateInput = $('#booking-date');

    $.ajax({
      method: "GET",
      url: "/booking/listDatesByMonth/" + month + "/" + sv_id
    })
      .done(function( msg ) {        
        if( Object.keys(msg).length > 1)
        {
            services = msg;
            dateInput.datepicker( 'setDate', "2017-"+ month +"-01" );
            dateInput.datepicker('setDatesDisabled', msg.unavailables);
            showAvailability();
            hideLoading();
        } else{
            hiddeAvailability();
            hideLoading();                
        }
      });
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
    if( $("#bookingIntLanguage" ).text() != '' ) // Requires interpreter
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

function closeEditBooking()
{
    $(".close-booking-edit").on("click", function(){

        var booking_id = $(".edit-booking").attr("id");
        $(".booking-edit").addClass("hidden");
        $(".booking-information").hide().removeClass("hidden").fadeIn(); 
        AppCalendar.init();
    }); 
}