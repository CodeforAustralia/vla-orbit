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

    var service_call = function( reminder ) {
        $.ajax({
                  method: "GET",
                  url: "/booking/sendSmsReminder",
                  data: { reminder: reminder }
                })
                  .done(function( msg ) {                    
                    swal( msg.message, "" , msg.success );
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