@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create Service </h1>
    <!-- END PAGE HEADER-->

    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>New Service</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/service" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

	                <div class="form-group">
	                  	<label for="name">Name:</label>
	                  	<input type="text" class="form-control" id="name" name="name" required>
	                </div>

	                <div class="form-group">
	                  	<label for="phone">Phone:</label>
	                  	<input type="phone" class="form-control" id="phone" name="phone">
	                </div>

	                <div class="form-group">
	                  	<label for="email">Email:</label>
	                  	<input type="email" class="form-control" id="email" name="email">
	                </div>

	                <div class="form-group">
	                  	<label for="description">Description:</label>
	                  	<input type="text" class="form-control" id="description" name="description">
	                </div>

	                <div class="form-group">
	                  	<label for="service_provider_id">Service Provider:</label>	                  	
                        <select class="form-control" id="service_provider_id" name="service_provider_id">                                
                            @foreach($service_providers as $service_provider)
                                <option value="{{ $service_provider['ServiceProviderId'] }}">{{ $service_provider['ServiceProviderName'] }}</option>
                            @endforeach
                        </select>
	                </div>

	                <div class="form-group">
	                  	<label for="wait">Wait Time:</label>
	                  	<input type="text" class="form-control" id="wait" name="wait">
	                </div>

	                <div class="form-group">
	                  	<label for="location_id">Location Id:</label>	                  	
                        <select class="form-control" id="location_id" name="location_id">                                
                            @foreach($service_levels as $service_level)
                                <option value="{{ $service_level['ServiceLevelId'] }}">{{ $service_level['ServiceLevelName'] }}</option>
                            @endforeach
                        </select>
	                </div>

	                <div class="form-group">
	                  	<label for="service_type_id">Service Type Id:</label>     	
                        <select class="form-control" id="service_type_id" name="service_type_id">                                
                            @foreach($service_types as $service_type)
                                <option value="{{ $service_type['ServiceTypelId'] }}">{{ $service_type['ServiceTypeName'] }}</option>
                            @endforeach
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
@endsection