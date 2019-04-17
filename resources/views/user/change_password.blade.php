@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Change Password </h1>
    <!-- END PAGE HEADER-->


    <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i>User Password</div>
            </div>
            <div class="portlet-body form">

                <form method="POST" action="/change_password" class="form-horizontal">

                    <div class="form-body">
                        {{ csrf_field() }}

                        <input type="text" class="form-control hidden" id="id" name="id" value="{{ Auth::user()->id }}" required>

                        <div class="form-group">
                            <label for="old_password" class="col-md-3 control-label">Old Password</label>

                            <div class="col-md-4">
                                <input type="password" class="form-control" id="old_password" name="old_password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-3 control-label">New Password</label>

                            <div class="col-md-4">
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-md-3 control-label">Confirm New Password</label>

                            <div class="col-md-4">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle green">Submit</button>
                            </div>
                        </div>
                    </div>

                </form>

                @include ('orbit.errors')
            </div>
        </div>

@endsection