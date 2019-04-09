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
            <div class="table-responsive">
                <div id="dTables">
                    <data-table
                        fetch-url="/referral/list_outbound_pager"
                        show-url=""
                        edit-url=""
                        delete-url=""
                        per-page="50"
                        title="Outbound Referrals"
                        identifier="id"
                        show-print
                        :columns="['id','date', 'legal_issue', 'location' , 'contact' ,'reason', 'service_provider', 'service_name', 'user']"
                    ></data-table>
                </div>
            </div>
        </div>
    </div>
    <!-- End: Datatable referrals -->
@endsection
@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection