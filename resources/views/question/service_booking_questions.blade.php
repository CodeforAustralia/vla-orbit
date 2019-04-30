@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Service Booking Questions </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/question/new/3">New Question</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div id="dTables" class="service_data_table">
                <data-table
                    fetch-url="/question/list_service_booking_questions"
                    show-url=""
                    edit-url="/question/show"
                    delete-url="/question/delete"
                    title="Service Booking Questions"
                    per-page="20"
                    model="question"
                    identifier="QuestionId"
                    :columns="[
                                'QuestionId',
                                'QuestionName'
                            ]"
                ></data-table>
            </div>
        </div>
    </div>
    <!-- End: Datatable -->
@endsection
@section('scripts')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection