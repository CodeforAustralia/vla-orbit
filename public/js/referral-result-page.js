var service_id = 0;
$(document).ready(function() {

  filters();

  $('.open-booking').on( "click", function(){

    var service_card = $( this ).closest(".service-card");
    var sv_id = $( service_card ).attr("id");
    var booking_ids = $( this ).attr("id").split('-');
    var sp_id = booking_ids[2];
    var booking_id = booking_ids[0];
    var booking_interpretor_id = booking_ids[1];
    
    $('#service_provider_id').attr("disabled", "disabled");
    $('#service_provider_id option[value="' + sp_id + '"]').prop("selected", "selected").change();
    $('#sp_services').attr("disabled", "disabled");

    $(".booking-area").addClass("hidden");      
    setTimeout(function(){
        $('#sp_services option[value="' + booking_id + '-' + booking_interpretor_id + '"]').prop("selected", "selected").change();
        $(".booking-area").hide().removeClass("hidden").fadeIn();
    }, 2500);
  });

  $('.open-modal').on( "click", function(){    
    var service_card = $( this ).closest(".service-card");
    var service_provider_name = $(service_card).find(".service-provider-name").text();
    var service_name = $(service_card).find(".service-name").text();
    var image_path = $(service_card).find("img").attr("src");
    service_id = $( service_card ).attr('id');

    var modal = $("#SelectMatch");
    $(modal).find(".service-provider-name").text(service_provider_name);
    $(modal).find(".service-name").text(service_name);
    $(modal).find("img").attr("src", image_path);
  });


  $( "#close-modal, .close" ).on( "click", function() {
    $("#result-step-1").show();
    $("#result-step-2").hide();
    $("#service_provider_id option").prop("selected", false);
    $("#SelectMatchLabel").text("Send Referral");
  });

  $('#send-client').on( "click", function(){
    var phone = $("#Phone").val();
    var email = $("#Email").val();
    var OutboundServiceProviderId = $("#OutboundServiceProviderId").val();
    var UserID = $("#UserID").val();
    var MatterId = $("#MatterId").val();
    var Notes = $("#Notes option:selected").text();
    var CatchmentId = $("#CatchmentId").val();
    var safe_phone = 0;
    var safe_to_email = 0;
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

    if( safe_phone == 0 && safe_email == 0 ) // Not safe to contact
    {
      swal("Alert", "Please provide a safe contact information", "warning");
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
      var csrf = $('meta[name=_token]').attr('content');
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
                Notes: Notes
              }
      })
        .done(function( msg ) {          
          $("#referral_id").html(msg.data);
          $("#SelectMatchLabel").text("Referral Sent");
          $("#result-step-1").hide();
          $("#result-step-2").show();
        });
    } 
    else {
      swal( "Alert", "Please provide an Email and/or a mobile number.", "warning" );
    }

  });

  $('form').submit(function(e) {
      $(':disabled').each(function(e) {
          $(this).removeAttr('disabled');
      })
  });

  $('#Desc').summernote({
      toolbar: [
          // [groupName, [list of button]]
          ['style', ['bold', 'italic', 'underline']],
          ['para', ['ul', 'ol', 'paragraph']],          
          ['link', ['linkDialogShow', 'unlink']]          
      ]
  });

});  

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function filters()
{
  console.log('in');
  var filter_level = ['all-level', 'phone-line', 'phone-appointments', 'appointment', 'duty-lawyer', 'outreach', 'drop-in', 'workshop']


  var filter_type = ['all-type', 'information', 'advice', 'representation']


  $('.filter-type a').on( "click", function(){
    for (index = 0; index < filter_type.length; ++index) {
      
      if( this.className == 'all-type' )
      {   
        $(".portlet." + filter_type[index]).show();
      } 
      else if( this.className != filter_type[index] )
      {        
        $(".portlet." + filter_type[index]).hide();
      }

    }
    $(".portlet." + this.className ).show();
  });


  $('.filter-level a').on( "click", function(){
    
    for (index = 0; index < filter_level.length; ++index) {
      
      if( this.className == 'all-level' )
      {   
        $(".portlet." + filter_level[index]).show();
      } 
      else if( this.className != filter_level[index] )
      {        
        $(".portlet." + filter_level[index]).hide();
      } 
    }
    $(".portlet." + this.className ).show();
  });
}