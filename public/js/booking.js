var services = [];
var services_by_sp_obj = [];
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

function check_if_can_book( sv_id )
{
    for (let i = services_by_sp_obj.length - 1; i >= 0; i--) 
    {        
        if( services_by_sp_obj[i].ServiceId === sv_id )
        {
            let serviceActions = services_by_sp_obj[i].ServiceActions;
            for (let j = serviceActions.length - 1; j >= 0; j--) 
            {                
                if(serviceActions[j].Action === 'ALL' || serviceActions[j].Action === 'BOOK' )
                {
                    return true;
                }
            }
        }
    }
    return false;
}

function service_change()
{
    $("#sp_services, #Language, #IsComplex").on("change",function() {
        let booking_id = $( "#sp_services" ).val().split('-')[0];
        let booking_interpreter_id = $( "#sp_services" ).val().split('-')[1];
        
        if( booking_id !== '' || booking_interpreter_id !== '' ) //Direct booking is available
        {
            $("#direct_booking").show();
            document.getElementById("request_type").selectedIndex = 0;
            $("#direct_booking").trigger('change');

            //Clean times options
            $("#time-options").html('');
            $("#booking-date").val('');

            if( requireInterpreterOrComplex() ) // Requires interpreter or is complex matter
            {                
                getBookingsByService( booking_interpreter_id );
            } 
            else // Do not requires interpreter
            {                
                getBookingsByService(booking_id); 
            }
        }
        else // Alternative bookings
        {            
            document.getElementById("request_type").selectedIndex = 1;
            $("#direct_booking").trigger('change');

            $("#direct_booking").hide();
            $(".availability").addClass("hidden");
        }

        $("#ServiceName").val( $("#sp_services option:selected").text() );
        $("#ServiceProviderName").val( $("#service_provider_id option:selected").text() );
            
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
                        showTimes(day, month, year, current_service);     
                    }
                })
                .on('changeMonth', function(e){
                    var booking_id = $( "#sp_services" ).val().split('-')[0];
                    var booking_interpreter_id = $( "#sp_services" ).val().split('-')[1];
                    var current_year = String(e.date).split(" ")[3];
                    var current_month = new Date(e.date).getMonth() + 1;
                    
                    $("#booking-date").val('')
                    if( requireInterpreterOrComplex() ) // Requires interpreter or is complex matter
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
    if( $( "#sp_services" ).val() != "")
    {
        $("#no-dates-availables").hide().removeClass("hidden").fadeIn();    
    }
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

function getServiceByBookingId( services, booking_id )
{
    for (index = 0; index < services.length; ++index) {
        if( booking_id == services[index].BookingServiceId || booking_id == services[index].BookingInterpritterServiceId )
        {
            return { length: services[index].BookingServiceLength, lengthInt: services[index].BookingInternalServiceLength };
        }
    }
    return { length: 0, lengthInt: 0 };
}

function showTimes(day, month, year, current_service)
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

    var durations_by_service = getServiceByBookingId( services_by_sp_obj, current_service );
    var duration_slot = durations_by_service.length;
    
    if( requireInterpreterOrComplex() ) // Requires interpreter or is complex matter
    {
        var duration_slot = durations_by_service.lengthInt;
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
        services_by_sp_obj = services_by_sp;        
        $("#sp_services").html('<option> </option>');
        let user_sp_id = $(".sp_id").attr('id');

        for (index = 0; index < services_by_sp.length; ++index) {
            let service_id = services_by_sp[index].ServiceId;
            let booking_id = services_by_sp[index].BookingServiceId;
            let booking_interpreter_id = services_by_sp[index].BookingInterpritterServiceId;
            let service_name = services_by_sp[index].ServiceName;
            let is_user_sp = sp_id === user_sp_id ;

            let option = '';
            //If the service is linked to booking bug and the user do not belong to legal help and can book OR is the same users office
            if( user_sp_id != 112 && (booking_id != '' || booking_interpreter_id != '') 
                    && ( check_if_can_book( service_id ) || is_user_sp ) )
            {
                option = '<option value="' + booking_id + '-' + booking_interpreter_id + '-' + service_id + '"> ' + service_name + ' </option>';            
            }
            else if( user_sp_id == 112 )
            {
                if( check_if_can_book( service_id ))
                {
                    option = '<option value="' + booking_id + '-' + booking_interpreter_id + '-' + service_id + '"> ' + service_name + ' </option>';            
                } 
                else
                {
                    option = '<option value="--' + service_id + '"> ' + service_name + ' </option>';            
                }
            }
            $("#sp_services").append(option);
        }   

        if( $("#sp_services option")[0] )
        {
            let booking_id = $( $("#sp_services option")[0] ).val();
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

var enableSummernote = function () 
{  
  $('#Desc').summernote({
      toolbar: [
          // [groupName, [list of button]]
          ['style', ['bold', 'italic', 'underline']],
          ['para', ['ul', 'ol', 'paragraph']],          
          ['link', ['linkDialogShow', 'unlink']]          
      ]
  });
}();

var changeRequestType = function()
{
    $("#request_type").on("change", function() 
    {
        var template = '';
        hide_direct_booking_elements();            
        setSubmitButtonText('is_request');
        if( this.value === 'appointment_request' )
        {            
            template += 'This call was supervised by (if relevant):  <br><br>';            
        }
        else if ( this.value === 'for_assessment' ) 
        {
            template += 'Brief outline of matter: <br> <br>';            
            template += 'Notes (special needs, urgency, time limits, tribunal/court hearing dates and location if the caller is in custody/detention):<br><br>';
            template += 'This call was supervised by (if relevant):  <br><br>';            
        }  
        else if ( this.value === 'phone_advice' ) 
        {
            template += 'Client ID (if known): <br> <br>';            
            template += 'Brief outline of matter: <br><br><br>';
            template += 'Notes (special needs, urgency, time limits, tribunal/court hearing dates and location if the caller is in custody/detention):<br><br>';
            template += 'This call was supervised by (if relevant):  <br><br>';            
        }     
        else if ( this.value === 'duty_layer' ) 
        {
            template += 'Brief outline of matter: <br><br>';
            template += 'Court Date: <br><br>';
            template += 'This call was supervised by (if relevant):  <br><br>';    
        }    
        else if ( this.value === 'child_support' ) 
        {
            template += 'Brief outline of matter: <br><br>';
            template += 'Before completing this booking, ensure conflicts enquiry is completed. Please list names and DOB of other parties (including children): <br><br>';
            template += 'This call was supervised by (if relevant):  <br><br>';    
        } 
        else if ( this.value === 'child_protection' ) 
        {
            template += 'Urgent: Y/N <br><br><br>';
            template += '<strong>Conflict Check All Parties</strong> <br><br>';
            template += 'Mother: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
            template += 'Mother\'s spouse/ domestic partner: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
            template += 'Father: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
            template += 'Father\'s spouse/domestic partner: DOB,  ATSI Y/N, Conflict Y/N <br><br>';
            template += 'Children (inc step): DOB,  ATSI Y/N, Conflict Y/N <br><br><hr>';    
            template += 'Upcoming Court date: Y/N <br><br>';
            template += 'Court location: <br><br>';
            template += 'Are there orders in place? Y/N Details <br><br>';
            template += 'Date orders made? <br><br>';
            template += 'Upcoming appointment with DHHS?  Y/N  When? <br><br>';
            template += 'Has the client signed an agreement with DHHS in relation to the child? Y/N Details <br><br>';
            template += 'Are they asking the client to sign something? Details <br><br>';
            template += 'Has DHHS indicated they are thing of removing the child from the client\'s care or change the living arrangements? Details <br><br>';
            template += 'What has prompted the call to VLA today? Details <br><br>';
        } 
        else if ( this.value === 'direct_booking' ) 
        {
            show_direct_booking_elements();
            setSubmitButtonText( 'direct' );
        }

        displayFieldsByForm(this.value);
        $('#Desc').summernote('code', template);
    });
}();

var hide_direct_booking_elements = function()
{
    $('.attached-files').hide();
    $('.booking-area').hide();
};

var show_direct_booking_elements = function()
{
    $('.attached-files').show();
    $('.booking-area').show();
};

var form_validate = function()
{
    $("#bookingForm").validate({       
       
        ignore:":not(:visible)",

        submitHandler: function(form, event) 
        {
          event.preventDefault();
          removeDisabledAttribute();
          
          form.submit();
        }
    });
}();


var removeDisabledAttribute = function ()
{
    $(':disabled').each(function(event) 
    {
        $(this).removeAttr('disabled');
    });

}

var setSubmitButtonText = function ( type )
{
    let message = "Make booking";
    if( type === 'is_request' )
    {
        message = "Send e-Referral";
    }
    $('#submit-booking').text(message);
}

var requireInterpreterOrComplex = function()
{
    return ( $("#Language" ).val() != '' || $("#IsComplex:checked").val() == 1 ) ;
}

var setDOBMask = function () {
    $("#dob").inputmask("d/m/y", {
        "placeholder": "dd/mm/yyyy"
    }); 
}();

var hideFields = function () 
{
    const fields = getFields();
    hideExtraFormFields(fields);
}();

function getFormTypes() 
{
    return  {
                appointment_request: { fields: [] },
                child_protection:    { fields: [] },
                child_support:       { fields: ['child_support'] },
                direct_booking:      { fields: ['direct_booking'] },
                duty_layer:          { fields: ['direct_booking'] },
                for_assessment:      { fields: ['for_assessment'] },
                phone_advice:        { fields: ['phone_advice'] }
            };
}

function getFields() 
{
    return {        
                basic: [
                            'CIRNumber',
                            'FirstName',
                            'LastName',
                            'mobile',
                            'phonepermission',
                            'phoneCallPermission',
                            'phoneMessagePermission',
                            'reContact'
                        ],
                for_assessment: ['suburb', 'dob'],        
                direct_booking: ['postal_address', 'email', 'emailpermission'],
                child_support:  ['postal_address', 'email', 'emailpermission']                
            };
}

var displayFieldsByForm = function (current_form) 
{
    const form_type = getFormTypes();
    const fields = getFields();
    hideExtraFormFields(fields);
 
    const form_fields =  form_type[current_form].fields;
    
    for (let i = form_fields.length - 1; i >= 0; i--) 
    {
        const fields_in_group = form_fields[i];
        if( fields.hasOwnProperty(fields_in_group) )
            for (let j = fields[fields_in_group].length - 1; j >= 0; j--) 
                $('#' + fields[fields_in_group][j]).closest('.row').show();
    }
}

function hideExtraFormFields(fields) 
{
    Object.keys(fields).map(function (item) 
    {         
        const fields_in_group = fields[item] ;
        if( item !== 'basic')                   
            for (let i = fields_in_group.length - 1; i >= 0; i--) 
                $('#' + fields_in_group[i]).closest('.row').hide();        
    });
}