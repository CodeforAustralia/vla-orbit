@extends ('orbit.master')

@section ('content')

    @include ('orbit.alert')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Services </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/service/new">New Services</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_service">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%"> Id </th>
                            <th width="25%"> Name </th>
                            <th width="15%"> Service Provider </th>
                            <th width="10%"> Phone </th>
                            <th width="5%"> Email </th>
                            <th width="10%"> Service type </th>
                            <th width="5%"> Service Level </th>
                            <th width="30%"> Actions</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable services -->
    
@endsection
