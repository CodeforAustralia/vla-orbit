@extends ('orbit.master')

@section ('content')
<!-- SC1 -->
<!-- Result 1 -->
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
    @if( sizeof($matches) > 0 )
          @include ('referral.create.multiple-results')
    @else
          @include ('referral.create.no-results')

    @endif
  </div> <!-- Col Close -->
</div> <!-- Row Close -->

  <!-- Modal Start -->     
  <div class="modal fade" id="SelectMatch" tabindex="-1" role="dialog" aria-labelledby="SelectMatchLabel">
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
                  <img src="https://pbs.twimg.com/profile_images/583201253980643328/NfKqUKrP.jpg" class="img-responsive img-thumbnail center-block">
                </div>
                <!-- Service & SP -->
                <div class="col-xs-6 col-sm-8">
                  <h3><strong>Homeless Law Service</strong></h3>
                  <h4>Justice Connect</h4>
                  <p>Send the client the contact details of this service and a record of this Orbit referral by Email, SMS or both with the form below.</p>
                </div>
              </div>

              <!-- Start Form --> 
              <div class="row">
                <div class="col-xs-12">
                  <h3><small>Send to client by email:</small></h3>
                  <!-- Send to Client form -->
                  <form>
                    <!-- Client Name -->
                    <div class="form-group">
                    <!-- Email Address -->
                    <div class="form-group">
                      <label class="sr-only" for="Email">Email Address</label>
                      <input type="email" class="form-control" id="Email" placeholder="Client Email Address">                        
                      <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> This email address is safe to contact                                  
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <h3><small>Send to client by SMS:</small></h3>
                    <!-- Phone Number -->
                    <div class="form-group">
                      <label class="sr-only" for="Phone">Phone Number</label>
                      <input type="tel" class="form-control" id="Phone" placeholder="Client Phone Number">                            
                      <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> This phone number is safe to text                          
                            </label>
                          </div>  
                        </div>
                      </div>
                    </div>
                    <!-- Button -->
                    <div class="col-xs-6 col-xs-offset-3"><br>
                        <!--<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#SelectMatch">Send to Client</button><br><!-- Trigger Modal -->
                        <button type="button" class="btn green-jungle btn-block btn-lg pull-right" id="send-client">Send to Client</button><br><!-- Trigger Modal -->
                    </div><!-- End Form -->
                  </form>
                </div>
              </div>
            </div>
          </div> <!-- Modal Container -->
            
            <div class="row" id="result-step-2" style="display:none">
              <div class="col-xs-12 text-center">
                <p style="font-size: 126px;"><i class="fa fa-check-circle" style="color: #5cb85c;background-color: #fff;"></i></p>
                <h3><strong>Referral Sent to Client</strong></h3>
                <h3><strong>ID:</strong> #43102</h3><br>
                <button type="button" class="btn default btn-outline btn-lg" data-toggle="modal" data-target="#SelectMatch" id="close-modal"><span>View Results</span></button>
                <button type="button" class="btn green-jungle btn-lg" onClick="window.location='/referral';">Return to Dashboard</button><br><br><br><br>
              </div>
            </div>
        </div> <!-- Modal Body Close-->
      </div><!-- Modal Content Close-->
    </div><!-- Modal Dialogue Close-->
  </div><!-- Modal Fade Close-->
@endsection