@extends ('orbit.master')

@section ('content')

    @include ('orbit.alert')
    
    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All SMS Templates </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/sms_template/new">New SMS Template</a>
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
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax_sms_templates">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="30%"> Service Name </th>
                            <th width="60%"> Template </th>
                            <th width="10%">  </th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End: Datatable referrals -->
@endsection