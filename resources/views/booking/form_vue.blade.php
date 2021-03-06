
<div id="app">

    <div>
        <br>
        <small>Book a client into an appointment by using the Direct Booking form below. The office/program area providing the service will be able to see the booking in {{ strtoupper(config('app.name')) }}. </small>
        <br>
        <small>Legal Help: as an alternative to Direct Bookings you can send an e-referral to the office/program area by choosing any of the other form types. A copy of this email is bcc’d to the LegalInfoCallBack mailbox.</small>
    </div>
    <hr>

    <form role="form" method="POST" action="/booking" enctype="multipart/form-data" id="bookingForm">

        {{ csrf_field() }}
        <input type="hidden" id="created_by" name="created_by" value="{{ Auth::user()->name }}">
        <input type="hidden" id="e-referral_header" name="e-referal-header" :value="header_value">
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
                        <label>Form Type:</label> <small>if direct booking is not available, this service is not in {{ strtoupper(config('app.name')) }} yet. Legal Help users can select an e-referral template here</small>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 ">
                <div class="form-group">
                    <div class="col-xs-12 col-md-6 padding-bottom-10">
                        <select class="form-control" v-on:change="onChangeFormType" name="request_type" id="request_type" required>
                            <option :value="null"></option>
                            <option value="0" v-if='can_book'>Direct Booking</option>
                            <option v-for="form in e_referral_forms" :value="form.ReferralFormID" v-text="form.ReferralFromName" v-if="can_e_referr"></option>
                            <option disabled v-if="display_no_book_refer">This service cannot book or e-refer. Please check the service configuration</option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <button type="button" class="btn btn-block dark btn-outline" data-toggle="modal" data-target="#bookingTypeDescription">More info</button>
                    </div>
                </div>
            </div>
        </div>

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

        <hr v-show="can_book && is_direct_booking">
        <h4 class="padding-top-10 padding-bottom-10" v-show="can_book && is_direct_booking">Appointment Details</h4>

        <div class="booking-area" v-show="can_book && is_direct_booking">
            <div class="row availability">
                <div class="col-xs-12 padding-bottom-20">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Date of appointment: <small>choose from dates marked green</small></label>
                            <input type="text" class="form-control input-medium" id="booking-date" name="booking-date" autocomplete="off" required>
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
                                <label class="mt-radio mt-radio-outline" v-for= "time in available_times" :key="time.start_time" >
                                        <input type="radio" id='time' :value="time" v-model="hour">
                                        <input type="hidden"  v-if="hour" name="resource_id" id="resource_id" :value="hour.resource_id">
                                        <input type="hidden"  v-if="hour" name="time_length" id="time_length" :value="hour.duration">
                                        <input type="hidden"  v-if="hour" name="start_hour" id="start_hour" :value="hour.start_time">
                                        <input type="hidden"  v-if="hour" name="text" id="text" :value="hour.text">
                                        @{{ time.text }}<span></span><br>
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
                        <input type="text" class="form-control input-large" placeholder="Jane" name="firstName" id="FirstName" autocomplete="off" required>
                    </div>
                </div>
            </div>

            <div class="col-xs-4 col-sm-6 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Last Name:</label>
                        <input type="text" class="form-control input-large" placeholder="Smith" name="lastName" id="LastName" autocomplete="off" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>CIR Number: <small>if known / required if legal advice is given</small></label>
                        <input type="text" class="form-control input-large" placeholder="1234567" name="CIRNumber" autocomplete="off" id="CIRNumber">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="displayField('dob')">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Date of Birth: <small>not required if CIR provided</small></label>
                        <input type="text" class="form-control input-large" autocomplete="off" name="dob" id="dob">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-show="displayField('suburb')">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Suburb town of caller: <small>not required if CIR provided</small></label>
                        <input type="text" class="form-control input-large" name="suburb" autocomplete="off" id="suburb">
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

        <div class="row" v-if="displayField('postal_address')">
            <div class="col-xs-5 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Postal Address: <small>not required if CIR provided</small></label>
                        <input type="text" class="form-control input-large" name="postal_address" autocomplete="off" id="postal_address">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="displayField('email')">
            <div class="col-xs-5 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Email: <small>not required if CIR provided</small></label>
                        <input type="text" class="form-control input-large" placeholder="janesmith@gmail.com" name="client[ClientEmail]" autocomplete="off" id="email">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="displayField('email')">
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

        <div class="row" v-show="displayField('court_date')">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Court Date:</label>
                        <input type="text" class="form-control input-large" autocomplete="off" name="court_date" id="court_date">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-5 col-md-6 col-lg-5">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Phone Number:</label>
                        <input type="text" class="form-control input-large" placeholder="0400 000 000" name="phone" autocomplete="off" id="mobile">
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
                                <input type="radio" name="RemindNow" id="RemindNow" value="No" checked>No<span></span>
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
                        <label>SMS auto-reminder? &nbsp; <small>This will send an appointment reminder at 9am the day before the appointment.
                            <br>Note: This SMS may be arrive unexpectedly. Before selecting 'yes', please ensure the client will not be exposed to any family violence risk at this time </small></label>
                        <div class="mt-radio-inline padding-left-20">
                            <label class="mt-radio mt-radio-outline">
                                <input type="radio" name="phonepermission" id="phonepermission" value="Yes">Yes<span></span>
                            </label>
                            <label class="mt-radio mt-radio-outline">
                                <input type="radio" name="phonepermission" id="phonepermission" value="No" checked>No<span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-xs-5 col-md-7 col-lg-8">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20">
                        <label>Is it ok to leave a message or send SMS? Any other instructions re contact?</label>
                        <input type="text" class="form-control input-large" name="reContact" autocomplete="off" id="reContact">
                    </div>
                </div>
            </div>
        </div>

        <hr v-show="can_book && is_direct_booking && current_service.ServiceBookingQuestions.length >= 1">
        <h4 v-show="can_book && is_direct_booking && current_service.ServiceBookingQuestions.length >= 1" class="padding-top-10 padding-bottom-10">Booking Questions</h4>

        <div class="row" v-show="can_book && is_direct_booking && current_service.ServiceBookingQuestions.length >= 1">
        <a @click="display_help? display_help = false : display_help = true" pull-right font-green> @{{display_help? 'Hide Help' : 'Show Help'}} </a>
            <div class="col-xs-5 col-md-7 col-lg-8">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-20" v-for="question in current_service.ServiceBookingQuestions" v-bind:key="question.ServiceBookingQuestionId">
                        <div v-if="question.QuestionType=='boolean'">
                            <label  :for="question.ServiceBookingQuestionId">
                                @{{question.QuestionLabel }}
                            </label>
                            <br>
                            <small v-show='display_help' class="elibigility_label">@{{question.QuestionName }}</small>
                            <div class="mt-radio-inline padding-left-20">
                                <label class="mt-radio mt-radio-outline">
                                    <input @change="validateQuestions()" type="radio" id="question.ServiceBookingQuestionId" v-model="question.answer" value="true">Yes<span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    <input @change="validateQuestions()" type="radio" id="question.ServiceBookingQuestionId" v-model="question.answer" value="false">No<span></span>
                                </label>
                            </div>
                        </div>
                        <div v-else-if="question.QuestionType=='numeric'">
                            <label :for="question.ServiceBookingQuestionId">@{{question.QuestionLabel}}</label>
                            <br>
                            <small v-show='display_help' class="elibigility_label">@{{question.QuestionName }}</small>
                            <input type="number" v-on:keyup="validateQuestions()" class="form-control input-large" v-model="question.answer" autocomplete="off" :id="question.ServiceBookingQuestionId">
                        </div>
                        <div v-else-if="question.QuestionType=='text'">
                            <label :for="question.ServiceBookingQuestionId"> @{{question.QuestionLabel}}</label>
                            <br>
                            <small v-show='display_help' class="elibigility_label">@{{question.QuestionName }}</small>
                            <input type="text" v-on:keyup="validateQuestions()" class="form-control input-large" v-model="question.answer" autocomplete="off" :id="question.ServiceBookingQuestionId">
                        </div>
                        <div v-else-if="question.QuestionType=='multiple'">
                            <label :for="question.ServiceBookingQuestionId">@{{question.QuestionLabel}}</label>
                            <br>
                            <small v-show='display_help' class="elibigility_label">@{{question.QuestionName }}</small>
                            <select v-model="question.answer" class="form-control input-large" @change="validateQuestions()" :id="question.ServiceBookingQuestionId">
                                <option v-for="option in question.options" v-bind:value="option">
                                    @{{option}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h4 class="padding-top-10 padding-bottom-10">Description</h4>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-xs-12 padding-bottom-10">
                        <label><small>Client requirements, special needs, difficulties experienced with client, time limits,<br> instructions for contact or any other information that may be useful for the service provider to know beforehand.</small> </label>
                        <vue-mce
                            id="booking_description"
                            class="form-control"
                            :value="description_value"
                            :config="config"
                            name="Desc"/>
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
                            <input type="file" name="files" class="form-control" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
text/plain, application/pdf, image/*">
                        </div>
                        <div data-repeater-item class="mt-repeater-item mt-overflow">
                            <label class="control-label">Additional Attachment</label>
                            <div class="mt-repeater-cell">
                                <input type="file" name="files" class="form-control mt-repeater-input-inline" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
text/plain, application/pdf, image/*" />
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
                <button type="submit" :disabled="disable_submit" class="btn green-jungle btn-block btn-lg" id="submit-booking">Make Booking</button>
            </div>
        </div>

    </form>

    @include ('booking.booking_description')

</div>


@section('scripts')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="https://cloud.tinymce.com/dev/tinymce.min.js?apiKey={{ env('TYTINYMCE_KEY') }}" ></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>
    <script src="/js/bookings_vue.js?id={{ str_random(6) }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
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