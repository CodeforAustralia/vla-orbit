@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Create Matter Type </h1>
    <!-- END PAGE HEADER-->
    
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>New Legal Matter Type</div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="POST" action="/matter_type" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Enter text" name="title">
                        </div>
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