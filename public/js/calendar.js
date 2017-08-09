var AppCalendar = function() {

    return {
        //main function to initiate the module
        init: function() {
            $("#contentLoading").modal();
            this.initCalendar();
        },

        initCalendar: function() {

            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var h = {};

            if (App.isRTL()) {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        right: 'title, prev, next',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        right: 'title',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today, prev,next'
                    };
                }
            } else {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        left: 'title, prev, next',
                        center: '',
                        right: 'today,month,agendaWeek,agendaDay'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        left: 'title',
                        center: '',
                        right: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                }
            }

            var initDrag = function(el) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim(el.text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                el.data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                el.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            };

            var addEvent = function(title) {
                title = title.length === 0 ? "Untitled Event" : title;
                var html = $('<div class="external-event label label-default">' + title + '</div>');
                jQuery('#event_box').append(html);
                initDrag(html);
            };

            $('#external-events div.external-event').each(function() {
                initDrag($(this));
            });

            $('#event_add').unbind('click').click(function() {
                var title = $('#event_title').val();
                addEvent(title);
            });

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                header: h,
                defaultView: 'agendaWeek', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/ 
                slotDuration: '00:15:00',
                minTime : '07:00:00',
                maxTime : '19:00:00',
                weekends: false,
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function(date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');
                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;
                    copiedEventObject.className = $(this).attr("data-class");

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                eventSources: [
                {
                    url: '/booking/listCalendar',
                    type: 'GET',
                    beforeSend: function () {                        
                        $("#contentLoading").modal();
                    },
                    error: function() {
                        alert('there was an error while fetching events!');
                        $("#contentLoading").modal('hide');
                    },
                    success: function () {
                        $("#contentLoading").modal('hide');
                    }
                }],
                eventClick: function(calEvent, jsEvent, view) {
                    
                    $("#bookingRef").text(calEvent.data.BookingRef);
                    $("#bookingTitle").text(calEvent.data.ServiceName);
                    $("#bookingTime").text(calEvent.data.BookingDate + " " + calEvent.data.BookingTime);
                    $("#bookingName").text(calEvent.data.FirstName + " " + calEvent.data.LastName);
                    $("#bookingPhone").text(calEvent.data.Mobile);
                    $("#bookingEmail").text(calEvent.data.Email);
                    $("#bookingCIRNumber").text(calEvent.data.CIRNumber);
                    $("#bookingIntLanguage").text(calEvent.data.IntLanguage);
                    $("#bookingIsSafe").text(calEvent.data.IsSafe);
                    $("#bookingDescription").text(calEvent.data.Description);
                    $("#delete-booking").attr('href', '/booking/delete/' + calEvent.data.BookingRef);
                    $(".edit-booking").attr('id', calEvent.data.ServiceId); //Change for real service id ServiceId
                    // change the border color just for fun
                    $(this).css('border-color', 'red');
                    confirmDialog();
                    $("#bookingInfo").modal();

                },
                eventAfterRender: function (event, element, view) {
                    var today = new Date();
                    if (event.start < today && event.end < today) {                     
                        element.css('background-color', '#77DD77');
                    }
                },
            });

        },

    };

}();

var confirmDialog = function() 
{
    $( "#delete-booking" ).on( "click", function(e) 
    {        
        var r = confirm("Are you sure that you want to delete it?\n To confirm press OK or Cancel.");
        if (r == true) 
        {
            //Continue to the event
        } else {
            e.preventDefault();       
        }
    });  
}
jQuery(document).ready(function() {    
   AppCalendar.init(); 
});