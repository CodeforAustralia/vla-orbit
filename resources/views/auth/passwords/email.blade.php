@extends ('orbit.login_page')

@section('content')

    <!-- BEGIN : LOGIN PAGE 5-2 -->
    <div class="user-login-5">
        <div class="row bs-reset">
            <div class="col-md-6 login-container bs-reset">
                <img class="login-logo login-6" src="../assets/pages/img/login/login-invert.png" />

                <div class="login-content">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <h1>{{ strtoupper(config('app.name')) }} reset password</h1>
                    <p> Please provide your email. </p>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel panel-default">

                                <div class="panel-heading">Reset Password</div>
                                <div class="panel-body">


                                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Send Password Reset Link
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="login-footer">
                    <div class="row bs-reset">
                        <div class="col-xs-5 bs-reset">
                            <ul class="login-social">
                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-social-github"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-7 bs-reset">
                            <div class="login-copyright text-right">
                                <p>Copyright © VLA 2018</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 bs-reset">
                <div class="login-bg"> </div>
            </div>
        </div>
    </div>
    <!-- END : LOGIN PAGE 5-2 -->
@endsection

@section('styles')

  <!-- BEGIN PAGE LEVEL STYLES -->
  <link href="/assets/pages/css/login-5.min.css" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL STYLES -->

@endsection

@section('scripts')

  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
  <script src="/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="/assets/pages/scripts/login-5.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL SCRIPTS -->

@endsection