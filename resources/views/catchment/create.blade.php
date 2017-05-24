@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create New Catchment </h1>
    <!-- END PAGE HEADER-->
    
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>New Catchment</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/catchment" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">

                <div class="form-group">
                  	<label for="postcode">Postcode:</label>
                  	<input type="text" class="form-control" id="postcode" name="postcode">
                </div>

                <div class="form-group">
                  	<label for="Suburb">Suburb:</label>
                  	<input type="text" class="form-control" id="Suburb" name="Suburb">
                </div>

                <div class="form-group">
                  	<label for="local_goverment_council">Local Goverment Council:</label>
                  	<input type="text" class="form-control" id="local_goverment_council" name="local_goverment_council">
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