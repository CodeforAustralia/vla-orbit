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
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/4Z4iC1eD3Es" frameborder="0" allowfullscreen></iframe>
                                </div>                               
                                
                                <div class="caption hidden">
                                    <h3 class="">Request a new service</h3>
                                    <p class=""> Does your office provide a service that isn't in ORBIT yet? Any local services you refer to a lot and would like to see as a referral option?
                                    Complete <a target="_blank" href="https://www.surveymonkey.com/r/Orbit_request_service">this form</a> to request a new service added to ORBIT and we'll check that the service qualifies as a referral option.</p>
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
                    @foreach( $dashboards as $dashboard )
                        @include('dashboard.dashboard_partial')
                    @endforeach
                </div>
            </div>

        </div>
        <!-- END WELL PORTLET-->
    </div>
    

@endsection