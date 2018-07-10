<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>{{ strtoupper(config('app.name')) }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of {{ ucfirst(config('app.name')) }}" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/layouts/layout2/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout2/css/custom.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout2/css/loading.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!-- SELECT2 LAYOUT STYLES -->
        <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END SELECT2 LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
        @yield('styles')

        <script src="/js/ga.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    </head>
    <!-- END HEAD -->

    <body class="login">

        @yield ('content')

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
        <!--Start of Tawk.to Script-->
        <script src="/js/tawk.js" type="text/javascript"></script>
        <!--End of Tawk.to Script-->
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

    </body>

</html>