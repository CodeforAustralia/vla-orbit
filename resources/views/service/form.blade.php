    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-notebook"></i>Service</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/service" class="form-horizontal" id="service_submit">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="sv_id" name="sv_id" value="{{ isset($current_service) ? $current_service->ServiceId : 0 }}" required>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-5">
                            <p class="caption-subject font-purple-soft bold uppercase">Service Details</p>
                        </div>

                        <div class="col-xs-7 text-right">
                            <label for="Status">Show this service in results?</label>
                            <input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" name="Status" id="Status" {{ ( isset( $current_service ) && $current_service->Status == 0 ) ? '' : 'checked' }}>
                        </div>

                        <div class="col-sm-12">
                            <label for="service_provider_id">Service Provider: <small>if not listed go to the Service Provider tab in left sidebar to create new</small></label>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control col-sm-7" id="service_provider_id" name="service_provider_id">
                                @foreach($service_providers as $service_provider)
                                    <option value="{{ $service_provider['ServiceProviderId'] }}" {{ (isset($current_service) && $service_provider['ServiceProviderId'] ==  $current_service->ServiceProviderId ) ? 'selected' : '' }} > {{ $service_provider['ServiceProviderName'] }} </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-sm-12">
                            <label for="name">Service Name: <small>eg. Fines Drop-in Clinic</small></label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="name" name="name" value="{{ isset($current_service) ? $current_service->ServiceName : '' }}" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="service_type_id">Service Type:</label>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control" id="service_type_id" name="service_type_id">
                                @foreach($service_types as $service_type)
                                    <option value="{{ $service_type['ServiceTypelId'] }}" {{ (isset($current_service) && $service_type['ServiceTypelId'] ==  $current_service->ServiceTypeId) ? 'selected' : '' }} > {{ $service_type['ServiceTypeName'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="service_level_id">Service Level:</label>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control" id="service_level_id" name="service_level_id">
                                @foreach($service_levels as $service_level)
                                    <option value="{{ $service_level['ServiceLevelId'] }}" {{ (isset($current_service) && $service_level['ServiceLevelId'] ==  $current_service->ServiceLevelId ) ? 'selected' : '' }} > {{ $service_level['ServiceLevelName'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="wait">Wait Time: <small>eg. 2 weeks for an appointment or 20 min. wait for a phone service</small></label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="wait" name="wait" value="{{ isset($current_service) ? $current_service->Wait : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="description">Description: <small>incl. info on how to proceed, what to expect and what to prepare ahead of making contact</small></label>
                            <textarea class="form-control" rows="5" class="form-control" id="description" name="description" required>{{ isset($current_service) ? $current_service->Description : '' }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-xs-5">
                            <p class="caption-subject font-purple-soft bold uppercase">Contact Details</p>
                        </div>
                        <div class="col-sm-12">
                            <label for="location">Location: <small>eg. 123 Fitzroy St, Brunswick 3056. If none put #</small></label>
                        </div>
                        <div class="col-sm-7">
                            <input type="location" class="form-control" id="location" name="location" value="{{ isset($current_service) ? $current_service->Location : '' }}" required>
                        </div>
                    </div>

	                <div class="form-group">
                        <div class="col-sm-12">
	                  	    <label for="phone">Phone Number: <small>eg. 0444 333 222 The number is for clients to contact the service. If none put #</small></label>
                        </div>
                        <div class="col-sm-7">
    	                  	<input type="text" class="form-control" id="phone" name="phone" value="{{ isset($current_service) ? $current_service->Phone : '' }}" required>
                        </div>
	                </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="email">Email: <small>any e-referrals will be sent to this address</small></label>
                        </div>
                        <div class="col-sm-7">
                            <input type="email" class="form-control" id="email" name="email" value="{{ isset($current_service) ? $current_service->Email : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="URL">Website: <small>eg. www.codeforaustralia.org. Do not include http://</small></label>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="URL" name="URL" value="{{ isset($current_service) ? $current_service->URL : '' }}" required>
                        </div>
                    </div>

	                <div class="form-group">
                        <div class="col-sm-12">
	                  	    <label for="OpenningHrs">Opening Hours: <small>eg. Wednesdays 2-4pm or Weekdays 9am - 5pm</small></label>
                        </div>
                        <div class="col-sm-7">
    	                  	<input type="text" class="form-control" id="OpenningHrs" name="OpenningHrs" value="{{ isset($current_service) ? $current_service->OpenningHrs : '' }}" required>
                        </div>
	                </div>


                    <div class="form-group">

                        <div class="col-xs-5">
                            <p class="caption-subject font-purple-soft bold uppercase">Catchment Area</p>
                        </div>

                        <div class="col-sm-12">
                            <p><small>If the service has a catchment add the local government areas or suburbs here. If no catchment leave blank</small></p>

                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_lga" data-toggle="tab"> LGA </a>
                                </li>
                                <li>
                                    <a href="#tab_suburb" data-toggle="tab"> Suburb </a>
                                </li>
                                <li>
                                    <a href="#tab_postcode" data-toggle="tab"> Postcode </a>
                                </li>
                            </ul>
                            <div class="tab-content">

                                <div class="tab-pane fade active in" id="tab_lga">
                                    <select multiple class="form-control" id="lga" name="lga[]"></select>

                                </div>

                                <div class="tab-pane fade" id="tab_suburb">
                                    <select multiple class="form-control col-xs-9" id="suburbs" name="suburbs[]"></select>
                                </div>

                                <div class="tab-pane fade" id="tab_postcode">
                                    <input type="postcodes" class="form-control" id="postcodes" name="postcodes" value="">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-5">
                            <p class="caption-subject font-purple-soft bold uppercase">Legal Matters</p>
                        </div>

                        <div class="col-sm-12">
                            <p><small>Choose the legal matters covered by the service. If needed <a data-toggle="modal" href="#request-matter">request a new legal matter</a>.</small></p>
                            <select multiple class="form-control" id="matters" name="matters[]"></select>

                        </div>
                    </div>

                    @include ('service.vulnerability')

                    @if( isset($current_service) )
                        @include ('service.questions')
                    @else
	                    <div class="form-group">
                            <div class="col-sm-12">
    	                        <p><small>To narrow down Legal Matters or override Eligibility Criteria per Legal Matter you must save this service first.</small></p>
                            </div>
	                    </div>
                    @endif

                    <div class="panel-group accordion" id="accordion1">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title font-purple-soft bold uppercase">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1"> Referral Settings </a>
                                </h4>
                            </div>
                            <div id="collapse_1" class="panel-collapse collapse">
                                <div class="panel-body">

                                    <div class="form-group col-sm-12">
                                        <p class="margin-0">Referrals conditions:</p>
                                        <p class="font-grey-silver margin-bottom-10">Enable Referrals to specific Service Providers by adding them here. <span id="count_referral_conditions">({{ isset($referral_conditions) ? count( $referral_conditions ) : '0' }}) </span> &nbsp;<a href="javascript:;" class="btn btn-xs green" select-all-sp="referral">Select All</a> &nbsp;
                                        <a href="javascript:;" class="btn btn-xs red" clear-all-sp="referral">Clear</a></p>
                                        <select multiple class="form-control" id="referral_conditions" name="referral_conditions[]"></select>
                                    </div>

                                </div>
                            </div>
                        </div>

                            <div class="panel panel-default {{ (isset($current_service) && $current_service->ServiceProviderTypeName == 'VLA' ? '' : 'hidden' )}}">
                            <div class="panel-heading">
                                <h4 class="panel-title font-purple-soft bold uppercase">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2"> Booking Settings </a>
                                </h4>
                            </div>
                            <div id="collapse_2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    @if(!isset($current_service))
                                        <span>To enable bookings you must save this service first.</span>
                                    @elseif( isset($service_booking) && empty($service_booking))
                                        <div class="form-group col-sm-12 margin-0" id="service_booking">
                                            <a href="#" class="btn green" @click="activateService({{ isset($current_service) ? $current_service->ServiceId : 0 }})">Enable bookings</a>
                                        </div>
                                    @endif
                                    <div class="form-group col-sm-12 service_booking_conditions {{ (isset($service_booking) && empty($service_booking) ? 'hidden' : '' )}}">
                                        <p class="margin-0">Bookings conditions:</p>
                                        <p class="font-grey-silver margin-bottom-10">Enable bookings to specific Service Providers by adding them here. <span id="count_booking_conditions">({{ isset($booking_conditions) ? count( $booking_conditions) : '0'  }}) </span>  &nbsp;<a href="javascript:;" class="btn btn-xs green" select-all-sp="booking">Select All</a> &nbsp; <a href="javascript:;" class="btn btn-xs red" clear-all-sp="booking">Clear</a></p>
                                        <select multiple class="form-control" id="booking_conditions" name="booking_conditions[]"></select>
                                        <div class="form-group col-sm-12">
                                            @include ('service.booking_questions')
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title font-purple-soft bold uppercase">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3"> E-Referral Settings </a>
                                </h4>
                            </div>
                            <div id="collapse_3" class="panel-collapse collapse">
                                <div class="panel-body">

                                    <div class="form-group col-sm-12">
                                        <p class="margin-0"> E-Referral conditions:</p>
                                        <p class="font-grey-silver margin-bottom-10">Enable E-Referreal to specific Service Providers by adding them here. <span id="count_e_referral_conditions">({{ isset($e_referral_conditions) ? count( $e_referral_conditions) : '0'  }}) </span>  &nbsp;<a href="javascript:;" class="btn btn-xs green" select-all-sp="e_referral">Select All</a> &nbsp; <a href="javascript:;" class="btn btn-xs red" clear-all-sp="e_referral">Clear</a></p>
                                        <select multiple class="form-control" id="e_referral_conditions" name="e_referral_conditions[]"></select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <p class="font-grey-silver margin-bottom-10">Choose e-referral template available for ths service. Template chosen here are for all added service providers.</p>
                                        <select multiple class="form-control" id="e_referral_forms" name="e_referral_forms[]"></select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-lg green" >Save Service</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>

    @include ('service.request_additional_modal')

@section('styles')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
<link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<!-- Bootstrap toogle CSS -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!-- END Bootstrap toogle CSS -->
@endsection

@section('scripts')
    <script src="/js/init_select2.js?id={{ str_random(6) }}"></script>
    <script src="/assets/pages/scripts/components-bootstrap-tagsinput.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
    <!-- Bootstrap toogle JS -->
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- END Bootstrap toogle JS -->
    <script src="/js/request_service_vue.js?id={{ str_random(6) }}"></script>
    <script src="/js/service_booking.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')
    //init_select2.js is loading information by ajx for each select2 field in this page
    function loadServiceMatters()
    {
        $("#matters").select2({width: '100%',escapeMarkup: function (text) {return text;}}).val( {{ isset($matter_services) ? json_encode( $matter_services ) : '[]' }} ).trigger("change");

    }

    function loadCatchments()
    {
        @if ( isset($catchments) )
            $("#lga").select2({width: '100%'}).val(  {{ json_encode( $catchments['LGA'] ) }} ).trigger("change");
            $("#suburbs").select2({width: '100%'}).val(  {{ json_encode( $catchments['Suburbs'] ) }} ).trigger("change");
            $("#postcodes").val( '{{ $catchments['Postcode'] }}' );
        @endif
    }

    function loadReferralConditions()
    {
        $("#referral_conditions").select2({width: '100%'}).val(  {{ isset($referral_conditions) ? json_encode( $referral_conditions ) : '[]' }} ).trigger("change").on("change", function(e) {
          $('#count_referral_conditions').text('(' + $(this).next().find('.select2-selection__choice').length + ')');
        });
    }

    function loadBookingConditions()
    {
        $("#booking_conditions").select2({width: '100%'}).val(  {{ isset($booking_conditions) ? json_encode( $booking_conditions) : '[]'  }} ).trigger("change").on("change", function(e) {
          $('#count_booking_conditions').text('(' + $(this).next().find('.select2-selection__choice').length + ')');
        });
    }

    function loadEReferralConditions()
    {
        $("#e_referral_conditions").select2({width: '100%'}).val(  {{ isset($e_referral_conditions) ? json_encode( $e_referral_conditions) : '[]'  }} ).trigger("change").on("change", function(e) {
          $('#count_e_referral_conditions').text('(' + $(this).next().find('.select2-selection__choice').length + ')');
        });
    }

    function loadEReferralForms()
    {
        $("#e_referral_forms").select2({width: '100%'}).val(  {{ isset($e_referral_forms) ? json_encode( $e_referral_forms) : '[]'  }} ).trigger("change");
    }

    function showLoader()
    {
        $("#contentLoading").modal("show");
    }

    $('#description').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['link', ['linkDialogShow', 'unlink']]
        ]
    });
    $('#service_submit').submit(function() {
        $("#contentLoading").modal("show");
    });

@endsection
