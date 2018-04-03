@extends ('orbit.master')

@section ('content')

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
          <div class="portlet-title">
            <div class="caption">
              <span class="caption-subject font-green sbold uppercase"><i class="icon-list font-green"></i>&nbsp;  Legal Help Bookings </span>
            </div>
          </div>
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_bookings_legal_help">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="5%"> Id </th>
                            <th width="10%"> Date </th>
                            <th width="5%"> Time </th>
                            <th width="10%"> Service </th>
                            <th width="10%"> Service Provider </th>
                            <th width="10%"> First Name </th>
                            <th width="10%"> Last Name </th>
                            <th width="10%"> Email </th>
                            <th width="10%"> Phone </th>
                            <th width="10%"> Sent SMS </th>
                            <th width="10%"> Made By </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
                
@endsection