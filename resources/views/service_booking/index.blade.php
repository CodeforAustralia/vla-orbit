@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Services Booking </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/service_booking/new">New Service Booking</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->
    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_booking_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service booking"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_service_booking">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%"> Id </th>
                            <th width="10%"> Service Provider </th>
                            <th width="10%"> Service Id </th>
                            <th width="10%"> Service Name </th>
                            <th width="10%"> Booking Service Id </th>
                            <th width="10%"> Booking Service Name </th>
                            <th width="10%"> Actions</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable services -->
@endsection