var currentEventInCalendar = [];
var EventsInCalendar = [];
var currentServiceProvider = 0;

var AppCalendar = function() {

    var getWsUrlParams = function()
    {
        var pathname = window.location.pathname;
        var params = {
                        url_calendar: '/booking/listCalendar',
                        url_update_calendar: '/booking/updateBooking'
                      };
        if( pathname === '/booking/by_service_provider' )
        {
            params.url_calendar = '/booking/listCalendarBySp';
        }

        return params;
    }

    var onChangeSp = function()
    {

        $('#service_provider_id').on('change', function()
        {
            currentServiceProvider = this.value;
            AppCalendar.init();
        });
    }();

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

            var urlPrams = getWsUrlParams();

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
                    url: urlPrams.url_calendar,
                    type: 'GET',
                    data: {
                        sp_id: currentServiceProvider
                    },
                    beforeSend: function () {
                        $("#contentLoading").modal();
                    },
                    error: function() {
                        alert('there was an error while fetching events!');
                        $("#contentLoading").modal('hide');
                    },
                    success: function (data) {
                        $("#contentLoading").modal('hide');
                        EventsInCalendar = data;
                    }
                }],
                eventClick: function(calEvent, jsEvent, view) {

                    //User info
                    if( calEvent.user )
                        $("#createdBy").text(calEvent.user.name);

                    //Booking info
                    $("#bookingRef").text(calEvent.data.BookingRef);
                    $("#bookingTitle").text(calEvent.data.ServiceName);
                    $("#bookingSPName").text(calEvent.data.ServiceProviderName);
                    $("#bookingTime").text(calEvent.data.BookingDate + " " + calEvent.data.BookingTime);

                    $("#bookingFirstName").text(calEvent.data.FirstName);
                    $("#bookingFirstName").removeClass('editable-empty');

                    $("#bookingLastName").text(calEvent.data.LastName);
                    $("#bookingLastName").removeClass('editable-empty');

                    if( calEvent.data.Mobile != '' )
                    {
                        $("#bookingPhone").text(calEvent.data.Mobile);
                        $("#bookingPhone").removeClass('editable-empty');
                    }
                    else
                    {
                        $("#bookingPhone").text('N/P');
                    }

                    if( calEvent.data.Email != '' )
                    {
                        $("#bookingEmail").text(calEvent.data.Email);
                        $("#bookingEmail").removeClass('editable-empty');
                    }
                    else
                    {
                        $("#bookingEmail").text('N/P');
                    }

                    if( calEvent.data.CIRNumber != '' )
                    {
                        $("#bookingCIRNumber").text(calEvent.data.CIRNumber);
                        $("#bookingCIRNumber").removeClass('editable-empty');
                    }
                    else
                    {
                        $("#bookingCIRNumber").text('N/P');
                    }

                    $("#bookingIntLanguage").text(calEvent.data.IntLanguage);

                    $("#bookingIsSafe").text(calEvent.data.IsSafe);

                    let IsComplex = (calEvent.data.IsComplex) ? 'Yes' : 'No';
                    let IsSafeSMS = ( calEvent.data.IsSafeSMS ) ? 'Yes' : 'No';
                    let IsSafeCall = ( calEvent.data.IsSafeCall ) ? 'Yes' : 'No';
                    let IsSafeLeaveMessage = ( calEvent.data.IsSafeLeaveMessage ) ? 'Yes' : 'No';

                    $("#IsComplex").text(IsComplex);
                    $("#IsSafeSMS").text(IsSafeSMS);
                    $("#IsSafeCall").text(IsSafeCall);
                    $("#IsSafeLeaveMessage").text(IsSafeLeaveMessage);
                    $("#ContactInstructions").text(calEvent.data.ContactInstructions);

                    if( calEvent.data.Description != '' )
                    {
                        $("#bookingDescription").html(calEvent.data.Description);
                        $("#bookingDescription").removeClass('editable-empty');
                    }
                    else
                    {
                        $("#bookingDescription").text('N/P');
                    }

                    $("#delete-booking").attr('href', '/booking/delete/' + calEvent.data.BookingRef);
                    $(".edit-booking").attr('id', calEvent.data.ServiceId); //Change for real service id ServiceId
                    // change the border color just for fun
                    $(this).css('border-color', 'red');
                    $("#bookingInfo").modal();


                    const booking_date = new Date(calEvent.data.BookingDate);
                    const booking_time = calEvent.data.BookingTime;

                    let day_before = new Date();
                    day_before.setDate(day_before.getDate()-1);

                    if ( booking_date > day_before && calEvent.data.Mobile != '' && calEvent.data.IsSafeSMS ) {
                        $('.sms-actions').show();
                    } else {
                        $('.sms-actions').hide();
                    }
                    currentEventInCalendar = calEvent.data;

                    var sentDates = calEvent.data.SMSSendDates.string;
                    var sentDatesStr = '';
                    if( typeof sentDates === 'string' ) {
                        sentDatesStr = sentDates.split(' ')[0];
                    } else {
                        for (let i = 0, len = sentDates.length; i < len; i++) {
                            sentDatesStr += sentDates[i].split(' ')[0] + ', ';
                        }
                    }

                    let booking_date_moment = moment(booking_date).toDate();
                    $("#" + calEvent.data.ServiceId).text(moment(booking_date_moment).format('DD/MM/YYYY') +
                        ' at ' + tConvert(booking_time) );

                    //sentStatus
                    $("#sentStatus").text(sentDatesStr.replace(/,\s*$/, ''));
                    if ( sentDatesStr === "")
                    {
                        $("#sentStatus").html("<span class='font-red'>Not sent</span>");
                    }

                    setStatus(calEvent.data.BookingStatusId);
                    setColorStatus(calEvent.data.BookingStatusId);

                    if( Object.keys(calEvent.data.BookingDocuments).length > 0)
                    {
                        let BookingRef = calEvent.data.BookingRef;
                        showBookingDocuments(calEvent.data.BookingDocuments, BookingRef);
                    }
                    else
                    {
                        $(".slimScrollDiv").css('height', '0px');
                        $(".scroller").css('height', '0px');
                    }
                },
                eventAfterRender: function (event, element, view) {
                    let today = new Date();
                    if (event.start < today && event.end < today) {
                        element.css('background-color', '#77DD77');
                    }
                },
                columnFormat: {
                    week: 'ddd D/M'
                },

                eventMouseover: function (data, event, view) {
                    const booking = data.data;
                    let tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;color:white;background:#17C4BB;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;">';

                    tooltip += booking.ServiceName + '<br>';
                    tooltip += booking.ServiceProviderName + '<br>';
                    tooltip += tConvert ( data.start._i.split(' ')[1] ) + ' - ';
                    tooltip += tConvert ( data.end._i.split(' ')[1] ) + '<br>';
                    tooltip += '</div>';

                    $("body").append(tooltip);
                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $('.tooltiptopicevent').fadeIn('500');
                        $('.tooltiptopicevent').fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $('.tooltiptopicevent').css('top', e.pageY + 10);
                        $('.tooltiptopicevent').css('left', e.pageX + 20);
                    });


                },
                eventMouseout: function (data, event, view) {
                    $(this).css('z-index', 8);

                    $('.tooltiptopicevent').remove();

                }
            });

            var showBookingDocuments = function (bookingDocuments, BookingRef)
            {

                $(".slimScrollDiv").css('height', 33 + 'px');
                $(".scroller").css('height', 33 + 'px');
                $('.feeds').html('');

                if( bookingDocuments.BookingDocument instanceof Array )
                {
                    var amount_docs = Object.keys(bookingDocuments.BookingDocument).length;
                    $(".slimScrollDiv").css('height', (parseInt(amount_docs) * 33) + 'px');
                    $(".scroller").css('height', (parseInt(amount_docs) * 33) + 'px');
                    for (var i = 0, len = amount_docs; i < len; i++)
                    {
                      var filePath = bookingDocuments.BookingDocument[i].Filepath;
                      showDocumentTemplate(filePath, BookingRef);
                    }
                }
                else
                {
                  var filePath = bookingDocuments.BookingDocument.Filepath;
                  showDocumentTemplate(filePath, BookingRef);
                }
            }

            var showDocumentTemplate = function (filePath, BookingRef)
            {
                var booking_doc =
                                  '\
                                    <li>\
                                        <a href="/booking_docs/' + BookingRef + '/' + filePath + '" download>\
                                            <div class="col1">\
                                                <div class="cont">\
                                                    <div class="cont-col2">\
                                                        <div class="desc">' + filePath + '</div>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                            <div class="col2">\
                                                <div class="date">  </div>\
                                            </div>\
                                        </a>\
                                    </li>\
                                  ';
                $('.feeds').append(booking_doc);
            }

            var setStatus = function (status_id)
            {
                $('.booking-status option[value="' + status_id + '"]').prop("selected", true);
            }

            var setColorStatus = function (status_id)
            {
                $('.booking-status').removeClass('bg-green-jungle bg-font-green-jungle');
                $('.booking-status').removeClass('bg-red bg-font-red');
                switch(status_id)
                {
                    case 2:
                        $('.booking-status').addClass('bg-green-jungle bg-font-green-jungle');
                        break;
                    case 3:
                        $('.booking-status').addClass('bg-red bg-font-red');
                        break;
                }

            }

            var onChangeStatus = function ()
            {
                $('.booking-status').on('change', function()
                {
                    setColorStatus( parseInt(this.value) );
                    currentEventInCalendar.BookingStatusId = this.value;
                    saveBooking( currentEventInCalendar );

                })
            }();

            var updateForm = function ()
            {
                $('#bookingFirstName').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.FirstName = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
                $('#bookingLastName').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.LastName = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
                $('#bookingPhone').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.Mobile = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
                $('#bookingEmail').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.Email = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
                $('#bookingCIRNumber').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.CIRNumber = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
                $('#bookingDescription').editable({
                    url: function(params)
                    {
                        currentEventInCalendar.Description = params.value;
                        saveBooking(currentEventInCalendar);
                    },
                    validate: function(value)
                    {
                        if($.trim(value) == '')
                        {
                            return 'This field is required';
                        }
                    }
                });
            }();

            var saveBooking = function ( booking ) {

                var csrf = document.getElementsByName("_token")[0].content;
                $("#contentLoading").modal("show");
                    $.ajax({
                        headers:
                        {
                            'X-CSRF-TOKEN': csrf
                        },
                        method: "POST",
                        url: urlPrams.url_update_calendar,
                        data:
                        {
                            booking: JSON.stringify(booking)
                        }
                    })
                    .done(function( msg )
                    {
                        $("#contentLoading").modal("hide");
                    });
            };
        },

    };

}();

var setDefaultValuesOnClick = function()
{
    let elements = ['#bookingDescription', '#bookingFirstName', '#bookingLastName', '#bookingPhone', '#bookingEmail','#bookingCIRNumber'];
    for (let i = elements.length - 1; i >= 0; i--)
    {
        let current_element = $(elements[i]);
        $( current_element ).on( "click", function(e)
        {
            current_element.editable('setValue', current_element.html());
        });
    }
}();

function deleteBookingDialog()
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

//Taken from https://stackoverflow.com/questions/13898423/javascript-convert-24-hour-time-of-day-string-to-12-hour-time-with-am-pm-and-no
function tConvert (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  return time.join (''); // return adjusted time or original string
}

var selectDefaultSP = function()
{
    var sp_id = $(".sp_id").attr('id');
    $('#service_provider_id option[value="'+ sp_id +'"]').prop("selected", true);
    $("#service_provider_id").trigger('change');
}

jQuery(document).ready(function() {
   $.when(AppCalendar.init()).then(selectDefaultSP());
   deleteBookingDialog();
});