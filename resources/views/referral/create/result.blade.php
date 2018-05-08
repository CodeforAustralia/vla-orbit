@extends ('orbit.master')

@section ('content')

  <!-- Steps -->
  <div class="mt-element-step margin-bottom-0">
    <div class="row step-line">
      <a class="col-xs-3 col-xs-offset-1 mt-step-col first done" style="text-decoration: none;" href="/referral/create/location/{{ '?ca_id=' . session('ca_id') . '&mt_id=' . session('mt_id') }}">
        <div class="mt-step-number bg-white">
          <i class="fa fa-search"></i>
        </div>
        <div class="mt-step-title font-grey-cascade hidden-xs">Search</div>
        <div class="mt-step-content"></div>
      </a>
      <a class="col-xs-3 mt-step-col done" style="text-decoration: none;" href="/referral/create/details/{{ '?ca_id=' . session('ca_id') . '&mt_id=' .  session('mt_id')  }}">
        <div class="mt-step-number bg-white">
          <i class="fa fa-check-square-o"></i>
        </div>
        <div class="mt-step-title font-grey-cascade hidden-xs">Refine</div>
        <div class="mt-step-content font-grey-cascade"></div>
      </a>
      <div class="col-xs-3 mt-step-col active last">
        <div class="mt-step-number bg-white">
          <i class="fa fa-list"></i>
        </div>
        <div class="mt-step-title font-grey-cascade hidden-xs">Matches ({{ count($matches) }})</div>
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
                  <h3 class="service-name" id='serviceName'><strong>Homeless Law Service</strong></h3>
                  <h4 class="service-provider-name">Justice Connect</h4>
                  <p>Send referral details to the client by Email, SMS or both with the form below.</p>
                </div>
              </div>

              <!-- Start Form --> 
              <div class="row">
                <div class="col-xs-12">
                  
                  <!-- Send to Client form -->
                  <form autocomplete="off">
                    <!-- Email Address -->
                    <div class="form-group margin-bottom-10 map-box collapse">
                      <div class="col-xs-12 padding-0 ">
                        <p class=" margin-top-10 margin-bottom-10">Add client's address to share contact details of 5 nearest panel lawyers.</p>
                      </div>
                      <div class="col-xs-9 padding-0">
                        <input type="text" name="address" id="address" class="form-control">
                      </div>
                      <div class="col-xs-3 padding-0 margin-bottom-10">
                        <button onclick="getAddress()" class="form-control padding-0 btn grey" type="button">Update Map</button>
                      </div>
                      <div class="col-xs-12 padding-0 margin-bottom-10">
                        <div id="map"></div>                    
                        <input type="text" name="nearest" id="nearest" class="hidden">
                      </div>
                    </div>
                    <div class="form-group">
                      <label>
                        <input type="checkbox" id="safeEmail"> It is safe to send an email to this address                        
                        <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Please be mindful of any family violence risks; eg if this email account is accessible by others."></i>
                      </label> 
                      <label class="sr-only" for="Email">Email Address</label>
                      <input type="email" class="form-control" id="Email" placeholder="Client email address">                        
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
                        <input type="checkbox" id="safePhone"> It is safe to send an SMS to this number
                        <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Please be mindful of any family violence risks; eg if the SMS or an SMS notification can be viewed by others."></i>
                      </label>
                      <label class="sr-only" for="Phone">Phone Number</label>
                      <input type="number" class="form-control" id="Phone" placeholder="Client mobile number">

                      <div class="col-xs-11 col-xs-offset-1">
                        <div class="form-group">
                          <div class="checkbox">
                          </div>  
                        </div>
                      </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                      <label for="Notes">Referral reason <small>(information is <strong><u>not</u></strong> shared with client)</small></label>                      
                      <select class="form-control" id="Notes" name="Notes">                                
                                <option></option>
                                <option>Not eligible - Already has solicitor</option>
                                <option>Not eligible - Catchment</option>
                                <option>Not eligible - Client circumstances</option>
                                <option>Not eligible - Conflict of interest</option>
                                <option>Not eligible - Income/means</option>
                                <option>Not eligible - Insufficient merit/impact</option>
                                <option>Not eligible - Legal matter</option>
                                <option>Internal referral</option>
                                <option>No capacity in-house</option>
                                <option>Non-legal referral</option>
                                <option>Specialist service available</option>
                                <option>Wanted guaranteed court representation</option>
                                <option>Other</option>
                        </select>
                    </div>
                    
                    <!-- Extra -->
                    <div class="form-group">                      
                      <input type="text" class="form-control hidden" id="CatchmentId" name="CatchmentId" value="{{ session('ca_id') }}">
                      <input type="text" class="form-control hidden" id="MatterId" name="MatterId" value="{{ session('mt_id') }}">
                      <input type="text" class="form-control hidden" id="UserID" name="UserID" value="{{ Auth::user()->id }}">
                      <input type="text" class="form-control hidden" id="OutboundServiceProviderId" name="OutboundServiceProviderId" value="{{ Auth::user()->sp_id }}">
                    </div>

                    <!-- Button -->
                    <div class="col-xs-8 col-sm-6 col-xs-offset-2 col-sm-offset-3"><br>                        
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
                <button type="button" class="btn default blue btn-lg" data-toggle="modal" data-target="#SelectMatch" id="close-modal"><span>Return to Matches</span></button>
                <button type="button" class="btn green-jungle btn-lg" onClick="window.location='/referral/create/location';">Done</button><br><br><br><br>
              </div>
            </div>
        </div> <!-- Modal Body Close-->
      </div><!-- Modal Content Close-->
    </div><!-- Modal Dialogue Close-->
  </div><!-- Modal Fade Close-->


  <!-- NRE Modal Start -->    

<!-- Confirm Eligibility Modal -->                                        
    <div class="modal fade in bs-modal-lg" id="sendNRE" role="dialog" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">                  
                  <h4 class="modal-title">Send Info Email</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                  
              </div>
              <div class="modal-body">
                  <div class="container-fluid">
                      @includeIf ('no_reply_emails.form')
                  </div>
              </div>
          </div>
      </div>
    </div>

<!-- Modal Fade Close-->
@endsection

@section('scripts-extra')
    <script src="/assets/global/plugins/readmore/readmore.min.js" type="text/javascript"></script>
    <script src="/js/referral-result-page.js?id={{ str_random(6) }}" type="text/javascript"></script>


    <script src="/js/no-reply-emails.js?id={{ str_random(6) }}" type="text/javascript"></script>    
    <script src="/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>

    <script async defer>
        {!! $google_maps_script !!}
    </script>  
    <script src="/js/panel-lawyers.js?id={{ str_random(6) }}" type="text/javascript"></script>
@endsection

@section('inline-scripts')  
@endsection
