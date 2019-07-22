@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> Configurations </h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/configuration/new">New Configuration</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div id="dTables" class="configuration_data_table">
                <data-table
                    fetch-url="/configuration/list_table"
                    show-url="#"
                    show-label="#"
                    edit-url="/configuration/show"
                    delete-url="/configuration/delete"
                    title="Configurations"
                    per-page="100"
                    model="configuration"
                    identifier="Key"
                    :columns="[
                                'Name',
                                'Key'
                            ]"
                    description=""
                ></data-table>
            </div>
        </div>
    </div>
    <!-- End: Datatable services -->
@endsection
@section('styles')
@endsection
@section('scripts')
    <script src="{{ asset('js/tables.js') }}?id={{ str_random(6) }}"></script>
@endsection