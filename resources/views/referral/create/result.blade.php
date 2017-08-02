@extends ('orbit.master')

@section ('content')


  <!-- BEGIN PAGE HEADER-->
  <!-- Steps -->
  <div class="mt-element-step margin-bottom-20">
    <div class="row step-line">
      <div class="col-xs-2 col-xs-offset-1 mt-step-col first done" style="cursor: pointer;" onclick="window.location='/referral/create/location';">
        <div class="mt-step-number bg-white">
          <i class="fa fa-map-marker"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Location</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </div>
      <div class="col-xs-2 mt-step-col done">
        <div class="mt-step-number bg-white">
          <i class="fa fa-legal"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Legal Issue</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </div>
      <div class="col-xs-2 mt-step-col done">
        <div class="mt-step-number bg-white">
          <i class="fa fa-check-square-o"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Eligibility</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </div>
      <div class="col-xs-2 mt-step-col done">
        <div class="mt-step-number bg-white">
          <i class="fa fa-question"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Questions</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </div>
      <div class="col-xs-2 mt-step-col active last">
        <div class="mt-step-number bg-white">
          <i class="fa fa-list"></i>
        </div>
        <div class="mt-step-title font-grey-cascade">Matches ({{ count($matches) }})</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </div>
    </div>
  </div>

    <!-- END PAGE HEADER-->

  <!-- Result 1 -->
  <div class="row">
    <div class="col-xs-12">
      @include ('referral.create.multiple-results')
    </div> <!-- Col Close -->
  </div> <!-- Row Close -->
    @include ('referral.create.booking')
  <!-- Modal Start -->     
  <div class="modal fade" id="SelectMatch" tabindex="-1" role="dialog" aria-labelledby="SelectMatchLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="SelectMatchLabel">Send Referral</h4>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <div class="container-fluid">
          <!-- Top -->
            <div id="result-step-1">
              <div class="row">
                <!-- Logo -->
                <div class="col-xs-6 col-sm-4">
                  <img src="#" class="img-responsive img-thumbnail center-block">
                </div>
                <!-- Service & SP -->
                <div class="col-xs-6 col-sm-8">
                  <h3 class="service-name"><strong>Homeless Law Service</strong></h3>
                  <h4 class="service-provider-name">Justice Connect</h4>
                  <p>Send referral details to the client by Email, SMS or both with the form below.</p>
                </div>
              </div>

              <!-- Start Form --> 
              <div class="row">
                <div class="col-xs-12">
                  
                  <!-- Send to Client form -->
                  <form>
                    <!-- Client Name -->
                    <div class="form-group">
                    <!-- Email Address -->
                    <div class="form-group">
                      <label>
                        <input type="checkbox" id="safeEmail"> It is safe to contact client by email
                      </label> 
                      <label class="sr-only" for="Email">Email Address</label>
                      <input type="email" class="form-control" id="Email" placeholder="Client Email Address">                        
                      <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                          <div class="checkbox">
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Phone Number -->
                    <div class="form-group">
                      <label>
                        <input type="checkbox" id="safePhone"> It is safe to contact client by SMS
                      </label>
                      <label class="sr-only" for="Phone">Phone Number</label>
                      <input type="tel" class="form-control" id="Phone" placeholder="Client Phone Number">                            
                      <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                          <div class="checkbox">
                          </div>  
                        </div>
                      </div>
                    </div>
                    <!-- Button -->
                    <div class="col-xs-6 col-xs-offset-3"><br>                        
                        <!-- Trigger Modal -->
                        <button type="button" class="btn green-jungle btn-block btn-lg pull-right" id="send-client">Send to Client</button><br><!-- Trigger Modal -->
                    </div><!-- End Form -->
                  </form>
                </div>
              </div>
            </div>
          </div> 
          
          <!-- Modal Container -->
            
            <div class="row" id="result-step-2" style="display:none">
              <div class="col-xs-12 text-center">
                <p style="font-size: 126px;"><i class="fa fa-check-circle" style="color: #5cb85c;background-color: #fff;"></i></p>
                <h3><strong>Referral sent to client</strong></h3>
                <h3><strong>ID: #</strong><span id="referral_id"></span></h3><br>
                <button type="button" class="btn default btn-outline btn-lg" data-toggle="modal" data-target="#SelectMatch" id="close-modal"><span>Return to Matches</span></button>
                <button type="button" class="btn green-jungle btn-lg" onClick="window.location='/referral';">Done</button><br><br><br><br>
              </div>
            </div>
        </div> <!-- Modal Body Close-->
      </div><!-- Modal Content Close-->
    </div><!-- Modal Dialogue Close-->
  </div><!-- Modal Fade Close-->
@endsection

@section('scripts')
@endsection

@section('inline-scripts')
  var service_id = 0;
  $(document).ready(function() {

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
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          method: "POST",
          url: "/referral",
          data: { 
                  Mobile: phone, 
                  Email: email,
                  SafeMobile: safe_phone,
                  SafeEmail: safe_email,
                  ServiceId: service_id
                }
        })
          .done(function( msg ) {
            console.log( msg );
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
  });  

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
@endsection
