@php    
    $sp_arr = [ 98, 99, 106, 119, 160 ];        
    $is_lh  = \App\Http\helpers::getUSerServiceProviderId() === 112;    
@endphp
<div>
    <br>    
    <small>Book a client in for an appointment using the form type Direct Booking. The office providing the service will be see the booking in Orbit. Send an e-referral to the office providing the service by choosing any of the other form types. A copy of the email sent is bcc’d to the LegalInfoCallBack mailbox.</small>
</div>
<hr>
<form role="form" method="POST" action="/booking" enctype="multipart/form-data" id="bookingForm">

    {{ csrf_field() }}

    <h4 class="padding-top-10 padding-bottom-10">Service Details</h4>
    
    <div class="row">
        <div class="col-xs-12 col-md-12 padding-bottom-20">
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Office/program area:</label> <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="ORBIT is currently for new client appointments at Civil Justice (internal use only), CLM summary crime, and Ringwood crime."></i>
                </div>
                <div class="col-xs-12 col-md-6">
                    <select class="form-control" id="service_provider_id" name="service_provider_id" required>                                
                        <option selected=""> </option>
                        
                        @foreach($service_providers as $service_provider)
                            @if( (in_array( $service_provider['ServiceProviderId'], $sp_arr ) && !$is_lh) ||  ($service_provider['ServiceProviderTypeName'] == 'VLA' && $is_lh) )
                            <option value="{{ $service_provider['ServiceProviderId'] }}"> {{ $service_provider['ServiceProviderName'] }} </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="text" class="form-control input-large hidden" id="ServiceProviderName" name="ServiceProviderName"> 
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">
            <div class="form-group">
			    <div class="col-xs-12">
			        <label>Service: <small>if unsure if client qualifies for service find the VLA service guidelines <a href="https://viclegalaid.sharepoint.com/sites/intranet/practiceresources/Pages/default.aspx" target="_blank">here</a></small></label>  
			    </div>
		    </div>
        </div>
        <div class="col-xs-12 col-md-12 padding-bottom-20">
            <div class="form-group">
                
                <div class="col-xs-12 col-md-6">
                    <select class="form-control" id="sp_services" name="ServiceId" required>
                        <option> </option>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-4 hidden">
                    <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#EligibilityConfirm">View Service Details</button>
                </div>
            </div>
            <input type="text" class="form-control input-large hidden" id="ServiceName" name="ServiceName"> 
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">
            <div class="form-group">
			    <div class="col-xs-12">
			        <label>Form Type:</label> <small>if direct booking is not available, this service is not in ORBIT yet. Legal Help users can select an e-referral template here</small>
			    </div>
		    </div>
        </div>
        <div class="col-xs-12 col-md-12 ">
            <div class="form-group">
                <div class="col-xs-12 col-md-6 padding-bottom-10">                    
                    <select class="form-control" id="request_type" name="request_type">                                                    
                        <option value="direct_booking" id="direct_booking">Direct Booking</option>
                        @if( \App\Http\helpers::getUSerServiceProviderId() === 112 || \App\Http\helpers::getUSerServiceProviderId() === 0 )
                        <option value="appointment_request">Appointment Request</option>
                        <option value="for_assessment">For Assessment</option>
                        <option value="phone_advice">Phone Advice</option>
                        <option value="duty_layer">Duty Lawyer</option>
                        @endif
                    </select>                    
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#bookingTypeDescription">More info</button>
                </div>
            </div>
        </div>
    </div>    

    <hr>
    <h4 class="padding-top-10 padding-bottom-10">Appointment details Details</h4>

    <div class="row">
        <div class="col-xs-12 ">
            <div class="form-group">
			    <div class="col-xs-12">
            		<label>Interpreter required?</label> <small>choose language from list. May result in longer appointment</small>
			    </div>
		    </div>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="form-group">
                <div class="col-xs-8 col-md-6 padding-bottom-10">
                    <select class="form-control" id="Language" name="Language">    
                        @include( 'booking.language' )
                    </select>                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-8 padding-bottom-10">
                    <label>Complex needs? <small>may result in longer appointment</small></label> <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Eg barrier to comprehending advice such as an intellectual disability, ABI,cognitive or psychiatric disability. May result in longer appointments at some services."></i>
                    
                    <div class="mt-radio-inline padding-left-20">
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="IsComplex" id="IsComplex" value="1">Yes<span></span>
                        </label>
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="IsComplex" id="IsComplex" value="0">No<span></span>
                        </label>
                    </div>                  
                </div>
            </div>
        </div>
    </div>

    @include ('booking.book-button')                    
    
    <hr>
    <h4 class="padding-top-10 padding-bottom-10">Client Details</h4>
    
    <div class="row">
        <div class="col-xs-5 col-sm-6 col-md-6 col-lg-5">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>First Name:</label>
                    <input type="text" class="form-control input-large" placeholder="Jane" name="client[FirstName]" required> 
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-sm-6 col-md-6 col-lg-5">        
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>Last Name:</label>
                    <input type="text" class="form-control input-large" placeholder="Smith" name="client[LastName]" required> 
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>CIR Number (if legal advice is given):</label>
                    <input type="text" class="form-control input-large" placeholder="1234567" name="CIRNumber" id="CIRNumber">                 
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
	    			<strong>Contact Information</strong>
	            </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5 col-md-6 col-lg-5">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>Phone Number:</label>
                    <input type="text" class="form-control input-large" placeholder="0400 000 000" name="client[Mobile]" id="mobile"> 
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">                            
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Is it safe to contact this client by phone call and SMS? &nbsp; <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="If it is safe to contact the client on their phone we may call or send the client an SMS to remind them of their booking or to notify them of changes to their booking. Select 'No' if it is unsafe to call or SMS this client."></i></label>
                    <div class="mt-radio-inline padding-left-20">
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="phonepermission" id="phonepermission" value="Yes">Yes<span></span>
                        </label>
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="phonepermission" id="phonepermission" value="No">No<span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5 col-md-6 col-lg-5">                            
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-20">
                    <label>Email:</label>
                    <input type="text" class="form-control input-large" placeholder="janesmith@gmail.com" name="client[ClientEmail]" id="email"> 
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Is it safe to contact this client by email? &nbsp; </label> <i class="fa fa-info-circle tooltips" aria-hidden="true" data-container="body" data-placement="right" data-original-title="Optional, and for recording in the booking only. Clients do not receive emailed booking confirmations."></i>
                    <div class="mt-radio-inline padding-left-20">
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="emailpermission" id="emailpermission" value="Yes">Yes<span></span>
                        </label>
                        <label class="mt-radio mt-radio-outline">
                            <input type="radio" name="emailpermission" id="emailpermission" value="No">No<span></span>
                        </label>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="col-xs-12 padding-bottom-10">
                    <label>Description:</label>
                    <textarea rows="5" class="form-control" id="Desc" placeholder="Client requirements, special needs, difficulties experienced with client, time limits, instructions for contact or any other information that may be useful for the service provider to know beforehand." name="Desc" required></textarea>
                </div>
            </div>
        </div>
    </div>

    @include ('booking.upload-files')

    <div class="row">
        <div class="col-xs-12 padding-top-10 padding-bottom-20">
            <button type="submit" class="btn green-jungle btn-block btn-lg" id="submit-booking">Make Booking</button>
        </div>
    </div>
</form>

@include ('booking.booking_description')   

@section('styles')    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection

@section('scripts')    
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="/js/booking.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <script src="/js/booking-dropzone.js" type="text/javascript"></script>
@endsection