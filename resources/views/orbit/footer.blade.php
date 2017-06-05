
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2017 &copy; VLA &
                <a target="_blank" href="http://codeforaustralia.org">Code for Australia</a>                 
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
            <!-- BEGIN QUICK NAV -->
            <nav class="quick-nav hidden">
                <a class="quick-nav-trigger" href="#0">
                    <span aria-hidden="true"></span>
                </a>
                <ul>
                    <li>
                        <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" target="_blank" class="active">
                            <span>Purchase Metronic</span>
                            <i class="icon-basket"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/reviews/4021469?ref=keenthemes" target="_blank">
                            <span>Customer Reviews</span>
                            <i class="icon-users"></i>
                        </a>
                    </li>
                    <li>
                        <a href="http://keenthemes.com/showcast/" target="_blank">
                            <span>Showcase</span>
                            <i class="icon-user"></i>
                        </a>
                    </li>
                    <li>
                        <a href="http://keenthemes.com/metronic-theme/changelog/" target="_blank">
                            <span>Changelog</span>
                            <i class="icon-graph"></i>
                        </a>
                    </li>
                </ul>
                <span aria-hidden="true" class="quick-nav-bg"></span>
            </nav>
            <div class="quick-nav-overlay"></div>
            <!-- END QUICK NAV -->
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
            
            <script src="/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
            
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
            <!-- ------------------------------------------ app loaded js ------------------------------------------ -->
            @yield('scripts')

            <!-- ------------------------------------------ template loaded js ------------------------------------------ -->
            <script type="text/javascript">
                @yield('inline-scripts')
            </script>

            <script>
                $(document).ready(function()
                {
                    $('#clickmewow').click(function()
                    {
                        $('#radio1003').attr('checked', 'checked');
                    });
                })
            </script>