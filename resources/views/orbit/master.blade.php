<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex">
        <title>{{ strtoupper(config('app.name')) }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of {{ ucfirst(config('app.name')) }}" name="description" />
        <meta content="Code For Australia and Victoria Legal Aid" name="author" />
        <meta name="_token" content="{{ csrf_token() }}"/>
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
        <link href="/assets/layouts/layout2/css/themes/blue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/layouts/layout2/css/custom.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout2/css/loading.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout2/css/xl-grid-bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!-- SELECT2 LAYOUT STYLES -->
        <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
        <!-- END SELECT2 LAYOUT STYLES -->
        <!-- include summernote css-->
        <link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
        <!-- End summernote css-->

        <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-92x92.png" sizes="92x92">

        @yield('styles')

        <script src="/js/ga.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">

        @include ('orbit.header')

        <!-- BEGIN CONTAINER -->
        <div class="page-container">

            @if(Auth::check())
                @include ('orbit.sidebar')
            @else
                @include ('orbit.login_modal')
            @endif
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">

                    @include ('orbit.alert')

                    @yield ('content')

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <?php  /** side bar removed **/ ?>
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->

        @include ('orbit.footer')

        @include ('orbit.loading_modal')

    </body>

</html>