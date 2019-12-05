
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2020 &copy; VLA &
                <a target="_blank" href="http://codeforaustralia.org">Code for Australia</a>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
         </div>
        <!-- END FOOTER -->
        <!-- BEGIN MODAL SELECT SP -->
        <div id="select_sp_modal" class="modal fade bs-modal-lg" tabindex="-1" aria-hidden="true" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Set up profile</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid" data-always-visible="1" data-rail-visible1="1">
                            <div class="row" id="sp-content" style="display:block">
                                <div class="form-group">
                                    <div class=" input-group col-md-6">
                                        <label for="sp_id">Choose your office/program area from the list to get the correct access:</label>
                                        @if(isset($service_providers))
                                        <select class="form-control" id="sp_id" name="sp_id">
                                        @foreach($service_providers as $service_provider)
                                            <option value="{{ $service_provider['ServiceProviderId'] }}"> {{ $service_provider['ServiceProviderName'] }} </option>
                                        @endforeach
                                        </select>
                                        @endif
                                        <label><small>Not listed? send us an email on {{ env('APP_TEAM_EMAIL', 'LHO@vla.vic.gov.au') }}</small></label>
                                    </div>
                                </div>
                                   <div class="form-group">
                                    <div class=" input-group col-md-6">
                                        <label for="full_name" id="full_namel">Full name: <small>Optional</small></label>
                                        <input class="form-control" type="text" autocomplete="off" id="full_name" name="full_name"/>
                                    </div>
                                </div>
                                <button type="button" class="btn green-jungle btn-lg pull-center" id="sp_send">Update profile</button><br>
                            </div>
                        </div>
                        <div class="row" id="sp-result" style="display:none">
                          <div class="col-xs-12 text-center">
                            <p style="font-size: 126px;"><i class="fa fa-check-circle" style="color: #5cb85c;background-color: #fff;"></i></p>
                            <h3><strong>Changes done successful</strong></h3>
                            <button type="button" onClick="window.location='/';" class="btn default blue btn-lg"><span>Close</span></button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!--[if lt IE 9]>
            <script src="/assets/global/plugins/respond.min.js"></script>
            <script src="/assets/global/plugins/excanvas.min.js"></script>
            <script src="/assets/global/plugins/ie8.fix.min.js"></script>
            <![endif]-->
            <!-- BEGIN CORE PLUGINS -->
            <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL SCRIPTS -->
            <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
            <!-- END THEME GLOBAL SCRIPTS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            <script src="/js/matter_type.js?id={{ str_random(6) }}" type="text/javascript"></script>
            <script src="/js/modal-hierarchy.js" type="text/javascript"></script>

            <script src="/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>

            <script src="/assets/pages/scripts/ui-sweetalert.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>

            <!-- END PAGE LEVEL SCRIPTS -->
            <!-- BEGIN THEME LAYOUT SCRIPTS -->
            <script src="/assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
            <script src="/assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
            <script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
            <script src="/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
            <!-- END THEME LAYOUT SCRIPTS -->
            <!-- SELECT2 LAYOUT SCRIPTS -->
            <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
            <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
            <!-- END SELECT2 LAYOUT SCRIPTS -->
            <!-- BEGIN multiselect PLUGIN -->
            <script src="/assets/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
            <!-- END multiselect PLUGIN -->

            <!--Start of HotJar to Script-->
            <script src="/js/hotjar.js" type="text/javascript"></script>
            <!--End of HotJar to Script-->

            <!-- include summernote js-->
            <script src="/js/summernote.js"></script>
            <script src="/js/summernote-cleaner.js"></script>
            <!-- end include summernote js-->
            {{-- @if ( \App\Http\helpers::firstFridayOfMonth() )
            <!-- If first friday of the month display net propmoter score form -->
            <script src="/js/survey-monkey.js" type="text/javascript"></script>
            <!-- END first friday of the month display net propmoter score form -->
            @endif --}}

            <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>

            <!-- ------------------------------------------ app loaded js ------------------------------------------ -->
            @yield('scripts')
            @yield('scripts-extra')
            <!-- ------------------------------------------ template loaded js ------------------------------------------ -->

            <script type="text/javascript">
                @yield('inline-scripts')
            </script>

            @if(\App\Http\helpers::getUSerServiceProviderId() === 0 &&  \App\Http\helpers::getRole() === 'VLA')
            <script>
            $('#select_sp_modal').modal('show');
            </script>
            @endif

            <script>
                $(document).ready(function()
                {
                    $('#clickmewow').click(function()
                    {
                        $('#radio1003').attr('checked', 'checked');
                    });
                })
            </script>
            <script src="/js/notifications_vue.js?id={{ str_random(6) }}" type="text/javascript"></script>
            <script src="/js/footer.js?id={{ str_random(6) }}" type="text/javascript"></script>