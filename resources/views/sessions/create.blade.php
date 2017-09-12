@extends ('orbit.login_page')


@section ('content')
   
    <!-- BEGIN : LOGIN PAGE 5-2 -->
    <div class="user-login-5">
        <div class="row bs-reset">
            <div class="col-md-6 login-container bs-reset">
                <img class="login-logo login-6" src="../assets/pages/img/login/login-invert.png" />
                <div class="login-content">
                    <h1>ORBIT Login</h1>
                    <p> Please provide your credentials, if you are a VLA user please use the link below. </p>
                    <form method="POST" action="/login" class="login-form" >

                        {{ csrf_field() }}

                        <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            <span>Enter any username and password. </span>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Email" id="email" name="email" required/> </div>

                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" id="password" required/> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-8 text-right">
                                <div class="forgot-password">
                                    <a href="/login_vla" class="forget-password">Login with VLA credentials</a>
                                </div>
                                <button class="btn blue" type="submit">Sign In</button>
                            </div>
                        </div>            
                        
                        <br><br><br><br><br><br>
                        
                        @include ('orbit.errors')

                    </form>
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
                                <p>Copyright © VLA 2017</p>
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
  <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
  <script src="/assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="/assets/pages/scripts/login-5.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL SCRIPTS -->

@endsection