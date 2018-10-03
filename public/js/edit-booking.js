var services = [];
var current_date = new Date();
var current_month = current_date.getMonth() + 1;
var current_year = current_date.getFullYear();
var current_service = '';

$(document).ready(function() {
    openEditBooking();
    closeEditBooking();
    updateBooking();
    printBooking();
});

function updateBooking()
{
    $("#update-booking").on("click", function(){
        if( typeof $("#time-options input:checked").val() != 'undefined' )
        {
            showLoading();
            let obj = {
                booking_ref: $("#bookingRef").text(),
                date_time: $("#time-options input:checked").val(),
                ClientBooking: currentEventInCalendar
            };

            var booking_ref = $("#bookingRef").text();
            var date_time = $("#time-options input:checked").val();
            var csrf = document.getElementsByName("_token")[0].content;
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': csrf
                },
                method: "POST",
                url: "/booking/updateBookingByRef",
                data: obj
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
    let date_input = $('#edit-booking-date');
    current_service = booking_id;
    date_input.datepicker({
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
                    event.stopPropagation();
                    var current_month = new Date(e.date).getMonth() + 1;
                    var current_year = String(e.date).split(" ")[3];
                    $("#edit-booking-date").val('');
                    getServiceDatesByDate( current_year, current_month, current_service); //Init dates
                });
                /**** Buggy method can't be used *****
                .on('changeMonth', function(e){});
                *******/
    getServiceDatesByDate( current_year, current_month, current_service); //Init dates
}

function getServiceDatesByDate( year, month, sv_id )
{
    if (typeof sv_id !== "undefined") {
        showLoading();
        let date_input = $('#edit-booking-date');

        $.ajax({
        method: "GET",
        url: "/booking/listDatesByDate/" + year + "/" + month + "/" + sv_id,
        async: false
        })
        .done(function( msg ) {
            if( Object.keys(msg).length > 1)
            {
                services = msg;
                date_input.datepicker( 'setDate',  year + "-" + month + "-01" );
                date_input.datepicker('setDatesDisabled', msg.unavailables);
                showAvailability();
                hideLoading();
            } else{
                hiddeAvailability();
                hideLoading();
            }
        });
    }
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

    var duration_slot = currentEventInCalendar.BookingBugServiceLength;
    if ($(".edit-booking").attr("id") == currentEventInCalendar.BookingBugInternalServiceId)
    {
        duration_slot = currentEventInCalendar.BookingBugInternalServiceLength;
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
        let moment = currentEventInCalendar.BookingDate;
        var booking_id = $(".edit-booking").attr("id");
        $(".booking-edit").addClass("hidden");
        $(".booking-information").hide().removeClass("hidden").fadeIn();

        $.when(AppCalendar.init()).then($('#calendar').fullCalendar('gotoDate', moment));
    });
}

function printBooking(){
    $( "#printBooking" ).click(function() {

        var printFrame = document.createElement('iframe');
        printFrame.name = "printFrame";
        printFrame.style.position = "absolute";
        printFrame.style.top = "-1000000px";
        document.body.appendChild(printFrame);
        var frameDoc = (printFrame.contentWindow) ? printFrame.contentWindow : (printFrame.contentDocument.document) ? printFrame.contentDocument.document : printFrame.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><body>'+
                                $('#SelectMatchLabel').html()+'<br>' +
                                $('#clientInformation').html()+ '<br>' +
                                $('#bookingInformation').html()+
                                '</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["printFrame"].focus();
            window.frames["printFrame"].print();
            document.body.removeChild(printFrame);
        }, 500);
    return false;
    });
}