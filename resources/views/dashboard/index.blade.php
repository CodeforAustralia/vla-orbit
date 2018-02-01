@extends ('orbit.master')

@section ('content')
    
    <!-- BEGIN PAGE HEADER-->
    <h1 class="page-title"> Dashboard </h1>
    <!-- END PAGE HEADER-->
    <div class="row">  
        <!-- BEGIN WELL PORTLET-->

        <div class="col-sm-12 col-md-6">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">Wall</span>
                    </div>
                    <a href="/dashboard/new" class="btn btn-sm green pull-right"> New Message </a> 
                </div>

                <div class="portlet-body column">
                    @foreach( $dashboards as $dashboard )
                        @include('dashboard.dashboard_partial')
                    @endforeach
                </div>
            </div>

        </div>
        <!-- END WELL PORTLET-->
    </div>
    

@endsection

@section('styles')

@endsection

@section('scripts')
    <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/portlet-draggable.min.js" type="text/javascript"></script>
    <script src="/js/dashboard.js" type="text/javascript"></script>
@endsection


@section('inline-scripts')
    

@endsection