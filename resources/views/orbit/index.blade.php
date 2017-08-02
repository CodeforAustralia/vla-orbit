@extends ('orbit.master')

@section ('content')
    
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Dashboard </h1>
    <!-- END PAGE HEADER-->
    
    <!-- BEGIN DASHBOARD STATS 1-->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="7">0</span>
                    </div>
                    <div class="desc"> Sent Referrals </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="#">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="12">0</span></div>
                    <div class="desc"> Today's Bookings </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 green" href="#">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="35">0</span>
                    </div>
                    <div class="desc"> Services this week </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number"> 
                        <span data-counter="counterup" data-value="89"></span>% </div>
                    <div class="desc"> Quality of referrals </div>
                </div>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    
    <div class="row">
        <!-- BEGIN THUMBNAILS PORTLET-->
        <div class="col-sm-12 col-md-6">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-book font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold">REFERRAL SCHOOL</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="thumbnail">
                                <img src="/assets/layouts/layout2/img/overlay.jpg" alt="">
                                <div class="caption">
                                    <h3>How to create Services</h3>
                                    <p> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                    <p>
                                        <a href="javascript:;" class="btn red"> Read More </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END THUMBNAILS PORTLET-->
                       
        <!-- BEGIN WELL PORTLET-->

        <div class="col-sm-12 col-md-6">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">WAll</span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="note note-info">
                        <h4 class="block bold font-grey-mint">Announcements</h4>
                        <p> Thanks for using Orbit. We are currently testing the service and need to know what is working, what isn't and if something is missing. Please send any feedback to <a href="mailto:orbitgeneric@gmail.com">orbitgeneric@gmail.com</a> or use the chat tool. </p>
                        <p></p>
                        <p>- Orbit team</p>
                    </div>
                    <div class="note note-info">
                        <h4 class="block bold font-grey-mint">Keywords</h4>
                        <p> To help navigate the search for the right legal issue an overview of the taxonomy used in Orbit with related keywords are available <a href="https://docs.google.com/spreadsheets/d/1L-pg_KxX9WP29CsE--3OvgyFE8BjL7CmgPBFHBw4-KM/" target="_blank">here</a>. </p>
                    </div>
                    <div class="note note-info">
                        <h4 class="block bold font-grey-mint">Non-Legal Referrals</h4>
                        <p> Find a list of alternative referral options <a href="https://docs.google.com/document/d/1meV27i-3VT2o4zqN7bCWDoAa8gia96adz6u0V9qVtgs/" target="_blank">here</a>.</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- END WELL PORTLET-->
    </div>
    

@endsection