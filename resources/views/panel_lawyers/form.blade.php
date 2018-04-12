    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gavel"></i>{{ isset($current_panel_lawyers) ? 'Edit ' : 'New ' }}Panel Lawyers
            </div>
        </div>
        <div class="portlet-body form">           
            <!-- BEGIN FORM-->
            @include ('orbit.errors')
            <form method="POST" action="/panel_lawyers{{ (isset($current_panel_lawyers) ? '/update' : '') }}" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="form-group hidden">
                        <input type="text" class="form-control" id="id" name="id" value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->id : 0 }}" required>
                    </div>

                    <div class="form-group">
                        <label for="firm_name" class="col-md-3 control-label">Firm Name:</label>
                        <div class="col-md-4">
                        	<input type="text" class="form-control" id="firm_name" name="firm_name" value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->firm_name : '' }}" required>
                    	</div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="address">Address:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="address" name="address"  value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->address : '' }}" placeholder="" required>
                        </div>
                            <button type="button" class="btn default" id="get_address" >Get Lat and Lng</button>
                    </div>                               
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="lat">Latitude:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="lat" name="lat" step="0.000001" value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->lat : '' }}" placeholder="" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="lng">Longitude:</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" id="lng" name="lng" step="0.000001" value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->lng : '' }}" placeholder="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="phone">Phone:</label>
                        <div class="col-md-4">
                            <input type="tel" class="form-control" id="phone" name="phone"  value="{{ isset($current_panel_lawyers) ? $current_panel_lawyers->phone : '' }}" placeholder="" required minlength=10 maxlength=10>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle green">Save</button>
                            <button type="button" onclick="window.location='/panel_lawyers';return false" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                    </div>
                </div>                               
            </form>
            <!-- END FORM-->
        </div>
    </div>
@section('scripts')  

    <script src="/js/panel-lawyers.js?id={{ str_random(6) }}" type="text/javascript"></script>    

@endsection

@section('styles')    

@endsection