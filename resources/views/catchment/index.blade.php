@extends ('orbit.master')

@section ('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Catchment </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/catchment/new">New Catchment</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_catchment">
                    <thead>
                        <tr role="row" class="heading">
                            <!-- <th width="10%"> Id </th> -->
                            <th width="30%"> Postcode </th>
                            <th width="20%"> Suburb </th>
                            <th width="20%"> Local goverment council </th>
                            <!-- <th width="10%"> </th> -->
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
    
@endsection