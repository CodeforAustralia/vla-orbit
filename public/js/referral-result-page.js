var service_id = 0;

function isEmail(email) 
{
  let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

/** rewrite module */
var initReadmore = function()
{
  $('.description').readmore({
      collapsedHeight: 56,
      speed: 1000,    
      lessLink: '<a href="#">Read less</a>'
  });
}();

var showCard = function (element_class, show) 
{
  if( show )
  {   
    $("." + element_class ).fadeIn("slow");
  } else 
  {
    $("." + element_class ).fadeOut("slow");
  } 
}

var filterElements = function (checked_values, filter_arr) {
    
  if( checked_values )
  {
    let off_values = filter_arr.filter( function(x) { return checked_values.indexOf(x) === -1 }); // Substract checked values from filters                    
    off_values.map( function(current_value) { return showCard(current_value, false) }); // Hide substracted values
    checked_values.map( function(current_value) { return showCard(current_value, true) });  // Show checked elements
  }
  else 
  {
    filter_arr.map( function(current_value) { return showCard(current_value, true) }); // Show all elements if checked values is null
  }
}

var setFilterOnElement = function (element, nonSelectedText,filter) {
  
    element.multiselect({
                    nonSelectedText: nonSelectedText,
                    buttonWidth: '175px',
                    onChange: function(option, checked) 
                    {                      
                      filterElements( element.val(), filter); //Selected values and filter
                    },
                    includeSelectAllOption: true,
                        onSelectAll: function() 
                        {
                          filterElements( null, filter); // Show elements if checked values is null
                      }
                  });
};

var initFilters = function () 
{
  const filter_level   = ['information', 'advice', 'representation'];
  const filter_type    = ['phone-line', 'phone-appointments', 'appointment', 'duty-lawyer', 'outreach', 'drop-in', 'workshop'];
  const filter_sp_type = ['non-legal-provider', 'clc', 'vla', 'legal-help', 'private-practitioners', 'panel-lawyer'];
  let filter_group   = [];

  filter_group = filter_type.concat(filter_level).concat(filter_sp_type);

  setFilterOnElement( $('#filter-group'), 'Filter By:', filter_group );

}();

var openBooking = function () 
{  
  $('.open-booking').on( "click", function()
  {
    $("#contentLoading").modal("show");
    const service_card = $( this ).closest(".card-container");
    const sv_id = $( service_card ).attr("id");
    const booking_ids = $( this ).attr("id").split('-');
    const sp_id = booking_ids[2];
    const booking_id = booking_ids[0];
    const booking_interpretor_id = booking_ids[1];    
    
    $('#service_provider_id').attr("disabled", "disabled");
    $('#service_provider_id option[value="' + sp_id + '"]').prop("selected", "selected").change();
    $('#sp_services').attr("disabled", "disabled");
    $('#request_type').attr("disabled", "disabled");
    $('#booking-date').attr("required");

    $(".booking-area").addClass("hidden");      
    setTimeout(function(){      
        $('#sp_services option[value="' + booking_id + '-' + booking_interpretor_id + '-' + sv_id + '"]').prop("selected", "selected").change();
        $(".booking-area").hide().removeClass("hidden").fadeIn();
        $("#contentLoading").modal("hide");
    }, 2500);
  });
}();

var openModal = function () 
{
  $('.open-modal').on( "click", function(){    
    const service_card = $( this ).closest(".card-container");
    const service_provider_name = $(service_card).find(".service-provider-name").text();
    const service_name = $(service_card).find(".service-name").text();
    const image_path = $(service_card).find("img").attr("src");
    service_id = $( service_card ).attr('id');

    const modal = $("#SelectMatch");
    $(modal).find(".service-provider-name").text(service_provider_name);
    $(modal).find(".service-name").text(service_name);
    $(modal).find("img").attr("src", image_path);

    if(service_card.hasClass('panel-lawyer'))
    {
      $('.map-box').show();
    }
    else
    {
      $('.map-box').hide();
    }

  });  
}();

var closeModal = function () 
{  
  $( "#close-modal, .close" ).on( "click", function() {
    $("#result-step-1").show();
    $("#result-step-2").hide();
    $("#service_provider_id option").prop("selected", false);
    $("#SelectMatchLabel").text("Send Referral");
    $('#map').hide();
    $('#nearest').val('');
  });
}();

var sendToClient = function () {
  
  $('#send-client').on( "click", function(){
    const phone = $("#Phone").val();
    const email = $("#Email").val();
    const OutboundServiceProviderId = $("#OutboundServiceProviderId").val();
    const UserID = $("#UserID").val();
    const MatterId = $("#MatterId").val();
    const Notes = $("#Notes option:selected").text();
    const CatchmentId = $("#CatchmentId").val();
    const nearest = $("#nearest").val();
    let safe_phone = 0;
    let safe_to_email = 0;

    if( $("#safeEmail").is(':checked') )
    {
      safe_email = 1;
    } 
    else {
      safe_email = 0;
    }

    if( $("#safePhone").is(':checked') )
    {
      safe_phone = 1;
    }
    else {
      safe_phone = 0;
    }

    if( $('.map-box').is(":visible") && !$('#map').is(":visible") )
    {
      swal("Alert", "Please provide an address and get nearest panel lawyers", "warning");
    }
    else if( safe_phone == 0 && safe_email == 0 ) // Not safe to contact
    {
      swal("Alert", "SMS or email can’t be send if it’s not safe to contact client. Please tick box if it is safe", "warning");
    } 
    else if( safe_email == 1 && email == '' ) //Empty valid email
    {
      swal("Alert", "Please provide a valid email", "warning");
    }
    else if( safe_email == 1 && email != '' && !isEmail( email ) ) //Not valid email
    {
      swal("Alert", "Please provide a valid email", "warning");
    }
    else if( safe_phone == 0 && phone != '' ) // Not safe phone
    { 
      swal("Alert", "Please enter a mobile number", "warning");
    }
    else if( safe_phone == 1 && phone == ''  ) // Empty Phone
    { 
      swal("Alert", "Please enter a mobile number", "warning");
    }
    else if( ( isEmail( email ) && safe_email == 1 ) || ( phone != '' && safe_phone ) )
    {
      $("#contentLoading").modal("show");
      const csrf = $('meta[name=_token]').attr('content');
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': csrf
        },
        method: "POST",
        url: "/referral",
        data: { 
                Mobile: phone, 
                Email: email,
                SafeMobile: safe_phone,
                SafeEmail: safe_email,
                ServiceId: service_id,
                CatchmentId: CatchmentId,
                MatterId: MatterId,
                UserID: UserID,
                OutboundServiceProviderId: OutboundServiceProviderId,
                Nearest: nearest
              }
      })
        .done(function( msg ) {          
          $("#referral_id").html(msg.data);
          $("#SelectMatchLabel").text("Referral Sent");
          $("#result-step-1").hide();
          $("#result-step-2").show();
          $("#contentLoading").modal("hide");
          disableReferralButton(service_id);
          $("#to").val(email);
        });
    } 
    else {
      swal( "Alert", "Please provide an Email and/or a mobile number.", "warning" );
    }

  });
}();

var disableReferralButton =  function( id )
{
  $("#" + id + " .refer-button").removeClass('bg-green-jungle bg-font-green-jungle');
  $("#" + id + " .refer-button").addClass('bg-grey-silver bg-font-grey-silver');
  $("#" + id + " .refer-button button").removeClass('bg-green-jungle bg-font-green-jungle');
  $("#" + id + " .refer-button button").addClass('bg-grey-silver bg-font-grey-silver');  
  $("#" + id + " .refer-button button").text('Referral Sent');
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