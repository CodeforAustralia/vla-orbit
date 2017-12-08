@extends ('orbit.master')

@section ('content')

    <div class="row widget-row">
        <div class="col-md-3">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                <h4 class="widget-thumb-heading">Bookings</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green icon-bulb"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">this year</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$bookings['year']}}">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        
        <div class="col-md-3">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                <h4 class="widget-thumb-heading">Bookings</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-purple icon-calendar"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">{{ date("M", strtotime(date('Y-m')." -1 month")) }}</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$bookings['previous_month']}}">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>

        <div class="col-md-3">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                <h4 class="widget-thumb-heading">Bookings</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red icon-calendar"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">{{ date("M") }}</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$bookings['month']}}">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        
        <div class="col-md-3">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 ">
                <h4 class="widget-thumb-heading">Referrals</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-blue icon-paper-plane"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">Total</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$bookings['total_referrals']}}">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN ROW -->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN CHART PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bar-chart font-green-haze"></i>
                                <span class="caption-subject bold uppercase font-green-haze"> Total bookings per service</span>
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="javascript:;" class="fullscreen"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="chart_1" class="chart" style="height: 200px;"> </div>
                        </div>
                    </div>
                    <!-- END CHART PORTLET-->
                </div>
            </div>
            <!-- END ROW -->
            <!-- BEGIN ROW -->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN CHART PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bar-chart font-green-haze"></i>
                                <span class="caption-subject bold uppercase font-green-haze"> Average time to get a booking per service</span>
                                <span class="caption-helper">in number of days</span>
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="javascript:;" class="fullscreen"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div id="chart_2" class="chart" style="height: 200px;"> </div>
                        </div>
                    </div>
                    <!-- END CHART PORTLET-->
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>

@endsection

@section('scripts-extra')
    <script src="/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

    <script src="/js/statistics.js" type="text/javascript"></script>
@endsection