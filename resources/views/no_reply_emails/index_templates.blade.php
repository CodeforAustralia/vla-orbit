@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> No-Reply Email Templates </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/no_reply_emails/templates/new">Create Template</a>
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
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_nre_templates">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="2%"> Id </th>
                            <th width="10%"> Template Name </th>
                            <th width="10%"> Subject </th>
                            <th width="10%"> Available for </th>
                            <th width="3%">  </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
@endsection