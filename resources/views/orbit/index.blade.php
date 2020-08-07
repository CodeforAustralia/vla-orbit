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
                        <span data-counter="counterup" data-value="{{ $stats->NoOfReferralsThisYear }}">0</span></div>
                    <div class="desc"> Referrals this year </div>
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
                        <span data-counter="counterup" data-value="{{ $stats->NoOfNRE }}">0</span>
                    </div>
                    <div class="desc"> Information emails sent this year </div>
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
                        <span data-counter="counterup" data-value="{{ $stats_year }}">0</span>
                    </div>
                    <div class="desc"> Bookings this year </div>
                </div>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->

    <div class="row">

        <!-- BEGIN WELL PORTLET-->
        <div class="col-sm-12 col-md-6">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">Notifications</span>
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

                        <div class="col-sm-6">
                            <div class="thumbnail">
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/sxP9jOCJs4s" frameborder="0" allowfullscreen></iframe>
                                </div>

                                <div class="caption hidden">
                                    <h3 class="">What is {{ ucfirst(config('app.name')) }}?</h3>
                                    <p class=""> </p>
                                    <p class="hidden">
                                        <a href="javascript:;" class="btn red"> Read More </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="thumbnail">
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/YO_Rsa3VBzs" frameborder="0" allowfullscreen></iframe>
                                </div>

                                <div class="caption hidden">
                                    <h3 class="">How to make a Referral?</h3>
                                    <p class=""> </p>
                                    <p class="hidden">
                                        <a href="javascript:;" class="btn red"> Read More </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="thumbnail">
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Rn1Xzf4qNCU" frameborder="0" allowfullscreen></iframe>
                                </div>

                                <div class="caption ">
                                    <h3 class="hidden">How to make a Referral?</h3>
                                    <p class="margin-bottom-0 small"> How to control the referrals coming to your organisation through Legal Help Online </p>
                                    <p class="hidden">
                                        <a href="javascript:;" class="btn red"> Read More </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="thumbnail">
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe width="100%" src="https://web.microsoftstream.com/embed/video/5aabe04a-4747-4533-b5bd-dd9881058abd?autoplay=false&amp;showinfo=false" allowfullscreen style="border:none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; height: 100%; max-width: 100%;">
                                </div>

                                <div class="caption ">
                                    <h3 class="hidden"></h3>
                                    <p class="margin-bottom-0 small"> How to update services in LHO (for VLA ASMs and other Service Directory updaters)</p>
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

        <!-- BEGIN THUMBNAILS PORTLET-->
        <div class="col-sm-12 col-md-6 hidden">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-book font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold">Presentations</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="thumbnail">
                            <!-- 16:9 aspect ratio -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/gYXmhQu2Hqs" frameborder="0" allowfullscreen></iframe>
                                </div>

                                <div class="caption hidden">
                                    <h3 class="">Showcase</h3>
                                    <p class=""> </p>
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

    </div>


@endsection