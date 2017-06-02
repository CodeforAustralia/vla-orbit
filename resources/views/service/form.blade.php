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

    <script type="text/javascript">
            function loadServiceMatters()
            {
                //$("#matters").val( {{ isset($matter_services) ? json_encode( $matter_services ) : '' }} );                
                $("#matters").select2().val({{ isset($matter_services) ? json_encode( $matter_services ) : '[]' }}).trigger("change");
            }            
    </script>

