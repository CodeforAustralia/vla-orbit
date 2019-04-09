@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> All Users ({{ $total_users }})</h1>
        <a role="button" class="btn main-green col-xs-2 pull-right" href="/user/new">New User</a>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable ">
        <div class="portlet-body">
            <div id="dTables">
                <data-table
                    fetch-url="/user/list_table"
                    show-url=""
                    edit-url="/user/show"
                    delete-url="/user/delete"
                    title="Users"
                    per-page="20"
                    identifier="id"
                    :columns="[
                                'id',
                                'name',
                                'email',
                                'sp_id',
                                'role'
                            ]"
                ></data-table>
            </div>
        </div>
    </div>
    <!-- End: Datatable services -->
@endsection

@section('scripts')
    <script src="{{ asset('js/tables.js') }}?id={{ str_random(6) }}"></script>
@endsection