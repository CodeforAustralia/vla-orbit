@extends ('orbit.master')

@section ('content')

    @include ('orbit.alert')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Service Level </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/service_level/new">New Service Level</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->
    
    <!-- Begin: Datatable service Level-->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_service_level">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="10%"> Id </th>
                            <th width="25%"> Service Level Name </th>
                            <th width="35%"> Service Level Description </th>
                            <th width="10%"> </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable service Level -->
@endsection