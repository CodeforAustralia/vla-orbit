var sendReminderWithParams = function(refNo)
{
    $.ajax({
        method: "GET",
        url: "/booking/listCalendarByUser",
        data: {}
    })
    .done(function( msg ) {
        let Bookings = msg.data;
        for (let index = 0; index < Bookings.length; index++)
        {
            const element = Bookings[index];
            if(element.RefNo === refNo )
            {
                let reminder = {
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
    });
}

var RemindBooking = function () {

    var get_reminder_info = function () {
        let reminder = {
            client_phone: $('#bookingPhone').text(),
            client_name: $('#bookingName').text(),
            bb_service_id: $(".edit-booking").attr("id"),
            date: $('#bookingTime').text().split(" ")[0],
            time: $('#bookingTime').text().split(" ")[1]
        }
        return reminder;
    }

    var sendcustomSms = function () {
        //Opening modal
        $('.custom-sms-booking').on('click', function () {
            $('#bookingCustomSms').modal('show');
            getCurrentServiceTemplate();
        });
        //Sending SMS
        $('.send-custom-sms').on('click', function () {
            let template = $('#custom-sms').val();
            service_call(template);
            $('#bookingCustomSms').modal('hide');
        });
    }

    var sendReminder = function () {
        $('.remind-booking').on('click', function () {
            service_call();
        });
    }

    //currentEventInCalendar is a global variable that is at calendar.js
    var service_call = function(template = '') {
        let reminder = get_reminder_info();
        reminder.template = template;
        $.ajax({
                    method: "GET",
                    url: "/booking/sendSmsReminder",
                    data: { reminder: reminder, booking: currentEventInCalendar }
                })
                .done(function( msg ) {
                    swal( msg, "" , "success" );
                });
    }

    var getCurrentServiceTemplate = function () {
        $("#contentLoading").modal("show");
        let sv_id = $(".edit-booking").attr("id");

        $.ajax({
            method: "GET",
            url: "/sms_template/getTemplateByServiceBookingId",
            data: {
                sv_id: sv_id,
                booking: currentEventInCalendar
            }
        })
        .done(function (msg) {
            let template = msg;
            $('#custom-sms').val(template);
            $("#contentLoading").modal("hide");
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            sendReminder();
            sendcustomSms();
        }

    };

}();

jQuery(document).ready(function() {
    RemindBooking.init();
});