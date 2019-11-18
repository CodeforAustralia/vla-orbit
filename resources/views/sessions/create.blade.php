@extends ('orbit.login_page')


@section ('content')

    <!-- BEGIN : LOGIN PAGE 5-2 -->
    <div class="user-login-5">
            <div class="col-md-6 login-container bs-reset col-xs-12" id='login-div'>
                <img class="login-logo login-6" src="../assets/pages/img/login/login-invert.png" />
                <div class="login-content" id="login-content">
                    <h1>{{ strtoupper(config('app.name')) }} Login</h1>
                    <form method="POST" action="/login" class="login-form" >
                        {{ csrf_field() }}

                        <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            <span>Enter any username and password. </span>
                        </div>
                        <div class="form-group">
                            <div class=" input-group col-xs-6">
                                <label for="email" id="email-login">Email </label>
                                <input class="form-control" type="text" autocomplete="off" id="email" name="email" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" input-group col-xs-6">
                                <label for="password" id="password-login">Password </label>
                                <input class="form-control" type="password" autocomplete="off" name="password" id="password" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 text-left">
                                <button class="btn blue" type="submit">Sign In</button>
                            </div>
                        </div>

                        <br><br><br><br>
                        @include ('orbit.errors')
                        @include ('orbit.alert')
                        <hr id="login_hr">
                        <div class="row">
                            <div class="col-sm-8 text-left">
                                <div class="forgot-password">
                                    <a href="#" id='login_signup' class="forget-password font-white-soft">Sign up</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="/password/reset" class="forget-password font-green-soft">Forgot your password?</a>
                                </div>
                            </div>
                        </div>
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
                                <p>Copyright © VLA 2019</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 login-container bs-reset">
                <div class="login-content">
                        <a href="/login_vla">
                        <img class="login-logo login-6 img-responsive" id="vla-login" src="../assets/pages/img/login/VLA_login_button.png" /></a> &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
    </div>
    <!-- END : LOGIN PAGE 5-2 -->
<!-- Sign up Modal -->
    <div class="modal fade in bs-modal-lg" id="singup" role="dialog" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Sign up</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                  <div class="container-fluid" >
                      <div class="row" id='login_message' style="display:block">
                        <form autocomplete="off">
                             <div class="form-group">
                                <label class="col-md-3 control-label">Message:</label>
                                <div class="col-md-8 col-lg-7">
                                  <textarea rows="7" class="form-control" id="login-message" name="login-message">Thanks for showing interest in {{ strtoupper(config('app.name')) }}. Please fill in your details below and an {{ strtoupper(config('app.name')) }} team member will get in touch shortly.&#13;&#10;&#13;&#10;Name:&#13;&#10;Email address:&#13;&#10;Organisation:
                                  </textarea>
                                </div>
                            </div>
                            <div class="col-xs-8 col-sm-6 col-xs-offset-2 col-sm-offset-3"><br>
                                <button type="button" class="btn green-jungle btn-block btn-lg pull-right" id="login_send">Send</button><br>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row" id="login-result" style="display:none">
                  <div class="col-xs-12 text-center">
                    <p style="font-size: 126px;"><i class="fa fa-check-circle" style="color: #5cb85c;background-color: #fff;"></i></p>
                    <h3><strong>Message Sent</strong></h3>
                    <button type="button" onClick="window.location='/login';" class="btn default blue btn-lg"><span>Ok</span></button>
                  </div>
                </div>
              </div><!-- Modal Body Close-->
          </div> <!-- Modal Content Close-->
      </div><!-- Modal Dialogue Close-->
    </div>  <!-- Modal Fade Close-->
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
  <script src="/js/login.js?id={{ str_random(6) }}" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->

  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="/assets/pages/scripts/login-5.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL SCRIPTS -->

@endsection