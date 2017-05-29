@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create Service Provider </h1>
    <!-- END PAGE HEADER-->
    
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>New Service Providers</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/service_provider" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

	                <div class="form-group">
	                  	<label for="some">Name:</label>
	                  	<input type="text" class="form-control" id="name" name="name" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">About:</label>
	                  	<input type="text" class="form-control" id="about" name="about">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Url:</label>
	                  	<input type="text" class="form-control" id="url" name="url">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Address:</label>
	                  	<input type="text" class="form-control" id="address" name="address">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Logo:</label>
	                  	<input type="text" class="form-control" id="logo" name="logo">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Name:</label>
	                  	<input type="text" class="form-control" id="contact_name" name="contact_name">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Phone:</label>
	                  	<input type="text" class="form-control" id="contact_phone" name="contact_phone">
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Email:</label>
	                  	<input type="text" class="form-control" id="contact_email" name="contact_email">
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