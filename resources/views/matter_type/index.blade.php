@extends ('orbit.master')

@section ('content')
	
    @include ('orbit.alert')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Matter Types </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/matter_type/new">New Matter Type</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-container">
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_matter_type">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="10%"> Id </th>
                            <th width="30%"> Name </th>
                            <th width="20%"> Created By </th>
                            <th width="20%"> Updated By </th>
                            <th width="10%"> Created On </th>
                            <th width="10%"> Updated On </th>
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