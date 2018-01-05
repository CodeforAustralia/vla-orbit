var sendReminderWithParams = function(refNo)
{
    for (let index = 0; index < EventsInCalendar.length; index++) 
    {
        const element = EventsInCalendar[index].data;        
        if(element.RefNo === refNo )
        {            
            var reminder = {
                client_phone:  element.Mobile,
                client_name:   element.FirstName,
                bb_service_id: element.ServiceId,
                date:          element.BookingDate,
                time:          element.BookingTime
            }

            $.ajax({
                method: "GET",
                url: "/booking/sendSmsReminder",
                data: { reminder: reminder, booking: element }
            })
            .done(function( msg ) {                    
                swal( msg, "" , "success" );
            });
        }
    }
}

var RemindBooking = function () {

    var sendReminder = function () {
        $('.remind-booking').on('click', function(){
            var reminder = {
                client_phone:  $('#bookingPhone').text(),
                client_name:   $('#bookingName').text(),
                bb_service_id: $(".edit-booking").attr("id"),
                date:          $('#bookingTime').text().split(" ")[0],
                time:          $('#bookingTime').text().split(" ")[1]
            }
            service_call(reminder);
        });  
    }

    //currentEventInCalendar is a global variable that is at calendar.js
    var service_call = function( reminder ) {
        $.ajax({
                  method: "GET",
                  url: "/booking/sendSmsReminder",
                  data: { reminder: reminder, booking: currentEventInCalendar }
                })
                  .done(function( msg ) {                    
                    swal( msg, "" , "success" );
                  });
    }

    return {
        //main function to initiate the module
        init: function () {
            sendReminder();
        }

    };

}();

jQuery(document).ready(function() {
    RemindBooking.init();
});