@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Matter </h1>

        @if ( in_array( \App\Http\helpers::getRole(), ['Administrator']) )
            <a role="button" class="btn main-green col-xs-2 pull-right" href="/matter/new">New Matter</a>
        @endif
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <div class="table-actions-wrapper">
                    <div id="service_filter" class="dataTables_filter"><label>Search: <input type="search" id="search_box" class="" placeholder="" aria-controls="service"></label></div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_matter">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="10%"> Id </th>
                            <th width="30%"> Name </th>
                            <th width="20%"> Tag </th>
                            <th width="20%"> Description </th>
                            <th width="10%"> Parent name </th>
                            <th width="10%"> Matter Type </th>
                            <th width="10%"> </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
@endsection