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
                        <span data-counter="counterup" data-value="{{ $stats->NoOfReferrals }}">0</span>
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
                        <span data-counter="counterup" data-value="{{ $stats_today->NoOfBookingToday }}">0</span></div>
                    <div class="desc"> Bookings Today </div>
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
                        <span data-counter="counterup" data-value="{{ $stats_week->NoOfBookingThisWeek }}">0</span>
                    </div>
                    <div class="desc"> Bookings this week </div>
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
                        <span data-counter="counterup" data-value="{{ $stats->NoOfBookingThisYear }}">0</span>
                    </div>
                    <div class="desc"> Bookings this year </div>
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
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/M-e5FlPKBGk" frameborder="0" allowfullscreen></iframe>
                                </div>                               
                                
                                <div class="caption">
                                    <h3 class="hidden">How to use Orbit</h3>
                                    <p class="hidden"> Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit. </p>
                                    <p class="hidden">
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
                        <h4 class="block bold font-grey-mint">Legal Help users!</h4>
                        <p> Bookings can now be made for <b>CRIMINAL LAW APPOINTMENTS</b> at these offices:</p>
                        <br>
                        <ul>
                            <li>Ringwood</li>
                            <li>Sunshine</li>
                            <li>CLM (summary crime)</li>
                        </ul>
                        <p>You can book clients in using the 'new booking' button on the top menu.</p>
                    </div>
                    <div class="note note-info">
                        <h4 class="block bold font-grey-mint">Announcements</h4>
                        <p> Thanks for using Orbit. We are currently testing the service and need to know what is working, what isn't and if something is missing. Please send any feedback to <a href="mailto:orbitteam@vla.vic.gov.au">orbitteam@vla.vic.gov.au</a> or use the chat tool. </p>
                        <br>
                        <p>- Orbit team</p>
                    </div>
                    <div class="note note-info">
                        <h4 class="block bold font-grey-mint">Finding the right legal matter</h4>
                        <p> To help you select the right legal matter, we're including a list of alternative search terms (for example, typing 'custody' will take you to 'Parenting disputes > Living arrangements').</p>
                        <br>
                        <p>This feature is <b>now available</b>. If you want to have a look at the taxonomy and the list of available search terms just click <a href="https://docs.google.com/spreadsheets/d/1L-pg_KxX9WP29CsE--3OvgyFE8BjL7CmgPBFHBw4-KM/" target="_blank">here</a>. </p>
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