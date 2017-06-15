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

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ isset($current_service) ? $current_service->ServiceName : '' }}" required>
                    </div>

	                <div class="form-group">
	                  	<label for="phone">Phone:</label>
	                  	<input type="phone" class="form-control" id="phone" name="phone" value="{{ isset($current_service) ? $current_service->Phone : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="email">Email:</label>
	                  	<input type="email" class="form-control" id="email" name="email" value="{{ isset($current_service) ? $current_service->Email : '' }}" required>
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
	                  	<label for="wait">Wait Time:</label>
	                  	<input type="text" class="form-control" id="wait" name="wait" value="{{ isset($current_service) ? $current_service->Wait : '' }}" required>
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
                        <label for="matters">Legal Matters:</label>
                         <select multiple class="form-control" id="matters" name="matters[]">
                        </select>
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
                        <p>To set questions to this Service please save it first.</p>
                    @endif

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Submit</button>
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>

@section('scripts')
    <script src="/js/init_select2.js?id={{ str_random(6) }}"></script>
@endsection

@section('inline-scripts')

    function loadServiceMatters()            
    {
        $("#matters").select2().val( {{ isset($matter_services) ? json_encode( $matter_services ) : '[]' }} ).trigger("change");        
    }

    function loadCatchments()
    {
        @if ( isset($catchments) )
            $("#lga").select2({width: '100%'}).val(  {{ json_encode( $catchments['LGA'] ) }} ).trigger("change");
            $("#suburbs").select2({width: '100%'}).val(  {{ json_encode( $catchments['Suburbs'] ) }} ).trigger("change");
            $("#postcodes").val( '{{ $catchments['Postcode'] }}' );
        @endif
    }

@endsection