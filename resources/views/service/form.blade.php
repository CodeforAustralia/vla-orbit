    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>Service</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/service" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="sv_id" name="sv_id" value="{{ isset($current_service) ? $current_service->ServiceId : 0 }}" required>
                    </div>                    

                    <div class="form-group pull-right">   
                        <label for="Status">Show this service in results?</label>                      
                        <input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" name="Status" id="Status" {{ ( isset( $current_service ) && $current_service->Status == 0 ) ? '' : 'checked' }}>
                    </div>

                    <div class="form-group">
                        <label for="name">Service Name (EG: Fines Drop In Clinic):</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ isset($current_service) ? $current_service->ServiceName : '' }}" required>
                    </div>

	                <div class="form-group">
	                  	<label for="phone">Phone Number (No spaces EG: 0390001000 or 0400100200):</label>
	                  	<input type="phone" class="form-control" id="phone" name="phone" value="{{ isset($current_service) ? $current_service->Phone : '' }}" required>
	                </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ isset($current_service) ? $current_service->Email : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location: (EG: 570 Bourke Street, Melbourne 3000)</label>
                        <input type="location" class="form-control" id="location" name="location" value="{{ isset($current_service) ? $current_service->Location : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="URL">URL: (EG: www.codeforaustralia.org with no http:// beforehand)</label>
                        <input type="text" class="form-control" id="URL" name="URL" value="{{ isset($current_service) ? $current_service->URL : '' }}" required>
                    </div>

	                <div class="form-group">
	                  	<label for="description">Description:</label>
                        <textarea class="form-control" rows="5" class="form-control" id="description" name="description" required>{{ isset($current_service) ? $current_service->Description : '' }}</textarea>
	                </div>

	                <div class="form-group">
	                  	<label for="service_provider_id">Service Provider:</label>	                  	
                        <select class="form-control" id="service_provider_id" name="service_provider_id">                                
                            @foreach($service_providers as $service_provider)
                                <option value="{{ $service_provider['ServiceProviderId'] }}" {{ (isset($current_service) && $service_provider['ServiceProviderId'] ==  $current_service->ServiceProviderId ) ? 'selected' : '' }} > {{ $service_provider['ServiceProviderName'] }} </option>
                            @endforeach
                        </select>
	                </div>

                    <div class="form-group">
                        <label for="wait">Wait Time (EG: 1 week, 7 weeks, 22 weeks):</label>
                        <input type="text" class="form-control" id="wait" name="wait" value="{{ isset($current_service) ? $current_service->Wait : '' }}" required>
                    </div>

	                <div class="form-group">
	                  	<label for="OpenningHrs">Opening Hours:</label>
	                  	<input type="text" class="form-control" id="OpenningHrs" name="OpenningHrs" value="{{ isset($current_service) ? $current_service->OpenningHrs : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="service_level_id">Service Level:</label>	                  	
                        <select class="form-control" id="service_level_id" name="service_level_id">                                
                            @foreach($service_levels as $service_level)
                                <option value="{{ $service_level['ServiceLevelId'] }}" {{ (isset($current_service) && $service_level['ServiceLevelId'] ==  $current_service->ServiceLevelId ) ? 'selected' : '' }} > {{ $service_level['ServiceLevelName'] }} </option>
                            @endforeach
                        </select>
	                </div>

	                <div class="form-group">
	                  	<label for="service_type_id">Service Type:</label>     	
                        <select class="form-control" id="service_type_id" name="service_type_id">                                
                            @foreach($service_types as $service_type)
                                <option value="{{ $service_type['ServiceTypelId'] }}" {{ (isset($current_service) && $service_type['ServiceTypelId'] ==  $current_service->ServiceTypeId) ? 'selected' : '' }} > {{ $service_type['ServiceTypeName'] }} </option>
                            @endforeach
                        </select>
	                </div>

                    <div class="form-group">
                        <label for="matters">Legal Matters (Make sure you are ONLY adding Specific Issues):</label>
                         <select multiple class="form-control" id="matters" name="matters[]"></select>
                    </div>

                    <div class="form-group">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-purple-soft bold uppercase">Catchment Area</span>
                                </div>
                            </div>
                            <div class="portlet-body">
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
                    </div>

                    @include ('service.vulnerability')

                    @if( isset($current_service) )
                        @include ('service.questions')
                    @else
                        <p>To narrow down Legal Matters or override Eligibility Criteria per Legal Matter you must save this service first.</p>
                    @endif

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-lg green">Save Service</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>

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
@endsection

@section('inline-scripts')

    function loadServiceMatters()            
    {
        $("#matters").select2({width: '100%'}).val( {{ isset($matter_services) ? json_encode( $matter_services ) : '[]' }} ).trigger("change");        
    }

    function loadCatchments()
    {
        @if ( isset($catchments) )
            $("#lga").select2({width: '100%'}).val(  {{ json_encode( $catchments['LGA'] ) }} ).trigger("change");
            $("#suburbs").select2({width: '100%'}).val(  {{ json_encode( $catchments['Suburbs'] ) }} ).trigger("change");
            $("#postcodes").val( '{{ $catchments['Postcode'] }}' );
        @endif
    }

    $('#description').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],          
            ['link', ['linkDialogShow', 'unlink']]          
        ]
    });

@endsection
