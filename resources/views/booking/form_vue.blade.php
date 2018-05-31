
<div id="app">

    <div>
        <br>    
        <small>Book a client into an appointment by using the Direct Booking form below. The office/program area providing the service will be able to see the booking in ORBIT. </small>
        <br>    
        <small>Legal Help: as an alternative to Direct Bookings you can send an e-referral to the office/program area by choosing any of the other form types. A copy of this email is bcc’d to the LegalInfoCallBack mailbox.</small>
    </div>
    <hr>    

    <form role="form" method="POST" action="/booking" enctype="multipart/form-data" id="bookingForm">

        {{ csrf_field() }}

        <div class="row">
            <div class="col-xs-12 col-md-12 padding-bottom-20">
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>Office/program area:</label>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <select class="form-control" v-on:change="onChangeSP" name="service_provider_id" v-model="selected_sp" required>
                            <option :value="null"></option>
                            <option v-for="service_provider in service_providers" :value="service_provider.id" v-text="service_provider.text"></option>
                        </select>
                    </div>
                </div>
                <input type="text" class="form-control input-large hidden" id="ServiceProviderName" name="ServiceProviderName" :value="current_service.ServiceProviderName"> 
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
                        <select class="form-control" v-on:change="onChangeService" name="ServiceId" required>
                            <option :value="null"></option>
                            <option v-for="service in services" :value="service.BookingServiceId + '-'+ service.BookingInterpritterServiceId +'-' + service.ServiceId" v-html="service.ServiceName"></option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-4 hidden">
                        <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#EligibilityConfirm">View Service Details</button>
                    </div>
                </div>
                <input type="text" class="form-control input-large hidden" id="ServiceName" name="ServiceName" :value="current_service.ServiceName"> 
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
                        <select class="form-control" v-on:change="onChangeFormType" name="request_type" id="request_type" required>
                            <option :value="null"></option>
                            <option value="0" v-if="can_book">Direct Booking</option>
                            <option v-for="form in e_referral_forms" :value="form.ReferralFormID" v-text="form.ReferralFromName" v-if="can_e_referr"></option>
                        </select>                  
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#bookingTypeDescription">More info</button>
                    </div>
                </div>
            </div>
        </div>    

        <hr>
        <h4 class="padding-top-10 padding-bottom-10">Appointment Details</h4>

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
                        <select class="form-control" id="Language" name="Language" v-on:change="onChangeLanguage">    
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
                        
                        <div class="mt-radio-inline padding-left-20" v-on:change="onChangeComplex">
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
        
        <div class="booking-area" v-show="can_book && is_direct_booking">   
            <div class="row availability">
                <div class="col-xs-12 padding-bottom-20">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Date of appointment: <small>choose from dates marked green</small></label>                
                            <input type="text" class="form-control input-medium" id="booking-date" name="booking-date" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row availability" >
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Available Times:</label>
                            <div class="mt-radio-list">
                                <label class="mt-radio mt-radio-outline"  v-for="time in available_times">
                                    <input type="radio" name="serviceTime" 
                                    :value="time.value">
                                    @{{ time.text }}<span></span>
                                </label>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>

        <hr>
        <h4 class="padding-top-10 padding-bottom-10">Client Details</h4>
        
        <div class="row">
            <div class="col-xs-5 col-sm-6 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>First Name:</label>
                        <input type="text" class="form-control input-large" placeholder="Jane" name="client[FirstName]" id="FirstName" required> 
                    </div>
                </div>
            </div>

            <div class="col-xs-4 col-sm-6 col-md-6 col-lg-5">        
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Last Name:</label>
                        <input type="text" class="form-control input-large" placeholder="Smith" name="client[LastName]" id="LastName"  required> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>CIR Number: <small>if known / required if legal advice is given</small></label>
                        <input type="text" class="form-control input-large" placeholder="1234567" name="CIRNumber" id="CIRNumber">                 
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="booking_template_id == 2">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Date of Birth</label>
                        <input type="text" class="form-control input-large" name="dob" id="dob">                 
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="booking_template_id == 2">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Suburb town of caller</label>
                        <input type="text" class="form-control input-large" name="suburb" id="suburb">                 
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

        <div class="row" v-if="booking_template_id == 5">
            <div class="col-xs-5 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Postal Address:</label>
                        <input type="text" class="form-control input-large" name="postal_address" id="postal_address"> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="booking_template_id == 5">
            <div class="col-xs-5 col-md-6 col-lg-5">                            
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Email:</label>
                        <input type="text" class="form-control input-large" placeholder="janesmith@gmail.com" name="client[ClientEmail]" id="email"> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="booking_template_id == 5">
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
            <div class="col-xs-5 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Phone Number:</label>
                        <input type="text" class="form-control input-large" placeholder="0400 000 000" name="client[Mobile]" id="mobile"> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="can_book && is_direct_booking">
            <div class="col-xs-12">                            
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>SMS auto-confirmation? &nbsp; <small>This will send an appointment confirmation when this booking is saved.</small></label>
                        <div class="mt-radio-inline padding-left-20">
                            <label class="mt-radio mt-radio-outline">
                                <input type="radio" name="RemindNow" id="RemindNow" value="Yes">Yes<span></span>
                            </label>
                            <label class="mt-radio mt-radio-outline">
                                <input type="radio" name="RemindNow" id="RemindNow" value="No">No<span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="can_book && is_direct_booking">
            <div class="col-xs-12">                            
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>SMS auto-reminder? &nbsp; <small>This will send an appointment reminder one day before the appointment.</small></label>
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
            <div class="col-xs-5 col-md-7 col-lg-8">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Is it ok to leave a message or send SMS? Any other instructions re contact?</label>
                        <input type="text" class="form-control input-large" name="reContact" id="reContact"> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-10">
                        <label>Description:</label>
                        <textarea v-text="booking_template" rows="5" class="form-control" id="Desc" placeholder="Client requirements, special needs, difficulties experienced with client, time limits, instructions for contact or any other information that may be useful for the service provider to know beforehand." name="Desc" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <!-- div >
            <h4 class="padding-top-10 padding-bottom-10">Attached Files</h4>
            <div class="col-xs-12">
                <div class="form-group booking-form">
                    <input type="file" multiple name="files" accept=".png,.jpg,.jpeg,.pdf">
                    <p>Drag your files here or click in this area.</p>
                </div>
            </div>
        </div-->
        
        <div class="row attached-files" v-show="can_book && is_direct_booking">            
            <div class="col-xs-12 col-md-6">                                
                <div class="form-group mt-repeater">
                    <div data-repeater-list="attachments">
                        <div class="mt-repeater-item">
                            <label class="control-label">Attachment</label>
                            <input type="file" name="files" class="form-control" accept=".png,.jpg,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.msg"> 
                        </div>
                        <div data-repeater-item class="mt-repeater-item mt-overflow">
                            <label class="control-label">Additional Attachment</label>
                            <div class="mt-repeater-cell">
                                <input type="file" name="files" class="form-control mt-repeater-input-inline" accept=".png,.jpg,.jpeg,.pdf" />
                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete mt-repeater-del-right mt-repeater-btn-inline">
                                    <i class="fa fa-close"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
                        <i class="fa fa-plus"></i> Add new Attachment</a>
                </div>
            </div>
        </div>  
        
        <div class="row">
            <div class="col-xs-12 padding-top-10 padding-bottom-20">
                <button type="submit" class="btn green-jungle btn-block btn-lg" id="submit-booking">Make Booking</button>
            </div>
        </div>
        
    </form>
    
    @include ('booking.booking_description')   
    
</div>


@section('scripts')    
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    
    <script src="/js/bookings_vue.js?id={{ str_random(6) }}" type="text/javascript"></script>
@endsection

@section('styles')    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endsection