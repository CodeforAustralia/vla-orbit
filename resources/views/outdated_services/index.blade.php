@extends ('orbit.master')

@section ('content')

    <!-- BEGIN PAGE HEADER-->
    <div class="portlet ">
        <h1 class="page-title col-xs-10"> Outdated Services </h1>
        <br>
    </div>
    <!-- END PAGE HEADER-->

    <!-- Begin: Datatable services -->
    <div class="portlet light portlet-fit portlet-datatable outdated_services">
        <div class="portlet-body">
            <table>
                <div class="table-responsive">
                    <table-local></table-local>
                </div>
            </table>
        </div>
    </div>
    <!-- End: Datatable services -->
@endsection
@section('styles')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection
@section('scripts')
    <script src="{{ asset('js/outdated_services.js') }}?id={{ str_random(6) }}"></script>
@endsection