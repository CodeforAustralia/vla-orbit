@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> Emails sent </h1>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Demo Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div class="table-responsive">
                <div id="dTables">
                    <data-table
                        fetch-url="/no_reply_emails/listAllLogRecordBySection"
                        show-url=""
                        edit-url=""
                        delete-url=""
                        per-page="50"
                        title="Information Emails"
                        identifier="id"
                        :columns="['id','sent_by' , 'subject' , 'sent_date_and_time']"
                    ></data-table>
                </div>
            </div>
        </div>
    </div>
    <!-- End: Demo Datatable services -->
@endsection
@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection