@extends ('orbit.master')

@section ('content')
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> Outbound Referrals </h1>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable referrals -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_outbound_referrals">
                    <thead>
                        <tr role="row" class="heading">
                            <th colspan="6" class="text-left-padding">Details</th>
                            <th colspan="2">To</th>
                            <th colspan="1">From</th>
                        </tr>
                        <tr role="row" class="heading">
                            <th> Id </th>
                            <th> Date </th>
                            <th> Legal Issue </th>
                            <th> Location </th>
                            <th> Contact </th>
                            <th> Reason </th>
                            <th> Service Provider </th>
                            <th> Service Name </th>
                            <th> User </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable referrals -->
@endsection