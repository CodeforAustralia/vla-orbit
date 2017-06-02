
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>Service Providers</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/service_provider" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="sp_id" name="sp_id" value="{{ isset($current_sp) ? $current_sp->ServiceProviderId : 0 }}" required>
                    </div>

	                <div class="form-group">
	                  	<label for="some">Name:</label>
	                  	<input type="text" class="form-control" id="name" name="name"  value="{{ isset($current_sp) ? $current_sp->ServiceProviderName : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">About:</label>
	                  	<textarea class="form-control" rows="5" class="form-control" id="about" name="about" required>{{ isset($current_sp) ? $current_sp->ServiceProviderAbout : '' }}</textarea>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Url:</label>
	                  	<input type="text" class="form-control" id="url" name="url" value="{{ isset($current_sp) ? $current_sp->ServiceProviderURL : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Address:</label>
	                  	<input type="text" class="form-control" id="address" name="address" value="" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Logo:</label>
	                  	<input type="text" class="form-control" id="logo" name="logo" value="{{ isset($current_sp) ? $current_sp->ServiceProviderLogo : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Name:</label>
	                  	<input type="text" class="form-control" id="contact_name" name="contact_name" value="{{ isset($current_sp) ? $current_sp->ContactName : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Phone:</label>
	                  	<input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ isset($current_sp) ? $current_sp->ContactPhone : '' }}" required>
	                </div>

	                <div class="form-group">
	                  	<label for="some">Contact Email:</label>
	                  	<input type="text" class="form-control" id="contact_email" name="contact_email" value="{{ isset($current_sp) ? $current_sp->ContactEmail : '' }}" required>
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