
        <!-- /.modal -->
        <div id="login_modal" class="modal fade" tabindex="-1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">                        
                        <h4 class="modal-title">Login</h4>
                    </div>
                    <div class="modal-body">
                        <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">

                            <div class="row">

                                <div class="col-sm-6 innerLink">
                                    <div class="mt-widget-4">
                                        <div class="mt-img-container">
                                            <img src="/assets/global/img/vla_sign.jpg"> </div>
                                        <div class="mt-container bg-purple-opacity">
                                            <br>
                                            <div class="mt-head-title"> VLA users <br><br> click here to login </div>
                                            <br>
                                            <br>
                                            <div class="mt-footer-button">
                                                <a href="/login_vla" class="btn btn-circle btn-danger btn-sm">Login</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 innerLink">
                                    <div class="mt-widget-4">
                                        <div class="mt-img-container">
                                            <img src="/assets/global/img/dashboard.png"> </div>
                                        <div class="mt-container bg-yellow-opacity">
                                            <br>
                                            <div class="mt-head-title"> Login with user and password </div>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="mt-footer-button">
                                                <a href="/login" class="btn btn-circle yellow-lemon btn-sm">Login</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


@section('inline-scripts')
  $(document).ready(function() {

        $('#login_modal').modal('show');

        //Thanks to Css Tricks for this idea https://css-tricks.com/snippets/jquery/make-entire-div-clickable/
        $(".innerLink").click(function() {
          window.location = $(this).find("a").attr("href"); 
          return false;
        });
  });
@endsection